<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiControler;
use App\Models\Product;
use App\Models\Ticket;
use App\Models\TicketReference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
}
