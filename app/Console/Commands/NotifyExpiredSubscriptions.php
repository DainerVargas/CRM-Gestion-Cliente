<?php

namespace App\Console\Commands;

use App\Models\GasSale;
use App\Mail\SubscriptionExpiredNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifyExpiredSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-expired-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia notificaciones por correo a los clientes cuyas cuentas han vencido y los marca como inactivos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->format('Y-m-d');
        
        $this->info("Buscando suscripciones vencidas hasta el: $today");

        // Buscamos ventas vencidas que aún no han sido notificadas
        $expiringSales = GasSale::with(['client', 'tvAccount'])
            ->whereDate('expiry_date', '<=', $today)
            ->where('expiration_notified', false)
            ->get();

        if ($expiringSales->isEmpty()) {
            $this->info("No hay nuevas suscripciones vencidas por notificar.");
            return;
        }

        foreach ($expiringSales as $sale) {
            if ($sale->client) {
                // Cambiar estado del cliente a inactivo
                $sale->client->update(['status' => 'inactive']);
                $this->info("Cliente {$sale->client->name} marcado como inactivo.");

                if ($sale->client->email) {
                    try {
                        Mail::to($sale->client->email)
                            ->cc(config('mail.from.address'))
                            ->send(new SubscriptionExpiredNotification(
                                $sale->client->name,
                                $sale->tvAccount->name ?? 'Cuenta de TV',
                                $sale->expiry_date->format('d/m/Y')
                            ));
                        
                        // Marcar como notificado
                        $sale->update(['expiration_notified' => true]);
                        
                        $this->info("Correo enviado y registro actualizado para: {$sale->client->email}");
                    } catch (\Exception $e) {
                        $this->error("Error enviando correo a {$sale->client->email}: " . $e->getMessage());
                    }
                } else {
                    // Si no tiene correo, igual lo marcamos como "notificado" (procesado) para no reintentar
                    $sale->update(['expiration_notified' => true]);
                    $this->warn("El cliente {$sale->client->name} no tiene correo, pero se marcó como procesado.");
                }
            }
        }

        $this->info("Proceso completado.");
    }
}
