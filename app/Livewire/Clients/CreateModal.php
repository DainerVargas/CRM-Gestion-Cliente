<?php

namespace App\Livewire\Clients;

use Livewire\Component;
use App\Models\Client;
use Livewire\Attributes\On;

class CreateModal extends Component
{
    public $name;
    public $email;
    public $phone;
    public $company;
    public $rubro;
    public $status = 'prospect';
    public $user_id; // Added property
    public $showModal = false;
    public $hasEmail = false;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'nullable|email|unique:clients,email',
        'phone' => 'required',
        'rubro' => 'nullable|string',
        'status' => 'required|in:active,inactive,prospect,libre,not_interested',
        'user_id' => 'required|exists:users,id',
    ];

    #[On('open-create-modal')]
    public function openModal()
    {
        $this->reset(['name', 'email', 'phone', 'company', 'rubro', 'status', 'hasEmail']);
        $this->user_id = auth()->id(); // Initialize with current user
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $this->phone = preg_replace('/[^0-9]/', '', $this->phone);

        Client::create([
            'name' => $this->name,
            'email' => $this->hasEmail ? $this->email : null,
            'phone' => $this->phone,
            'company' => $this->company,
            'rubro' => $this->rubro,
            'status' => $this->status,
            'user_id' => $this->user_id, // Use selected user_id
        ]);

        $this->dispatch('client-created');
        $this->showModal = false;
        session()->flash('message', 'Cliente creado con éxito.');
    }

    public function render()
    {
        $currentUser = auth()->user();
        $agents = [];

        if ($currentUser->isSuperAdmin()) {
            // Admin can assign to anyone
            $agents = \App\Models\User::all();
        } else {
            // Others can only assign to self
            $agents = \App\Models\User::where('id', $currentUser->id)->get();
        }

        return view('livewire.clients.create-modal', [
            'agents' => $agents
        ]);
    }
}
