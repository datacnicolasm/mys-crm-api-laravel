<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiControler;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
                    ->take(100)
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
                        ->get(['idrow',
                        'cod_ref',
                        'nom_ref',
                        'val_ref',
                        'cod_mar',
                        'cod_gru',
                        'cod_tip',
                        'stock_min',
                        'rotacion'
                        ]);

        return $this->showAll($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
