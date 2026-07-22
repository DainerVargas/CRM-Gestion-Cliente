<?php

namespace App\Livewire\Admin\Sales;

use App\Models\SalesSession;
use App\Models\GasSale;
use App\Models\Expense;
use App\Models\CylinderInventory;
use App\Models\Client;
use App\Models\SalePayment;
use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class SettlementManager extends Component
{
    use WithPagination;

    public $activeSession;
    public $selectedSession;
    public $editingSaleId;
    public $editingExpenseId;
    public $starting_cash = 250.46;
    public $date;
    public $filterStartDate;
    public $filterEndDate;
    public $view = 'active'; // 'active', 'history', 'details', 'debts'
    public $searchClient = ''; // Search for history
    
    // Form fields for Sale
    public $client_id;
    public $client_name_manual;
    public $cart = [];
    public $selected_service_id = '';
    public $selected_quantity = 1;
    public $selected_price = null;
    public $amount;
    public $paid_amount;
    public $payment_method = 'cash';
    public $status = 'paid';
    public $notes;
    public $payment_increments = []; // For adding payments to existing debt, indexed by sale ID

    // Form fields for Expense
    public $expense_description;
    public $expense_amount;
    public $expense_category = 'other';

    // Summary data
    public $totalSales = 0;
    public $totalExpenses = 0;
    public $totalCollections = 0;
    public $currentBalance = 0;

    protected $rules = [
        'starting_cash' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->date = date('Y-m-d');
        $this->loadActiveSession();
    }

    public function updatedSelectedServiceId($value)
    {
        if ($value) {
            $service = Service::find($value);
            if ($service) {
                $this->selected_price = $service->price;
            }
        } else {
            $this->selected_price = null;
        }
    }

    public function addToCart()
    {
        $this->validate([
            'selected_service_id' => 'required|exists:services,id',
            'selected_quantity' => 'required|integer|min:1',
            'selected_price' => 'required|numeric|min:0',
        ]);

        $service = Service::find($this->selected_service_id);
        
        $this->cart[] = [
            'service_id' => $service->id,
            'name' => $service->name,
            'quantity' => $this->selected_quantity,
            'price' => $this->selected_price,
            'subtotal' => $this->selected_price * $this->selected_quantity,
        ];

        $this->reset(['selected_service_id', 'selected_price']);
        $this->selected_quantity = 1;
        $this->calculateCartTotal();
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
        $this->calculateCartTotal();
    }

    public function calculateCartTotal()
    {
        $this->amount = collect($this->cart)->sum('subtotal');
    }

    public function loadActiveSession()
    {
        $this->activeSession = SalesSession::where('status', 'open')
            ->whereIn('user_id', Auth::user()->getTeamUserIds())
            ->latest()
            ->first();
            
        if ($this->activeSession) {
            $this->calculateTotals();
        }
    }

    public function openSession()
    {
        $this->validate();

        try {
            $this->activeSession = SalesSession::create([
                'date' => $this->date,
                'start_time' => date('H:i:s'),
                'starting_cash' => $this->starting_cash,
                'status' => 'open',
                'user_id' => Auth::id(),
            ]);

            // Initialize Inventory based on previous session or defaults
            $this->initializeInventory();
            
            // Reload the session from the database to ensure it has all default attributes and mimics a page refresh perfectly
            $this->loadActiveSession();
            
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Jornada abierta correctamente']);
        } catch (\Throwable $e) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Error: ' . $e->getMessage() . ' at line ' . $e->getLine() . ' in ' . basename($e->getFile())]);
        }
    }

    public function initializeInventory()
    {
        $types = ['k10', 's45', 's10'];
        foreach ($types as $type) {
            CylinderInventory::create([
                'sales_session_id' => $this->activeSession->id,
                'cylinder_type' => $type,
                'initial_full' => 0,
                'initial_empty' => 0,
            ]);
        }
    }

    public function addSale()
    {
        $this->validate([
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required',
            'cart' => 'required|array|min:1',
        ], [
            'cart.required' => 'Debe agregar al menos un servicio al carrito.',
            'cart.min' => 'Debe agregar al menos un servicio al carrito.',
        ]);

        if ($this->editingSaleId) {
            $sale = GasSale::find($this->editingSaleId);
            
            $sale->update([
                'client_id' => $this->client_id,
                'amount' => $this->amount,
                'paid_amount' => $this->payment_method === 'credit' ? ($this->paid_amount ?? 0) : $this->amount,
                'payment_method' => $this->payment_method,
                'status' => $this->payment_method === 'credit' ? ($this->paid_amount >= $this->amount ? 'paid' : 'pending') : 'paid',
                'notes' => $this->notes,
            ]);

            $sale->items()->delete();
            foreach ($this->cart as $item) {
                $sale->items()->create([
                    'service_id' => $item['service_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            $this->editingSaleId = null;
            $message = 'Venta actualizada';
        } else {
            $isDemo = $this->payment_method === 'demo';
            $saleAmount = $isDemo ? 0 : $this->amount;
            $salePaid = $isDemo ? 0 : ($this->payment_method === 'credit' ? ($this->paid_amount ?? 0) : $saleAmount);

            $sale = GasSale::create([
                'sales_session_id' => $this->activeSession->id,
                'client_id' => $this->client_id,
                'user_id' => Auth::id(),
                'expiry_date' => now()->addMonth(),
                'quantity' => collect($this->cart)->sum('quantity'),
                'amount' => $saleAmount,
                'paid_amount' => $salePaid,
                'payment_method' => $this->payment_method,
                'status' => $isDemo ? 'paid' : ($this->payment_method === 'credit' ? ($salePaid >= $saleAmount ? 'paid' : 'pending') : 'paid'),
                'notes' => $this->notes,
            ]);

            foreach ($this->cart as $item) {
                $sale->items()->create([
                    'service_id' => $item['service_id'],
                    'quantity' => $item['quantity'],
                    'price' => $isDemo ? 0 : $item['price'],
                    'subtotal' => $isDemo ? 0 : $item['subtotal'],
                ]);
            }

            // Record the initial payment (skip for demo)
            if (!$isDemo && $salePaid > 0) {
                \App\Models\SalePayment::create([
                    'gas_sale_id' => $sale->id,
                    'sales_session_id' => $this->activeSession->id,
                    'amount' => $salePaid,
                    'payment_method' => $this->payment_method === 'credit' ? 'cash' : $this->payment_method,
                    'notes' => 'Pago inicial',
                ]);
            }
            $message = 'Venta registrada';
        }

        $this->reset(['client_id', 'cart', 'amount', 'paid_amount', 'notes', 'status', 'payment_method']);
        $this->payment_method = 'cash'; // Reset to default
        $this->status = 'paid'; // Reset to default
        $this->calculateTotals();
        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
    }

    public function editSale($id)
    {
        $sale = GasSale::with('items.service')->find($id);
        $this->editingSaleId = $id;
        $this->client_id = $sale->client_id;
        $this->client_name_manual = $sale->client_name_manual;
        $this->amount = $sale->amount;
        $this->paid_amount = $sale->paid_amount;
        $this->payment_method = $sale->payment_method;
        $this->status = $sale->status;
        $this->notes = $sale->notes;
        
        $this->cart = [];
        foreach ($sale->items as $item) {
            $this->cart[] = [
                'service_id' => $item->service_id,
                'name' => $item->service->name ?? 'Servicio Eliminado',
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->subtotal,
            ];
        }
    }

    public function deleteSale($id)
    {
        GasSale::destroy($id);
        $this->calculateTotals();
        $this->dispatch('notify', ['type' => 'warning', 'message' => 'Venta eliminada']);
    }

    public function addExpense()
    {
        $this->validate([
            'expense_description' => 'required|string',
            'expense_amount' => 'required|numeric|min:0',
            'expense_category' => 'required',
        ]);

        if ($this->editingExpenseId) {
            $expense = Expense::find($this->editingExpenseId);
            $expense->update([
                'description' => $this->expense_description,
                'amount' => $this->expense_amount,
                'category' => $this->expense_category,
            ]);
            $this->editingExpenseId = null;
            $message = 'Gasto actualizado';
        } else {
            Expense::create([
                'sales_session_id' => $this->activeSession->id,
                'description' => $this->expense_description,
                'amount' => $this->expense_amount,
                'category' => $this->expense_category,
            ]);
            $message = 'Gasto registrado';
        }

        $this->reset(['expense_description', 'expense_amount', 'expense_category']);
        $this->expense_category = 'other'; // Reset to default
        $this->calculateTotals();
        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
    }

    public function editExpense($id)
    {
        $expense = Expense::find($id);
        $this->editingExpenseId = $id;
        $this->expense_description = $expense->description;
        $this->expense_amount = $expense->amount;
        $this->expense_category = $expense->category;
    }

    public function deleteExpense($id)
    {
        Expense::destroy($id);
        $this->calculateTotals();
        $this->dispatch('notify', ['type' => 'warning', 'message' => 'Gasto eliminado']);
    }

    public function calculateTotals()
    {
        if (!$this->activeSession) return;

        // Total sales in this session = all payments received during this session (initial or debt payments)
        $this->totalSales = \App\Models\SalePayment::where('sales_session_id', $this->activeSession->id)->sum('amount');
        $this->totalExpenses = $this->activeSession->expenses()->sum('amount');
        
        // Total collections (pending debt of sales created in THIS session)
        $this->totalCollections = $this->activeSession->gasSales()->sum('amount') - $this->activeSession->gasSales()->sum('paid_amount');
        
        $this->currentBalance = $this->activeSession->starting_cash + $this->totalSales - $this->totalExpenses;
    }

    public function closeSession()
    {
        $this->activeSession->update([
            'status' => 'closed',
            'end_time' => date('H:i:s'),
            'closing_cash' => $this->currentBalance,
        ]);

        $this->activeSession = null;
        $this->dispatch('notify', ['type' => 'info', 'message' => 'Jornada cerrada']);
    }

    public function markAsPaid($saleId)
    {
        if (!$this->activeSession) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'No hay una jornada activa. Debe abrir una jornada primero para registrar cobranzas.']);
            return;
        }

        $sale = GasSale::find($saleId);
        if ($sale) {
            $remaining = $sale->amount - $sale->paid_amount;
            if ($remaining > 0) {
                \App\Models\SalePayment::create([
                    'gas_sale_id' => $sale->id,
                    'sales_session_id' => $this->activeSession->id,
                    'amount' => $remaining,
                    'payment_method' => 'cash',
                    'notes' => 'Pago total (marcado como pagado)',
                ]);
            }

            $sale->update([
                'status' => 'paid',
                'paid_amount' => $sale->amount
            ]);
            $this->calculateTotals();
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Venta marcada como pagada']);
        }
    }

    public function addPayment($saleId)
    {
        if (!$this->activeSession) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'No hay una jornada activa. Debe abrir una jornada primero para registrar cobranzas.']);
            return;
        }

        $amount = $this->payment_increments[$saleId] ?? null;

        if (!$amount || !is_numeric($amount) || $amount <= 0) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Ingrese un monto válido']);
            return;
        }

        $sale = GasSale::find($saleId);
        if ($sale) {
            $newPaidAmount = $sale->paid_amount + $amount;
            
            if ($newPaidAmount > $sale->amount) {
                $this->dispatch('notify', ['type' => 'error', 'message' => 'El abono supera el monto total']);
                return;
            }

            // Create payment record
            \App\Models\SalePayment::create([
                'gas_sale_id' => $sale->id,
                'sales_session_id' => $this->activeSession->id,
                'amount' => $amount,
                'payment_method' => 'cash',
                'notes' => 'Abono a deuda',
            ]);

            $sale->update([
                'paid_amount' => $newPaidAmount,
                'status' => $newPaidAmount >= $sale->amount ? 'paid' : 'pending'
            ]);

            $this->payment_increments[$saleId] = null;
            $this->calculateTotals();
            
            if ($this->selectedSession) {
                $this->selectedSession->refresh();
            }
            
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Abono registrado correctamente']);
        }
    }

    public function updateInventory($inventoryId, $full, $empty)
    {
        $item = CylinderInventory::find($inventoryId);
        if ($item) {
            $item->update([
                'final_full' => $full,
                'final_empty' => $empty,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Inventario actualizado']);
        }
    }

    public function viewDetails($sessionId)
    {
        $this->selectedSession = SalesSession::with(['gasSales', 'expenses', 'inventories'])->find($sessionId);
        $this->view = 'details';
    }

    public function backToHistory()
    {
        $this->selectedSession = null;
        $this->view = 'history';
    }

    public function render()
    {
        $historyQuery = SalesSession::whereIn('user_id', Auth::user()->getTeamUserIds());

        if ($this->filterStartDate) {
            $historyQuery->whereDate('date', '>=', $this->filterStartDate);
        }
        if ($this->filterEndDate) {
            $historyQuery->whereDate('date', '<=', $this->filterEndDate);
        }

        if ($this->searchClient) {
            $historyQuery->whereHas('gasSales', function ($query) {
                $query->where('client_name_manual', 'like', '%' . $this->searchClient . '%')
                      ->orWhereHas('client', function ($q) {
                          $q->where('name', 'like', '%' . $this->searchClient . '%');
                      });
            });
        }

        // Calculate totals for the filtered range
        $sessionIds = (clone $historyQuery)->pluck('id');
        
        $salesQuery = GasSale::whereIn('sales_session_id', $sessionIds);
        $paymentsQuery = SalePayment::whereIn('sales_session_id', $sessionIds);
        $expensesQuery = Expense::whereIn('sales_session_id', $sessionIds);

        if ($this->searchClient) {
            $salesQuery->where(function($q) {
                $q->where('client_name_manual', 'like', '%' . $this->searchClient . '%')
                  ->orWhereHas('client', function ($q2) {
                      $q2->where('name', 'like', '%' . $this->searchClient . '%');
                  });
            });
            $paymentsQuery->whereHas('sale', function($q) {
                $q->where('client_name_manual', 'like', '%' . $this->searchClient . '%')
                  ->orWhereHas('client', function ($q2) {
                      $q2->where('name', 'like', '%' . $this->searchClient . '%');
                  });
            });
        }

        $totalPeriodSales = $salesQuery->sum('amount');
        $totalPeriodCollected = $paymentsQuery->sum('amount');
        $totalPeriodExpenses = $expensesQuery->sum('amount');

        return view('livewire.admin.sales.settlement-manager', [
            'sales' => $this->activeSession ? $this->activeSession->gasSales()->latest()->get() : [],
            'expenses' => $this->activeSession ? $this->activeSession->expenses()->latest()->get() : [],
            'clients' => Client::whereIn('user_id', Auth::user()->getTeamUserIds())->get(),
            'services' => Service::where('is_active', true)->orderBy('name')->get(),
            'history' => $historyQuery->latest()->paginate(10),
            'totalPeriodSales' => $totalPeriodSales,
            'totalPeriodCollected' => $totalPeriodCollected,
            'totalPeriodExpenses' => $totalPeriodExpenses,
            'pendingDebts' => GasSale::whereIn('user_id', Auth::user()->getTeamUserIds())
                ->where('status', 'pending')
                ->when($this->searchClient, function($query) {
                    $query->where('client_name_manual', 'like', '%' . $this->searchClient . '%')
                          ->orWhereHas('client', function ($q) {
                              $q->where('name', 'like', '%' . $this->searchClient . '%');
                          });
                })->latest()->get(),
        ])->layout('layouts.app');
    }
}
