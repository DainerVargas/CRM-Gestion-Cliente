<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

    #[Signature('app:send-billing-alerts')]
    #[Description('Envía alertas de cobro para los clientes cuya fecha de próximo cobro es hoy')]
    class SendBillingAlerts extends Command
    {
        /**
         * Execute the console command.
         */
        public function handle()
        {
            $clients = \App\Models\Client::with('user')
                ->whereDate('next_billing_date', \Carbon\Carbon::today())
                ->get();
    
            $count = 0;
            foreach ($clients as $client) {
                if ($client->user && $client->user->email) {
                    \Illuminate\Support\Facades\Mail::to($client->user->email)
                        ->send(new \App\Mail\BillingAlertMail($client));
                    $count++;
                }
            }
    
            $this->info("Se enviaron $count alertas de cobro.");
        }
    }
