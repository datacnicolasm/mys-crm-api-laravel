<?php

namespace App\Console;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Ticket;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function (WhatsAppService $wa) {
            $tickets_saldo = Ticket::where('cod_type', 1)
            ->whereNotIn('cod_estado', [2, 3])
            ->with('references')
            ->get()
            ->pluck('references')
            ->flatten()
            ->filter(function ($reference) {
                return Product::condicionalExistencias($reference->cod_ref);
            });

        foreach ($tickets_saldo as $ref) {

            try {
                $wa = new WhatsAppService();

                $ticket_ref = Ticket::where('idreg', $ref->idreg_ticket)->first();
                $customer = Customer::where('cod_ter', $ticket_ref->cod_ter)->first();
                $user = User::where('cod_mer', $ticket_ref->cod_user)->first();

                $name_mer = trim(ucfirst(strtolower(explode(" ", $user->nom_mer)[0])));
                $cod_ref = trim($ref->cod_ref);
                $cliente = trim($customer->repres);
                $phone = '+57' . trim($user->tel);
                $id_ticket = $ticket_ref->idreg;

                $template_vars = [$name_mer, $cod_ref, $cliente, $id_ticket];

                $tpl_mer = 'ref_ticket_crm';

                $result_mer = $wa->sendTemplateText($phone, $tpl_mer, $template_vars, 'en');

                Log::debug(["Whatsapp enviado" => [$cod_ref, $cliente, $phone, $id_ticket, $name_mer, $result_mer]]);
            } catch (\Throwable $e) {

                report($e);
            }
        }
        })->everyTenMinutes();;
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
