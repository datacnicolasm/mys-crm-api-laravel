<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\ApiControler;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends ApiControler
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::where('clasific','1')
                        ->get(['idrow','cod_ter','nom_ter','dir','tel1','email','ciudad']);
        return $this->showAll($customers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show_one(Request $request)
    {
        $id = $request->input('idrow');
        $customers = Customer::with('tickets','tickets.type')
                        ->where('clasific','1')
                        ->where('idrow', $id)
                        ->get(['idrow','cod_ter','nom_ter','dir','tel1','email','ciudad']);
        
        return $this->showAll($customers);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
