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
        // Find calls where next_call_at is set, and EITHER:
        // 1. next_call_at is within 2 hours AND notified_2h is false
        // 2. next_call_at is within 5 minutes AND notified_5m is false
        $calls = Call::with(['client', 'user.parent'])
            ->whereNotNull('next_call_at')
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('next_call_at', '<=', now()->addHours(2))
                      ->where('notified_2h', false);
                })->orWhere(function ($q) {
                    $q->where('next_call_at', '<=', now()->addMinutes(5))
                      ->where('notified_5m', false);
                });
            })
            ->get();

        foreach ($calls as $call) {
            $freshCall = Call::find($call->id);
            if (!$freshCall) continue;

            $now = now();
            $nextCallAt = $freshCall->next_call_at;

            $send2h = !$freshCall->notified_2h && $nextCallAt->lte($now->copy()->addHours(2));
            $send5m = !$freshCall->notified_5m && $nextCallAt->lte($now->copy()->addMinutes(5));

            if (!$send2h && !$send5m) {
                continue;
            }

            try {
                $assistant = $freshCall->user;
                if (!$assistant || !$assistant->email) continue;

                $emails = [$assistant->email];
                if ($assistant->parent && $assistant->parent->email) {
                    $emails[] = $assistant->parent->email;
                }
                $emails = array_unique($emails);

                if ($send2h) {
                    // Update state before sending to avoid race condition/duplication
                    $freshCall->update(['notified_2h' => true]);
                    
                    Mail::to($emails)->send(new NextCallReminder($freshCall, '2 hours'));
                    Log::info("2-hour reminder sent for call ID: {$freshCall->id} to: " . implode(', ', $emails));
                }

                // Re-evaluate 5m send condition in case the state was updated during the 2h loop iteration
                $freshCall = $freshCall->fresh();
                $send5m = !$freshCall->notified_5m && $nextCallAt->lte(now()->addMinutes(5));

                if ($send5m) {
                    // Update state before sending to avoid race condition/duplication
                    $freshCall->update([
                        'notified_5m' => true,
                        'notified' => true,
                    ]);
                    
                    Mail::to($emails)->send(new NextCallReminder($freshCall, '5 minutes'));
                    Log::info("5-minute reminder sent for call ID: {$freshCall->id} to: " . implode(', ', $emails));
                }

            } catch (\Exception $e) {
                Log::error("Failed to send notification for call ID: {$freshCall->id}. Error: " . $e->getMessage());
            }
        }
    }

    public function render()
    {
        return view('livewire.notification-checker');
    }
}
