<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Models\Call;
use App\Mail\NextCallReminder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CallReminderTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that two reminders are sent for each scheduled call:
     * 1. One at 2 hours before the scheduled time (notified_2h = true).
     * 2. Another at 5 minutes before the scheduled time (notified_5m = true, notified = true).
     */
    public function test_send_reminders_at_appropriate_times(): void
    {
        Mail::fake();

        // 1. Create User & Client
        $user = User::create([
            'name' => 'Agent Test',
            'email' => 'agent@example.com',
            'password' => bcrypt('password123'),
            'role' => 'manager',
        ]);

        $client = Client::create([
            'name' => 'Client Test',
            'email' => 'client@example.com',
            'phone' => '123456789',
            'user_id' => $user->id,
        ]);

        // 2. Call 1: Scheduled for 1 hour 50 minutes from now (should trigger 2h reminder, not 5m)
        $call1 = Call::create([
            'client_id' => $client->id,
            'user_id' => $user->id,
            'called_at' => now(),
            'next_call_at' => now()->addMinutes(110),
            'notified' => false,
            'notified_2h' => false,
            'notified_5m' => false,
        ]);

        // 3. Call 2: Scheduled for 4 minutes from now, with 2h already sent (should trigger 5m reminder)
        $call2 = Call::create([
            'client_id' => $client->id,
            'user_id' => $user->id,
            'called_at' => now(),
            'next_call_at' => now()->addMinutes(4),
            'notified' => false,
            'notified_2h' => true,
            'notified_5m' => false,
        ]);

        // 4. Call 3: Scheduled for 3 hours from now (should NOT trigger any reminders)
        $call3 = Call::create([
            'client_id' => $client->id,
            'user_id' => $user->id,
            'called_at' => now(),
            'next_call_at' => now()->addHours(3),
            'notified' => false,
            'notified_2h' => false,
            'notified_5m' => false,
        ]);

        // 5. Run the checkNotifications method
        $component = new \App\Livewire\NotificationChecker();
        $component->checkNotifications();

        // Assertions for Call 1 (2h reminder sent)
        $call1 = $call1->fresh();
        $this->assertTrue($call1->notified_2h);
        $this->assertFalse($call1->notified_5m);
        $this->assertFalse($call1->notified);

        // Assertions for Call 2 (5m reminder sent, full notified set)
        $call2 = $call2->fresh();
        $this->assertTrue($call2->notified_2h);
        $this->assertTrue($call2->notified_5m);
        $this->assertTrue($call2->notified);

        // Assertions for Call 3 (nothing sent)
        $call3 = $call3->fresh();
        $this->assertFalse($call3->notified_2h);
        $this->assertFalse($call3->notified_5m);
        $this->assertFalse($call3->notified);

        // Assert Mails were sent with correct timeframes
        Mail::assertSent(NextCallReminder::class, function ($mail) use ($call1) {
            return $mail->call->id === $call1->id && $mail->timeframe === '2 hours';
        });

        Mail::assertSent(NextCallReminder::class, function ($mail) use ($call2) {
            return $mail->call->id === $call2->id && $mail->timeframe === '5 minutes';
        });

        Mail::assertNotSent(NextCallReminder::class, function ($mail) use ($call3) {
            return $mail->call->id === $call3->id;
        });
    }
}
