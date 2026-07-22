<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SalesSession;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that a super admin can delete a manager admin, and all associated
     * assistants and sales sessions are deleted cleanly in cascade.
     */
    public function test_super_admin_can_delete_manager_and_cascade_records(): void
    {
        // 1. Create a Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin Test',
            'email' => 'superadmin_test@example.com',
            'password' => bcrypt('password123'),
            'role' => 'super_admin',
        ]);

        // 2. Create a Manager
        $manager = User::create([
            'name' => 'Manager Admin Test',
            'email' => 'manager_test@example.com',
            'password' => bcrypt('password123'),
            'role' => 'manager',
            'parent_id' => $superAdmin->id,
        ]);

        // 3. Create an Assistant under that Manager
        $assistant = User::create([
            'name' => 'Assistant Test',
            'email' => 'assistant_test@example.com',
            'password' => bcrypt('password123'),
            'role' => 'assistant',
            'parent_id' => $manager->id,
        ]);

        // 4. Create Sales Sessions for both Manager and Assistant
        $managerSession = SalesSession::create([
            'date' => now()->toDateString(),
            'start_time' => now()->toTimeString(),
            'starting_cash' => 100.00,
            'status' => 'open',
            'user_id' => $manager->id,
        ]);

        $assistantSession = SalesSession::create([
            'date' => now()->toDateString(),
            'start_time' => now()->toTimeString(),
            'starting_cash' => 50.00,
            'status' => 'open',
            'user_id' => $assistant->id,
        ]);

        // Assert they exist in database before deletion
        $this->assertDatabaseHas('users', ['id' => $manager->id]);
        $this->assertDatabaseHas('users', ['id' => $assistant->id]);
        $this->assertDatabaseHas('sales_sessions', ['id' => $managerSession->id]);
        $this->assertDatabaseHas('sales_sessions', ['id' => $assistantSession->id]);

        // 5. Authenticate as Super Admin and call delete on the Manager
        Livewire::actingAs($superAdmin)
            ->test(\App\Livewire\Users\Index::class)
            ->call('delete', $manager->id)
            ->assertHasNoErrors();

        // 6. Assert everything was cascade deleted cleanly
        $this->assertDatabaseMissing('users', ['id' => $manager->id]);
        $this->assertDatabaseMissing('users', ['id' => $assistant->id]);
        $this->assertDatabaseMissing('sales_sessions', ['id' => $managerSession->id]);
        $this->assertDatabaseMissing('sales_sessions', ['id' => $assistantSession->id]);
    }
}
