<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Call;
use App\Mail\NextCallReminder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationChecker extends Component
{
    public function checkNotifications()
    {
        // Find calls with next_call_at within 2 hours that haven't been notified
        $calls = Call::with(['client', 'user.parent'])
            ->whereNotNull('next_call_at')
            ->where('notified', false)
            ->where('next_call_at', '<=', now()->addHours(2))
            ->get();

        foreach ($calls as $call) {
            // Recargar para verificar que otro proceso (como otra pestaña abierta) no lo haya notificado ya
            $freshCall = Call::find($call->id);
            if (!$freshCall || $freshCall->notified) continue;

            try {
                $assistant = $call->user;
                if (!$assistant || !$assistant->email) continue;

                $emails = [$assistant->email];
                
                // Si el usuario es asistente o tiene un encargado (parent), incluimos al encargado
                if ($assistant->parent && $assistant->parent->email) {
                    $emails[] = $assistant->parent->email;
                }

                $emails = array_unique($emails);

                // Marcamos como notificado ANTES de enviar para evitar duplicados si el envío es lento
                $call->update(['notified' => true]);
                
                Mail::to($emails)->send(new NextCallReminder($call));
                
                $emailsString = implode(', ', $emails);
                Log::info("Notification sent for call ID: {$call->id} to: {$emailsString}");

            } catch (\Exception $e) {
                Log::error("Failed to send notification for call ID: {$call->id}. Error: " . $e->getMessage());
            }
        }
    }

    public function render()
    {
        return view('livewire.notification-checker');
    }
}
