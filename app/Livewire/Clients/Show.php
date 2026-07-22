<?php

namespace App\Livewire\Clients;

use Livewire\Component;
use App\Models\Client;
use App\Models\Call;

use Livewire\Attributes\On;

class Show extends Component
{
    public Client $client;
    public $showEditModal = false;
    public $name, $email, $phone, $company, $rubro, $status, $user_id, $next_billing_date;
    public $payment_increment; // For debt payments

    public $index_page, $index_search, $index_status;
    public $whatsappTemplates;

    protected $queryString = [
        'index_page' => ['as' => 'page', 'except' => 1],
        'index_search' => ['as' => 'search', 'except' => ''],
        'index_status' => ['as' => 'status', 'except' => ''],
    ];

    public function mount($id)
    {
        $isSuperAdmin = auth()->user()->isSuperAdmin();

        $this->client = Client::with(['calls', 'user', 'gasSales'])
            ->whereIn('user_id', auth()->user()->getTeamUserIds())
            ->findOrFail($id);

        $this->name = $this->client->name;
        $this->email = $this->client->email;
        $this->phone = $this->client->phone;
        $this->company = $this->client->company;
        $this->rubro = $this->client->rubro;
        $this->status = $this->client->status;
        $this->user_id = $this->client->user_id;
        $this->next_billing_date = $this->client->next_billing_date ? $this->client->next_billing_date->format('Y-m-d') : null;

        $ownerId = auth()->user()->isAssistant() ? (auth()->user()->parent_id ?? auth()->id()) : auth()->id();
        $this->whatsappTemplates = \App\Models\WhatsappTemplate::where('user_id', $ownerId)->get();
    }

    public function sendWhatsapp($templateId)
    {
        $ownerId = auth()->user()->isAssistant() ? (auth()->user()->parent_id ?? auth()->id()) : auth()->id();
        $template = \App\Models\WhatsappTemplate::where('user_id', $ownerId)->find($templateId);
        if (!$template) {
            session()->flash('error', 'Plantilla no encontrada.');
            return;
        }

        $phone = $this->client->phone;
        // Clean phone number (leave only digits)
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Adapt to Peru (+51)
        // Peruvian mobile numbers have 9 digits.
        if (strlen($phone) === 9) {
            $phone = "+51" . $phone;
        }
        if (strlen($phone) === 10) {
            $phone = "+57" . $phone;
        }
        
        $message = urlencode($template->description);
        $url = "https://api.whatsapp.com/send?phone={$phone}&text={$message}";
        
        $this->dispatch('open-whatsapp', url: $url);
    }

    public function openEditModal()
    {
        $this->user_id = $this->client->user_id;

        $this->showEditModal = true;
    }

    public function updateClient()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'nullable|email|unique:clients,email,' . $this->client->id,
            'phone' => 'required',
            'rubro' => 'nullable|string',
            'status' => 'required|in:active,inactive,prospect,libre,not_interested',
            'user_id' => 'required|exists:users,id',
            'next_billing_date' => 'nullable|date',
        ]);

        if ($this->client->status !== $this->status) {
            $this->client->status_changed_by = auth()->user()->name ?? 'Sistema';
        }

        $this->phone = preg_replace('/[^0-9]/', '', $this->phone);

        $this->client->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'rubro' => $this->rubro,
            'status' => $this->status,
            'status_changed_by' => $this->client->status_changed_by,
            'user_id' => $this->user_id,
            'next_billing_date' => $this->next_billing_date,
        ]);

        $this->client->refresh();

        $this->showEditModal = false;
        session()->flash('message', 'Información del cliente actualizada.');
    }

    #[On('call-logged')]
    public function refreshCalls()
    {
        $this->client->load('calls');
    }

    public function markAsPaid($saleId)
    {
        $sale = \App\Models\GasSale::find($saleId);
        if ($sale) {
            $remaining = $sale->amount - $sale->paid_amount;
            
            // Get active session if exists to record the payment
            $activeSession = \App\Models\SalesSession::where('status', 'open')->first();
            
            if ($activeSession && $remaining > 0) {
                \App\Models\SalePayment::create([
                    'gas_sale_id' => $sale->id,
                    'sales_session_id' => $activeSession->id,
                    'amount' => $remaining,
                    'payment_method' => 'cash',
                    'notes' => 'Pago total desde Gestión de Clientes',
                ]);
            }

            $sale->update([
                'status' => 'paid',
                'paid_amount' => $sale->amount
            ]);
            
            $this->client->refresh();
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Venta marcada como pagada']);
        }
    }

    public function addPayment($saleId)
    {
        $this->validate([
            'payment_increment' => 'required|numeric|min:0.01'
        ]);

        $sale = \App\Models\GasSale::find($saleId);
        if ($sale) {
            $newPaidAmount = $sale->paid_amount + $this->payment_increment;
            
            if ($newPaidAmount > $sale->amount) {
                $this->dispatch('notify', ['type' => 'error', 'message' => 'El abono supera el monto total']);
                return;
            }

            $activeSession = \App\Models\SalesSession::where('status', 'open')->first();
            if (!$activeSession) {
                $this->dispatch('notify', ['type' => 'error', 'message' => 'No hay una jornada abierta para registrar el pago']);
                return;
            }

            // Create payment record
            \App\Models\SalePayment::create([
                'gas_sale_id' => $sale->id,
                'sales_session_id' => $activeSession->id,
                'amount' => $this->payment_increment,
                'payment_method' => 'cash',
                'notes' => 'Abono desde Gestión de Clientes',
            ]);

            $sale->update([
                'paid_amount' => $newPaidAmount,
                'status' => $newPaidAmount >= $sale->amount ? 'paid' : 'pending'
            ]);

            $this->payment_increment = null;
            $this->client->refresh();
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Abono registrado correctamente']);
        }
    }

    public function render()
    {
        $currentUser = auth()->user();
        $agents = \App\Models\User::whereIn('id', $currentUser->getTeamUserIds())->get();

        return view('livewire.clients.show', [
            'calls' => $this->client->calls()->with('recording')->latest()->get(),
            'agents' => $agents
        ])->layout('layouts.app');
    }
}
