<?php

namespace App\Livewire\Calls;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Call;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $type = '';
    public $result = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'result' => ['except' => ''],
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingType() { $this->resetPage(); }
    public function updatingResult() { $this->resetPage(); }

    public function render()
    {
        $isSuperAdmin = auth()->user()->isSuperAdmin();

        $calls = Call::query()
            ->with('client')
            ->whereHas('client', function($q) {
                if (!auth()->user()->isSuperAdmin()) {
                    $q->where('user_id', auth()->id());
                }
                
                if ($this->search) {
                    $q->where(function($sq) {
                        $sq->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('company', 'like', '%' . $this->search . '%');
                    });
                }
            })
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->when($this->result, fn($q) => $q->where('result', $this->result))
            ->latest('called_at')
            ->paginate(15);

        return view('livewire.calls.index', [
            'calls' => $calls
        ])->layout('layouts.app');
    }
}
