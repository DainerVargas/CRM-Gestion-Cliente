<?php

namespace App\Livewire\Clients;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Client;

use Livewire\Attributes\On;

use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientsExport;
use App\Imports\ClientsImport;
use App\Models\GasSale;
use Illuminate\Support\Facades\Mail;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $status = '';
    public $importFile;
    public $showImportModal = false;
    public $importResults = [
        'created' => 0,
        'updated' => 0,
        'errors' => 0,
        'details' => []
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    #[On('client-created')]
    public function refreshList()
    {
        // Livewire refreshes automatically on re-render
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        Client::find($id)->delete();
        session()->flash('message', 'Cliente eliminado con éxito.');
    }

    public function export()
    {
        $userIds = auth()->user()->getTeamUserIds();
        return Excel::download(new ClientsExport($userIds), 'clientes.xlsx');
    }

    public function updatedImportFile()
    {
        $this->import();
    }

    public function import()
    {
        $this->validate([
            'importFile' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            $import = new ClientsImport;
            Excel::import($import, $this->importFile);
            
            $this->importResults = [
                'created' => $import->createdCount,
                'updated' => $import->updatedCount,
                'errors' => $import->errorCount,
                'details' => $import->errors
            ];
            
            $this->showImportModal = true;
            session()->flash('message', 'Importación finalizada.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al importar: ' . $e->getMessage());
        }

        $this->importFile = null;
    }


    public function render()
    {
        $clients = Client::query()->with(['user', 'latestCall'])
            ->whereIn('user_id', auth()->user()->getTeamUserIds())
            ->when($this->search, function ($query) {
                $query->where(function($q) {
                    $cleanSearch = preg_replace('/[^0-9]/', '', $this->search);

                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%')
                      ->orWhere('company', 'like', '%' . $this->search . '%')
                      ->orWhere('rubro', 'like', '%' . $this->search . '%');
                    
                    if ($cleanSearch) {
                        $q->orWhere('phone', 'like', '%' . $cleanSearch . '%');
                    }
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.clients.index', [
            'clients' => $clients,
            'showImportModal' => $this->showImportModal,
            'importResults' => $this->importResults,
        ])->layout('layouts.app');
    }

    public function paginationView()
    {
        return 'vendor.pagination.tailwind';
    }
}
