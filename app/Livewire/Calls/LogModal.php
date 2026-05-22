<?php

namespace App\Livewire\Calls;

use Livewire\Component;
use App\Models\Client;
use App\Models\Call;
use Livewire\Attributes\On;

class LogModal extends Component
{
    public $clientId;
    public $type = 'outbound';
    public $duration = '';
    public $observations = '';
    public $result = 'pending';
    public $called_at;
    public $next_call_at;
    public $hasNextCall = false;
    public $showModal = false;
    public $recordingId = null;

    #[On('open-call-modal')]
    public function openModal()
    {
        $this->resetValidation();
        $this->showModal = true;
        $this->called_at = now()->format('Y-m-d\TH:i');
        $this->next_call_at = now()->addDays(1)->format('Y-m-d\TH:i');
        $this->hasNextCall = false;
        $this->duration = '';
        $this->observations = '';
        $this->result = 'pending';
    }

    public function mount($clientId)
    {
        $this->clientId = $clientId;
        $this->called_at = now()->format('Y-m-d\TH:i');
    }

    public function save()
    {
        $rules = [
            'type' => 'required|in:inbound,outbound',
            'duration' => 'nullable|numeric',
            'result' => 'required',
            'called_at' => 'required',
        ];

        if ($this->hasNextCall) {
            $rules['next_call_at'] = 'required';
        }

        $this->validate($rules);

        try {
            $call = Call::create([
                'client_id' => $this->clientId,
                'user_id' => auth()->id() ?? 1,
                'type' => $this->type,
                'duration' => $this->duration ? (int)$this->duration : 0,
                'observations' => $this->observations,
                'result' => $this->result,
                'called_at' => $this->called_at,
                'next_call_at' => $this->hasNextCall ? $this->next_call_at : null,
            ]);

            if ($this->recordingId) {
                \App\Models\CallRecording::where('id', $this->recordingId)->update([
                    'call_id' => $call->id
                ]);
            }

            // Update Client Status based on result
            $client = Client::find($this->clientId);
            if ($client) {
                $statusMapping = [
                    'interested' => 'prospect',
                    'not_interested' => 'not_interested',
                    'closed' => 'active',
                    'pending' => 'prospect'
                ];

                $newStatus = $statusMapping[$this->result] ?? $client->status;
                
                if ($client->status !== $newStatus) {
                    $client->status = $newStatus;
                    $client->status_changed_by = auth()->user()->name ?? 'Sistema';
                    $client->save();
                }
            }

            $this->dispatch('call-logged');
            $this->reset(['duration', 'observations', 'result', 'showModal', 'hasNextCall', 'next_call_at', 'recordingId']);
            session()->flash('message', 'Llamada registrada correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.calls.log-modal');
    }
}
