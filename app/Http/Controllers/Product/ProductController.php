<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiControler;
use App\Models\Product;
use App\Models\Ticket;
use App\Models\TicketReference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ProductController extends ApiControler
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('brand','group','type')
                    ->get(['idrow',
                    'cod_ref',
                    'nom_ref',
                    'val_ref',
                    'cod_mar',
                    'cod_gru',
                    'cod_tip'
                    ]);

        return $this->showAll($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show_one(Request $request)
    {
        if ($request->has('sku'))
        {
            $arg_value = $request->input('sku');
            $arg_input = 'cod_ref';
        } elseif ($request->has('idrow')) {
            $arg_value = $request->input('idrow');
            $arg_input = 'idrow';
        }

        $product = Product::with('brand','group','type')
                        ->where($arg_input, $arg_value)
                        ->first(['idrow',
                           'cod_ref',
                           'nom_ref',
                           'val_ref',
                           'cod_mar',
                           'cod_gru',
                           'cod_tip',
                           'stock_min',
                           'rotacion'
        ]);

        $saldoFinal = Product::saldoImportaciones($product->cod_ref);
        
        //Log::info('Consulta:', ['saldo'=>$saldoFinal]);

        return $this->showOne($product);
    }

    /**
     * Return Tickets product
     */
    public function tickets_product(Request $request)
    {
        if ($request->has('sku'))
        {
            $arg_value = $request->input('sku');
            $arg_input = 'cod_ref';
        } elseif ($request->has('idrow')) {
            $arg_value = $request->input('idrow');
            $arg_input = 'idrow';
        }

        $id_tickets = TicketReference::where($arg_input, $arg_value)
                    ->distinct()->pluck('idreg_ticket');

        $tickets = Ticket::with('type','customer','user')
                    ->whereIn('idreg', $id_tickets)
                    ->get();                   
                    
        return $this->showAll($tickets);
    }

    public function saldo_producto(Request $request)
    {
        $referencia = $request->input('referencia');

        $product = Product::where('cod_ref', $referencia)->first(['val_ref']);
        $saldoObj = Product::saldoEcommerce($referencia);

        if (!$product || !$saldoObj) {
            return $this->errorResponse('Referencia no encontrada o sin saldo', 404);
        }

        return $this->successResponse([
            'data' => [
                'saldo'   => (float) trim($saldoObj->Saldo_final),
                'val_ref' => $product->val_ref,
            ]
        ], 200);
    }


    public function saldo_productos(Request $request)
    {
        $data = $request->validate([
            'referencias' => 'required|array|max:6',
            'referencias.*' => 'required|string|max:50',
        ]);

        // Normaliza entradas (quita espacios)
        $refs = array_values(array_unique(array_map('trim', $data['referencias'])));

        // Trae precios y normaliza la llave (trim del cod_ref)
        $pricesByRef = Product::query()
            ->whereIn('cod_ref', $refs)
            ->pluck('val_ref', 'cod_ref')              // Collection: [ "07BB0359   " => 999 ]
            ->mapWithKeys(fn($v, $k) => [trim($k) => $v]); // [ "07BB0359" => 999 ]

        // Saldos (SP) por referencia, con caché corto para evitar reventar la BD
        $items = [];
        foreach ($refs as $ref) {
            $saldo = Cache::remember(
                "saldo_ecommerce:{$ref}:001_008",   // clave (incluye bodegas)
                now()->addSeconds(30),             // TTL corto (ajústalo: 10–60s)
                function () use ($ref) {
                    $res = Product::saldoEcommerce($ref);
                    return $res ? (float) trim($res->Saldo_final) : null;
                }
            );

            $items[] = [
                'cod_ref' => $ref,
                'val_ref' => $pricesByRef->get($ref),
                'saldo'   => $saldo,
            ];
        }

        return $this->successResponse(['data' => $items], 200);
    }
}
