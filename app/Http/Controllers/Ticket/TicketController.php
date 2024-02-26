<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\ApiControler;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends ApiControler
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::with('type','customer')
                    ->get();
        return $this->showAll($tickets);
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
     * Get tickets for the sku product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_product(Request $request)
    {
        $tickets = Ticket::with('type','customer')
                        ->where('cod_ref', $request->input('sku'))
                        ->get();

        return $this->showAll($tickets);
    }

    /**
     * Display the specified ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show_one(Request $request)
    {
        $tickets = Ticket::with('type','customer','user')
                        ->where('idreg', $request->input('idreg'))
                        ->get();

        return $this->showAll($tickets);
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
     * @return \Illuminate\Http\Response
     */
    public function update_one(Request $request)
    {
        $ticket = Ticket::where('idreg', $request->input('idreg'))->with('type','customer','user')->first();

        $ticket->cod_estado = $request->input('cod_estado');
        $ticket->des_ticket = $request->input('des_ticket');
        $ticket->cod_user = $request->input('cod_user');
        
        $ticket->save();

        return $this->showOne($ticket);
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
