<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\ApiControler;
use App\Mail\NoticeTicketMail;
use App\Models\Customer;
use App\Models\Notice;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TicketController extends ApiControler
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::with('type', 'customer', 'references', 'references.product')
            ->get();
        return $this->showAll($tickets);
    }

    /**
     * Get the list tickets for user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function indexUserFilter(Request $request)
    {

        $monthsAgo = now()->subMonths($request->input('time_month'))->format('Y-d-m H:i:s');

        if (trim($request->input('cod_mer')) == '05' || trim($request->input('cod_mer')) == '02') {
            $tickets = Ticket::with('type', 'customer', 'references', 'references.product')
                ->get();
        } else {
            $tickets = Ticket::with('type', 'customer', 'references', 'references.product')
                ->where('cod_user', $request->input('cod_mer'))
                //->where('fecha_aded', '>=', $monthsAgo)
                ->get();
        }

        return $this->showAll($tickets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, WhatsAppService $wa)
    {
        /**
         * Creación del ticket en la base de datos.
         */
        $ticket = Ticket::create([
            'cod_type'      =>  $request->input('cod_type'),
            'cod_user'      =>  $request->input('cod_user'),
            'cod_ter'       =>  $request->input('cod_ter'),
            'title_ticket'  =>  $request->input('title_ticket'),
            'des_ticket'    =>  $request->input('des_ticket'),
            'cod_pipeline'  =>  $request->input('cod_pipeline'),
            'cod_estado'    =>  $request->input('cod_estado'),
            'cod_creator'   =>  $request->input('cod_creator'),
            'fecha_aded'    =>  Carbon::now()->format('Y-d-m H:i:s')
        ]);

        /**
         * Creación de la notificación en la base de datos.
         */
        $notice = Notice::create([
            'id_ticket' => $ticket->idreg,
            'cod_user' => $request->input('cod_creator'),
            'title' => 'ha creado el ticket.',
            'text_notice' => 'Se ha creado un nuevo ticket.',
            'created_at' => Carbon::now()->format('Y-d-m H:i:s'),
            'updated_at' => Carbon::now()->format('Y-d-m H:i:s')
        ]);

        try {
            $customer = Customer::where('cod_ter', $request->input('cod_ter'))->first();

            $phone = '+57' . trim(strval(($customer->tel1)));
            $tpl     = 'new_ticket_crm';

            $result = $wa->sendTemplate($phone, $tpl, 'es_CO');

            if (WhatsAppService::isAccepted($result)) {
                $notice_wa = Notice::create([
                    'id_ticket' => $ticket->idreg,
                    'cod_user' => $request->input('cod_creator'),
                    'title' => 'ha notificado a Whatsapp.',
                    'text_notice' => 'Mensaje enviado al cliente ' . $phone,
                    'created_at' => Carbon::now()->format('Y-d-m H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-d-m H:i:s')
                ]);
            }

        } catch (\Throwable $e) {

            report($e);
        }

        return $this->showOne($ticket);
    }

    /**
     * Display the specified ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show_one(Request $request)
    {
        $tickets = Ticket::with(
            'type',
            'customer',
            'user',
            'creator',
            'references',
            'references.product',
            'notices',
            'notices.user'
        )
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
        $ticket = Ticket::where('idreg', $request->input('idreg'))->with('type', 'customer', 'user')->first();

        $ticket->cod_estado = $request->input('cod_estado');
        $ticket->des_ticket = $request->input('des_ticket');
        $ticket->cod_user = $request->input('cod_user');

        $ticket->save();

        $notice = Notice::create([
            'id_ticket' => $ticket->idreg,
            'cod_user' => $request->input('cod_user'),
            'title' => 'ha modificado el ticket.',
            'text_notice' => 'Se ha modificado el ticket.',
            'created_at' => Carbon::now()->format('Y-d-m H:i:s'),
            'updated_at' => Carbon::now()->format('Y-d-m H:i:s')
        ]);

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

    /**
     * Create notice for ticket
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createNotice(Request $request)
    {
        $notice = Notice::create([
            'id_ticket' => intval($request->input('id_ticket')),
            'cod_user' => strval($request->input('cod_user')),
            'title' => strval($request->input('title')),
            'text_notice' => strval($request->input('text_notice')),
            'created_at' => Carbon::now()->format('Y-d-m H:i:s'),
            'updated_at' => Carbon::now()->format('Y-d-m H:i:s')
        ]);

        return $this->showOne($notice);
    }
}
