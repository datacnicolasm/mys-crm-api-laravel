<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\ApiControler;
use App\Models\Product;
use App\Models\Ticket;
use App\Models\TicketReference;
use Illuminate\Http\Request;

class TicketRefController extends ApiControler
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets_ref = TicketReference::with('product')
                    ->get();
        return $this->showAll($tickets_ref);
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
        $ticketRef = TicketReference::create([
            'idreg_ticket'  =>  intval($request->input('idreg_ticket')),
            'cod_ref'       =>  $request->input('cod_ref'),
            'cantidad'      =>  intval($request->input('cantidad')),
            'val_uni'       =>  intval($request->input('val_uni'))
        ]);

        return $this->showOne($ticketRef);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
