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

        $clientQuery = Client::query()->when(!$isSuperAdmin, fn($q) => $q->where('user_id', auth()->id()));
        $callQuery = Call::query()->when(!$isSuperAdmin, fn($q) => $q->whereHas('client', fn($cq) => $cq->where('user_id', auth()->id())));

        return view('livewire.dashboard', [
            'totalClients' => (clone $clientQuery)->count(),
            'activeClients' => (clone $clientQuery)->where('status', 'active')->count(),
            'totalCalls' => (clone $callQuery)->count(),
            'recentCalls' => (clone $callQuery)->with('client')->latest()->take(5)->get(),
        ])->layout('layouts.app');
    }
}
