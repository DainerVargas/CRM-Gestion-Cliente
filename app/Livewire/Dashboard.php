<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Client;
use App\Models\Call;

class Dashboard extends Component
{
    public function render()
    {
        $isSuperAdmin = auth()->user()->isSuperAdmin();

        $clientQuery = Client::query()->whereIn('user_id', auth()->user()->getTeamUserIds());
        $callQuery = Call::query()->whereHas('client', fn($cq) => $cq->whereIn('user_id', auth()->user()->getTeamUserIds()));

        return view('livewire.dashboard', [
            'totalClients' => (clone $clientQuery)->count(),
            'activeClients' => (clone $clientQuery)->where('status', 'active')->count(),
            'totalCalls' => (clone $callQuery)->count(),
            'recentCalls' => (clone $callQuery)->with('client')->latest()->take(5)->get(),
        ])->layout('layouts.app');
    }
}
