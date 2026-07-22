<?php

namespace App\Livewire\Services;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Service;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showCreateModal = false;
    public $showEditModal = false;

    public $serviceId;
    public $name;
    public $description;
    public $price;
    public $is_active = true;

    protected $rules = [
        'name' => 'required|min:3',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'is_active' => 'boolean',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->reset(['name', 'description', 'price', 'is_active', 'serviceId']);
        $this->is_active = true;
        $this->showCreateModal = true;
    }

    public function save()
    {
        $this->validate();

        Service::create([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'is_active' => $this->is_active,
        ]);

        $this->showCreateModal = false;
        session()->flash('message', 'Servicio creado exitosamente.');
    }

    public function editService($id)
    {
        $this->resetValidation();
        $service = Service::findOrFail($id);
        
        $this->serviceId = $service->id;
        $this->name = $service->name;
        $this->description = $service->description;
        $this->price = $service->price;
        $this->is_active = $service->is_active;

        $this->showEditModal = true;
    }

    public function updateService()
    {
        $this->validate();

        $service = Service::findOrFail($this->serviceId);
        $service->update([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'is_active' => $this->is_active,
        ]);

        $this->showEditModal = false;
        session()->flash('message', 'Servicio actualizado exitosamente.');
    }

    public function delete($id)
    {
        Service::findOrFail($id)->delete();
        session()->flash('message', 'Servicio eliminado exitosamente.');
    }

    public function toggleActive($id)
    {
        $service = Service::findOrFail($id);
        $service->update([
            'is_active' => !$service->is_active
        ]);
        session()->flash('message', 'Estado del servicio actualizado.');
    }

    public function render()
    {
        $services = Service::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.services.index', [
            'services' => $services
        ]);
    }
}
