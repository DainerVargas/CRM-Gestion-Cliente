<?php

namespace App\Livewire\WhatsappTemplates;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\WhatsappTemplate;

class Index extends Component
{
    use WithPagination;

    public $title, $description, $templateId;
    public $showCreateModal = false, $showEditModal = false;

    protected $rules = [
        'title' => 'required|min:3',
        'description' => 'required|min:5',
    ];

    public function openCreateModal()
    {
        $this->reset(['title', 'description', 'templateId', 'showEditModal']);
        $this->showCreateModal = true;
    }

    public function mount()
    {
        // No longer aborting 403, assistants can view but not manage (Blade handles buttons)
    }

    public function save()
    {
        if (auth()->user()->isAssistant()) {
            abort(403, 'No tienes permiso para crear plantillas.');
        }

        $this->validate();

        WhatsappTemplate::create([
            'title' => $this->title,
            'description' => $this->description,
            'user_id' => auth()->id(),
        ]);

        $this->showCreateModal = false;
        session()->flash('message', 'Plantilla creada correctamente.');
    }

    public function editTemplate($id)
    {
        if (auth()->user()->isAssistant()) {
            abort(403);
        }

        $template = WhatsappTemplate::where('user_id', auth()->id())->findOrFail($id);
        $this->templateId = $template->id;
        $this->title = $template->title;
        $this->description = $template->description;
        $this->showEditModal = true;
    }

    public function updateTemplate()
    {
        if (auth()->user()->isAssistant()) {
            abort(403);
        }

        $this->validate();

        $template = WhatsappTemplate::where('user_id', auth()->id())->findOrFail($this->templateId);
        $template->update([
            'title' => $this->title,
            'description' => $this->description,
        ]);

        $this->showEditModal = false;
        session()->flash('message', 'Plantilla actualizada correctamente.');
    }

    public function delete($id)
    {
        if (auth()->user()->isAssistant()) {
            abort(403);
        }

        WhatsappTemplate::where('user_id', auth()->id())->findOrFail($id)->delete();
        session()->flash('message', 'Plantilla eliminada.');
    }

    public function render()
    {
        return view('livewire.whatsapp-templates.index', [
            'templates' => WhatsappTemplate::where('user_id', auth()->id())->latest()->paginate(10)
        ])->layout('layouts.app');
    }
}
