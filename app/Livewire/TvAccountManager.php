<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TvAccount;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class TvAccountManager extends Component
{
    public $name = '';
    public $password = '';
    public $type = 'Premium';
    
    public $showCreateModal = false;
    public $showPassword = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'password' => 'required|string|max:255',
        'type' => 'required|string',
    ];

    public function createAccount()
    {
        $this->validate();

        TvAccount::create([
            'name' => $this->name,
            'password' => $this->password,
            'type' => $this->type,
            'user_id' => Auth::id(),
        ]);

        $this->reset(['name', 'password', 'type', 'showCreateModal']);
        $this->dispatch('swal:success', ['message' => 'Cuenta TV creada correctamente']);
    }

    public function togglePassword($id)
    {
        $this->showPassword[$id] = !($this->showPassword[$id] ?? false);
    }

    public function deleteAccount($id)
    {
        TvAccount::destroy($id);
    }

    public function render()
    {
        return view('livewire.tv-account-manager', [
            'accounts' => TvAccount::where('user_id', Auth::id())->latest()->get(),
            'clients' => Client::where('user_id', Auth::id())->get()
        ])->layout('layouts.app');
    }
}
