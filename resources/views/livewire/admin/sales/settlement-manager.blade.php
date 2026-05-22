<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 uppercase tracking-tight">Gestión de Ventas y Cierre</h1>
            <div class="flex items-center space-x-6 mt-2">
                <button wire:click="$set('view', 'active')" class="text-sm font-black uppercase tracking-widest {{ $view == 'active' ? 'text-[#043d8a] border-b-2 border-[#043d8a]' : 'text-slate-400 hover:text-slate-600' }} pb-1">Jornada Actual</button>
                <button wire:click="$set('view', 'history')" class="text-sm font-black uppercase tracking-widest {{ $view == 'history' || $view == 'details' ? 'text-[#043d8a] border-b-2 border-[#043d8a]' : 'text-slate-400 hover:text-slate-600' }} pb-1">Historial</button>
                <button wire:click="$set('view', 'debts')" class="text-sm font-black uppercase tracking-widest {{ $view == 'debts' ? 'text-amber-500 border-b-2 border-amber-500' : 'text-slate-400 hover:text-slate-600' }} pb-1 flex items-center">
                    Cobranzas 
                    @php 
                        $globalPending = \App\Models\GasSale::where('status', 'pending')
                            ->where('user_id', auth()->id())
                            ->count(); 
                    @endphp
                    @if($globalPending > 0)
                        <span class="ml-2 bg-amber-500 text-white text-[8px] px-1.5 py-0.5 rounded-full">{{ $globalPending }}</span>
                    @endif
                </button>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            @if(!$activeSession && $view == 'active')
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Caja Inicial</label>
                        <input type="number" wire:model="starting_cash" class="w-24 border-none p-0 focus:ring-0 text-lg font-bold text-slate-800" placeholder="0.00">
                    </div>
                    <button wire:click="openSession" class="bg-[#043d8a] hover:bg-[#064fb3] text-white px-6 py-3 rounded-xl font-black uppercase text-xs tracking-widest transition-all shadow-lg shadow-blue-900/20">
                        Abrir Jornada
                    </button>
                </div>
            @elseif($activeSession && $view == 'active')
                <div class="bg-emerald-50 text-emerald-700 px-4 py-2 rounded-full border border-emerald-100 flex items-center space-x-2">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </span>
                    <span class="text-sm font-black uppercase tracking-widest">Jornada Activa: {{ \Carbon\Carbon::parse($activeSession->date)->format('d/m/Y') }}</span>
                </div>
                <button wire:click="closeSession" onclick="confirm('¿Estás seguro de cerrar la jornada?') || event.stopImmediatePropagation()" class="bg-rose-500 hover:bg-rose-600 text-white px-6 py-3 rounded-xl font-black uppercase text-xs tracking-widest transition-all shadow-lg shadow-rose-900/20">
                    Cerrar Jornada
                </button>
            @endif
        </div>
    </div>

    @if($view == 'active')
        @if($activeSession)
            <!-- Dashboard Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Starting Cash -->
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-slate-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="relative">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Caja Inicial</p>
                        <h3 class="text-3xl font-black text-slate-900">S/ {{ number_format($activeSession->starting_cash, 2) }}</h3>
                    </div>
                </div>

                <!-- Sales -->
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="relative">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Ventas Diarias</p>
                        <h3 class="text-3xl font-black text-emerald-600">S/ {{ number_format($totalSales, 2) }}</h3>
                    </div>
                </div>

                <!-- Expenses -->
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-rose-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="relative">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Gastos Totales</p>
                        <h3 class="text-3xl font-black text-rose-600">S/ {{ number_format($totalExpenses, 2) }}</h3>
                    </div>
                </div>

                <!-- Balance -->
                <div class="bg-[#043d8a] p-6 rounded-[2rem] shadow-xl shadow-blue-900/20 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="relative">
                        <p class="text-[10px] font-black text-blue-200 uppercase tracking-widest mb-1">Efectivo en Caja</p>
                        <h3 class="text-3xl font-black text-white">S/ {{ number_format($currentBalance, 2) }}</h3>
                    </div>
                </div>

                <!-- Pending Collections -->
                <div class="bg-amber-50 p-6 rounded-[2rem] border border-amber-100 shadow-xl shadow-amber-200/50 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-100/50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="relative">
                        <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-1">Pendiente de Cobro</p>
                        <h3 class="text-3xl font-black text-amber-700">S/ {{ number_format($totalCollections, 2) }}</h3>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    <!-- Record Sale Form -->
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
                        <div class="p-8 border-b border-slate-50 bg-slate-50/50">
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">Registrar Nueva Venta</h2>
                        </div>
                        <div class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-6" x-data="{ method: @entangle('payment_method') }">
                                <div class="md:col-span-1">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Cliente</label>
                                    <select wire:model="client_id" class="w-full bg-slate-50 border-slate-100 rounded-xl focus:ring-[#043d8a] focus:border-[#043d8a] text-sm font-bold p-3">
                                        <option value="">Seleccionar Cliente (Obligatorio)</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('client_id') <span class="text-[10px] text-rose-500 font-bold ml-2">{{ $message }}</span> @enderror
                                </div>
                                <div class="hidden">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Cantidad</label>
                                    <input type="number" wire:model="quantity" class="w-full bg-slate-50 border-slate-100 rounded-xl focus:ring-[#043d8a] focus:border-[#043d8a] text-sm font-bold p-3">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Monto Total</label>
                                    <input type="number" wire:model="amount" step="0.01" class="w-full bg-slate-50 border-slate-100 rounded-xl focus:ring-[#043d8a] focus:border-[#043d8a] text-sm font-bold p-3" placeholder="0.00">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Método de Pago</label>
                                    <select x-model="method" wire:model.live="payment_method" class="w-full bg-slate-50 border-slate-100 rounded-xl focus:ring-[#043d8a] focus:border-[#043d8a] text-sm font-bold p-3">
                                        <option value="cash">Efectivo</option>
                                        <option value="yape">Yape / Plin</option>
                                        <option value="card">Tarjeta</option>
                                        <option value="credit">Crédito (A Deuda)</option>
                                        <option value="demo">Demo (Gratuito)</option>
                                    </select>
                                </div>
                                
                                <div x-show="method == 'credit'" x-cloak x-transition class="animate-in fade-in slide-in-from-top-2">
                                    <label class="block text-[10px] font-black text-amber-600 uppercase tracking-widest mb-2">¿Cuánto Abonó hoy?</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-amber-600 font-bold text-sm">S/</span>
                                        <input type="number" wire:model="paid_amount" step="0.01" class="w-full bg-amber-50 border-amber-200 rounded-xl focus:ring-amber-500 focus:border-amber-500 text-sm font-bold p-3 pl-10" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-8 pt-6 border-t border-slate-50 flex justify-end">
                                <button wire:click="addSale" class="px-10 py-4 bg-[#043d8a] hover:bg-[#032d66] text-white rounded-[1.25rem] font-black uppercase text-xs tracking-widest transition-all shadow-xl shadow-[#043d8a]/20 transform active:scale-95 flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                                    <span>{{ $editingSaleId ? 'Actualizar Registro' : 'Confirmar y Registrar Venta' }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
                        <div class="p-8 border-b border-slate-50 bg-slate-50/50">
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">Registrar Gasto</h2>
                        </div>
                        <div class="p-8 space-y-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Descripción</label>
                                <input type="text" wire:model="expense_description" placeholder="Ej: Comida, Combustible..." class="w-full bg-slate-50 border-slate-100 rounded-xl focus:ring-[#043d8a] focus:border-[#043d8a] text-sm font-bold p-3">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Monto</label>
                                    <input type="number" wire:model="expense_amount" step="0.01" class="w-full bg-slate-50 border-slate-100 rounded-xl focus:ring-[#043d8a] focus:border-[#043d8a] text-sm font-bold p-3">
                                </div>
                                <div class="grid grid-cols-1 gap-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Categoría</label>
                                    <select wire:model="expense_category" class="w-full bg-slate-50 border-slate-100 rounded-xl focus:ring-[#043d8a] focus:border-[#043d8a] text-sm font-bold p-3">
                                        <option value="food">Comida</option>
                                        <option value="fuel">Petrolio</option>
                                        <option value="advance">Adelanto</option>
                                        <option value="other">Otro</option>
                                    </select>
                                </div>
                            </div>
                            <button wire:click="addExpense" class="w-full bg-rose-500 hover:bg-rose-600 text-white p-3 rounded-xl font-black uppercase text-xs tracking-widest transition-all">
                                {{ $editingExpenseId ? 'Actualizar Gasto' : 'Agregar Gasto' }}
                            </button>
                        </div>
                    </div>
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
                        <div class="p-8 border-b border-slate-50">
                            <h2 class="text-sm font-black text-slate-900 uppercase tracking-widest">Gastos del Día</h2>
                        </div>
                        <div class="divide-y divide-slate-50 max-h-[400px] overflow-y-auto">
                            @forelse($expenses as $expense)
                                <div wire:key="expense-{{ $expense->id }}" class="p-6 flex items-center justify-between hover:bg-slate-50/50 transition-colors {{ $editingExpenseId == $expense->id ? 'bg-blue-50/50' : '' }}">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center text-rose-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900">{{ $expense->description }}</div>
                                            <div class="text-[10px] text-slate-400 font-black uppercase tracking-widest">{{ $expense->category }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <div class="text-right">
                                            <div class="font-black text-rose-600">- S/ {{ number_format($expense->amount, 2) }}</div>
                                            <div class="text-[10px] text-slate-400">{{ $expense->created_at->format('H:i A') }}</div>
                                        </div>
                                        <div class="flex flex-col space-y-1">
                                            <button wire:click="editExpense({{ $expense->id }})" class="p-1.5 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                            </button>
                                            <button wire:click="deleteExpense({{ $expense->id }})" onclick="confirm('¿Eliminar este gasto?') || event.stopImmediatePropagation()" class="p-1.5 text-rose-300 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-slate-400 text-xs font-medium italic">No hay gastos registrados.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sales List Full Width -->
            <div class="mt-8 bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
                <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-emerald-500 rounded-2xl text-white shadow-lg shadow-emerald-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight leading-none">Ventas del Día</h2>
                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-1">Registros de la jornada actual</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-black bg-emerald-100 text-emerald-700 px-4 py-2 rounded-full uppercase tracking-widest border border-emerald-200">{{ count($sales) }} Registros</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50/50 border-b border-slate-100">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Cliente</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Producto</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Cant.</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Monto Total</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Abonado</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Deuda</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Metodo</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Gestión de Cobro / Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($sales as $sale)
                                <tr wire:key="sale-{{ $sale->id }}" class="hover:bg-slate-50/50 transition-colors {{ $editingSaleId == $sale->id ? 'bg-blue-50/80' : '' }}">
                                    <td class="px-8 py-5">
                                        <div class="font-black text-slate-900 text-sm">{{ $sale->client?->name ?? 'Caminante' }}</div>
                                        <div class="text-[10px] text-slate-400 font-black uppercase tracking-tighter">{{ $sale->created_at->format('H:i A') }}</div>
                                    </td>
                                    <td class="px-8 py-5 font-bold text-slate-700 uppercase text-xs">
                                        {{ $sale->cylinder_type ?? 'Producto' }}
                                    </td>
                                    <td class="px-8 py-5 font-black text-slate-900 text-center">1</td>
                                    <td class="px-8 py-5 font-black text-slate-900">S/ {{ number_format($sale->amount, 2) }}</td>
                                    <td class="px-8 py-5 font-bold text-emerald-600">S/ {{ number_format($sale->paid_amount, 2) }}</td>
                                    <td class="px-8 py-5 font-bold text-rose-600">
                                        @if($sale->amount - $sale->paid_amount > 0)
                                            S/ {{ number_format($sale->amount - $sale->paid_amount, 2) }}
                                        @else
                                            <span class="text-emerald-500 font-black text-[10px] uppercase tracking-widest">Pagado</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest 
                                            @if($sale->payment_method == 'credit') bg-amber-100 text-amber-700 border border-amber-200
                                            @elseif($sale->payment_method == 'demo') bg-purple-100 text-purple-700 border border-purple-200
                                            @else bg-blue-100 text-[#043d8a] border border-blue-200 @endif">
                                            {{ $sale->payment_method }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end space-x-2">
                                            @if($sale->status == 'pending')
                                                <div class="flex items-center space-x-1 bg-emerald-50 p-1 rounded-xl border border-emerald-100 mr-2">
                                                    <input type="number" wire:model="payment_increments.{{ $sale->id }}" step="0.01" placeholder="Monto" class="w-16 bg-white border-none rounded-lg text-[10px] font-black p-1.5 focus:ring-0">
                                                    <button wire:click="addPayment({{ $sale->id }})" class="bg-emerald-500 hover:bg-emerald-600 text-white p-1.5 rounded-lg transition-all shadow-sm" title="Registrar Abono">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                                                    </button>
                                                    <button wire:click="markAsPaid({{ $sale->id }})" class="bg-emerald-100 text-emerald-700 hover:bg-emerald-200 p-1.5 rounded-lg transition-colors border border-emerald-200" title="Marcar Pago Total">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                                    </button>
                                                </div>
                                            @endif
                                            <button wire:click="editSale({{ $sale->id }})" class="p-2.5 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-all" title="Editar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                            </button>
                                            <button wire:click="deleteSale({{ $sale->id }})" onclick="confirm('¿Eliminar esta venta?') || event.stopImmediatePropagation()" class="p-2.5 text-rose-300 hover:bg-rose-50 rounded-xl transition-all" title="Eliminar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-8 py-16 text-center">
                                        <div class="text-slate-300 mb-2 font-medium italic">No hay ventas registradas en esta jornada.</div>
                                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Las ventas aparecerán aquí una vez que las registres arriba.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Inventory Control removed as it's not needed for TV accounts -->
        @else
            <!-- Welcome / Empty State -->
            <div class="bg-white rounded-[3rem] p-16 text-center border border-slate-100 shadow-2xl shadow-slate-200/50 border-dashed">
                <div class="w-24 h-24 bg-blue-50 rounded-[2rem] flex items-center justify-center mx-auto mb-8 text-[#043d8a]">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h2 class="text-3xl font-black text-slate-900 mb-4 uppercase tracking-tight">No hay una jornada abierta</h2>
                <p class="text-slate-500 max-w-md mx-auto font-medium text-lg leading-relaxed mb-10">Para comenzar a registrar las ventas, gastos e inventario de hoy, por favor ingresa el monto inicial de caja y abre la jornada.</p>
                <div class="max-w-xs mx-auto bg-slate-50 p-8 rounded-[2rem] border border-slate-100">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Saldo Inicial en Caja (S/)</label>
                    <input type="number" wire:model="starting_cash" class="w-full text-3xl font-black text-center bg-transparent border-none focus:ring-0 text-slate-900 mb-6" placeholder="0.00">
                    <button wire:click="openSession" class="w-full bg-[#043d8a] hover:bg-[#064fb3] text-white py-5 rounded-2xl font-black uppercase text-sm tracking-widest transition-all shadow-xl shadow-blue-900/30 active:scale-95">
                        Empezar Jornada
                    </button>
                </div>
            </div>
        @endif
    @elseif($view == 'history')
        <!-- History List View -->
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">Historial de Jornadas</h2>
                
                <!-- Filters -->
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center bg-slate-50 border border-slate-100 rounded-xl px-4 py-2">
                        <svg class="w-4 h-4 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <input type="text" wire:model.live.debounce.300ms="searchClient" placeholder="Buscar Cliente..." class="bg-transparent border-none p-0 focus:ring-0 text-xs font-bold text-slate-700 w-40">
                    </div>
                    <div class="flex items-center bg-slate-50 border border-slate-100 rounded-xl px-4 py-2">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mr-3">Desde</span>
                        <input type="date" wire:model.live="filterStartDate" class="bg-transparent border-none p-0 focus:ring-0 text-xs font-bold text-slate-700">
                    </div>
                    <div class="flex items-center bg-slate-50 border border-slate-100 rounded-xl px-4 py-2">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mr-3">Hasta</span>
                        <input type="date" wire:model.live="filterEndDate" class="bg-transparent border-none p-0 focus:ring-0 text-xs font-bold text-slate-700">
                    </div>
                    @if($filterStartDate || $filterEndDate || $searchClient)
                        <button wire:click="$set('filterStartDate', null); $set('filterEndDate', null); $set('searchClient', '')" class="text-[9px] font-black text-rose-500 uppercase tracking-widest hover:underline">Limpiar</button>
                    @endif
                </div>
            </div>

            @if($filterStartDate || $filterEndDate || $searchClient)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 px-8 mt-4">
                    <div class="bg-emerald-50 p-6 rounded-3xl border border-emerald-100 shadow-sm relative overflow-hidden group">
                        <div class="absolute -right-4 -top-4 w-16 h-16 bg-emerald-100/50 rounded-full group-hover:scale-110 transition-transform"></div>
                        <div class="relative">
                            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1">Total Ventas (Periodo)</p>
                            <h3 class="text-2xl font-black text-emerald-700">S/ {{ number_format($totalPeriodSales, 2) }}</h3>
                        </div>
                    </div>
                    <div class="bg-blue-50 p-6 rounded-3xl border border-blue-100 shadow-sm relative overflow-hidden group">
                        <div class="absolute -right-4 -top-4 w-16 h-16 bg-blue-100/50 rounded-full group-hover:scale-110 transition-transform"></div>
                        <div class="relative">
                            <p class="text-[10px] font-black text-[#043d8a] uppercase tracking-widest mb-1">Total Cobrado (Periodo)</p>
                            <h3 class="text-2xl font-black text-[#043d8a]">S/ {{ number_format($totalPeriodCollected, 2) }}</h3>
                        </div>
                    </div>
                    <div class="bg-rose-50 p-6 rounded-3xl border border-rose-100 shadow-sm relative overflow-hidden group">
                        <div class="absolute -right-4 -top-4 w-16 h-16 bg-rose-100/50 rounded-full group-hover:scale-110 transition-transform"></div>
                        <div class="relative">
                            <p class="text-[10px] font-black text-rose-600 uppercase tracking-widest mb-1">Total Gastos (Periodo)</p>
                            <h3 class="text-2xl font-black text-rose-700">S/ {{ number_format($totalPeriodExpenses, 2) }}</h3>
                        </div>
                    </div>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Fecha</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Caja Inicial</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Caja Final</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Ventas</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Gastos</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($history as $session)
                            @php 
                                $hasDebt = $session->gasSales()->where('status', 'pending')->exists();
                                $pendingAmount = $session->gasSales()->sum('amount') - $session->gasSales()->sum('paid_amount');
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors {{ $hasDebt ? 'bg-amber-50/30' : '' }}">
                                <td class="px-8 py-5">
                                    <div class="flex items-center space-x-3">
                                        <div>
                                            <div class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($session->date)->format('d/m/Y') }}</div>
                                            <div class="text-[10px] text-slate-400">{{ $session->start_time }} - {{ $session->end_time }}</div>
                                        </div>
                                        @if($session->status == 'open')
                                            <span class="bg-emerald-100 text-emerald-700 text-[8px] font-black uppercase px-2 py-0.5 rounded-full border border-emerald-200 flex items-center">
                                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1 animate-pulse"></span>
                                                Activa
                                            </span>
                                        @endif
                                        @if($hasDebt)
                                            <span class="bg-amber-100 text-amber-700 text-[8px] font-black uppercase px-2 py-0.5 rounded-full border border-amber-200">Deuda: S/ {{ number_format($pendingAmount, 2) }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-5 font-bold text-slate-700">S/ {{ number_format($session->starting_cash, 2) }}</td>
                                <td class="px-8 py-5 font-black text-slate-900">S/ {{ number_format($session->closing_cash, 2) }}</td>
                                <td class="px-8 py-5">
                                    <div class="text-emerald-600 font-bold">S/ {{ number_format($session->gasSales()->sum('paid_amount'), 2) }}</div>
                                    <div class="text-[9px] text-slate-400">De S/ {{ number_format($session->gasSales()->sum('amount'), 2) }}</div>
                                </td>
                                <td class="px-8 py-5 text-rose-600 font-bold">S/ {{ number_format($session->expenses()->sum('amount'), 2) }}</td>
                                <td class="px-8 py-5 text-right">
                                    <button wire:click="viewDetails({{ $session->id }})" class="bg-slate-100 hover:bg-[#043d8a] hover:text-white text-slate-600 px-4 py-2 rounded-lg font-black text-[10px] uppercase tracking-widest transition-all">Ver Detalles</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-8">
                {{ $history->links() }}
            </div>
        </div>
    @elseif($view == 'details' && $selectedSession)
        <!-- Detailed View for a Session -->
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <button wire:click="backToHistory" class="flex items-center text-slate-400 hover:text-slate-900 transition-colors">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    <span class="text-xs font-black uppercase tracking-widest">Volver al Historial</span>
                </button>
                <div class="text-right">
                    <h2 class="text-2xl font-black text-slate-900 uppercase">Jornada: {{ \Carbon\Carbon::parse($selectedSession->date)->format('d/m/Y') }}</h2>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Cerrada a las {{ $selectedSession->end_time }}</p>
                </div>
            </div>

            <!-- Summary at Top -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Caja Inicial</p>
                    <p class="text-lg font-black text-slate-900">S/ {{ number_format($selectedSession->starting_cash, 2) }}</p>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm border-l-4 border-l-emerald-500">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Recaudado</p>
                    <p class="text-lg font-black text-emerald-600">S/ {{ number_format($selectedSession->gasSales()->sum('paid_amount'), 2) }}</p>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm border-l-4 border-l-rose-500">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Gastos</p>
                    <p class="text-lg font-black text-rose-600">S/ {{ number_format($selectedSession->expenses()->sum('amount'), 2) }}</p>
                </div>
                <div class="bg-[#043d8a] p-4 rounded-2xl shadow-sm">
                    <p class="text-[8px] font-black text-blue-200 uppercase tracking-widest mb-1">Caja Final</p>
                    <p class="text-lg font-black text-white">S/ {{ number_format($selectedSession->closing_cash, 2) }}</p>
                </div>
                <div class="bg-amber-50 p-4 rounded-2xl border border-amber-100 shadow-sm border-l-4 border-l-amber-500">
                    <p class="text-[8px] font-black text-amber-600 uppercase tracking-widest mb-1">Pendiente</p>
                    <p class="text-lg font-black text-amber-700">S/ {{ number_format($selectedSession->gasSales()->sum('amount') - $selectedSession->gasSales()->sum('paid_amount'), 2) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-8">
                <div class="col-span-2 space-y-6">
                    <!-- Detailed Sales -->
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
                        <div class="p-6 border-b border-slate-50 bg-slate-50/30">
                            <h3 class="text-sm font-black uppercase tracking-widest">Detalle de Ventas</h3>
                        </div>
                        <table class="w-full text-left">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3 text-[9px] font-black uppercase text-slate-400">Cliente</th>
                                    <th class="px-6 py-3 text-[9px] font-black uppercase text-slate-400">Producto</th>
                                    <th class="px-6 py-3 text-[9px] font-black uppercase text-slate-400">Total</th>
                                    <th class="px-6 py-3 text-[9px] font-black uppercase text-slate-400">Abonado</th>
                                    <th class="px-6 py-3 text-[9px] font-black uppercase text-slate-400">Deuda</th>
                                    <th class="px-6 py-3 text-[9px] font-black uppercase text-slate-400">Pago</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($selectedSession->gasSales as $s)
                                    <tr class="text-sm border-b border-slate-50 last:border-0">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-900">{{ $s->client?->name ?? 'Caminante' }}</div>
                                            <!-- Payment History Dropdown/List -->
                                            @if($s->payments->count() > 0)
                                                <div class="mt-2 space-y-1">
                                                    @foreach($s->payments as $p)
                                                        <div class="flex items-center text-[9px] text-slate-400 bg-slate-50 rounded px-2 py-0.5 w-fit">
                                                            <span class="font-black mr-2">S/ {{ number_format($p->amount, 2) }}</span>
                                                            <span>{{ $p->created_at->format('d/m H:i') }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 uppercase text-xs font-bold text-slate-600">{{ $s->cylinder_type ?? 'Producto' }} x1</td>
                                        <td class="px-6 py-4 font-black">S/ {{ number_format($s->amount, 2) }}</td>
                                        <td class="px-6 py-4 font-bold text-emerald-600">S/ {{ number_format($s->paid_amount, 2) }}</td>
                                        <td class="px-6 py-4 font-bold text-rose-600">S/ {{ number_format($s->amount - $s->paid_amount, 2) }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 rounded-full text-[8px] font-black uppercase {{ $s->status == 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                                {{ $s->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <!-- Read-only in details view as requested -->
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Detailed Expenses -->
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
                        <div class="p-6 border-b border-slate-50 bg-slate-50/30">
                            <h3 class="text-sm font-black uppercase tracking-widest">Detalle de Gastos</h3>
                        </div>
                        <table class="w-full text-left">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3 text-[9px] font-black uppercase text-slate-400">Descripción</th>
                                    <th class="px-6 py-3 text-[9px] font-black uppercase text-slate-400">Categoría</th>
                                    <th class="px-6 py-3 text-[9px] font-black uppercase text-slate-400">Monto</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($selectedSession->expenses as $e)
                                    <tr class="text-sm">
                                        <td class="px-6 py-4 font-bold text-slate-900">{{ $e->description }}</td>
                                        <td class="px-6 py-4 uppercase text-xs font-bold text-slate-600">{{ $e->category }}</td>
                                        <td class="px-6 py-4 font-black text-rose-600">S/ {{ number_format($e->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-6">
                        <!-- Inventory removed from details -->
                </div>
            </div>
        </div>
    @elseif($view == 'debts')
        <!-- Global Debts View -->
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">Cuentas por Cobrar</h2>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-1">Todas las ventas con deuda pendiente</p>
                    @if(!$activeSession)
                        <div class="mt-2 bg-amber-50 text-amber-700 text-[10px] font-black uppercase tracking-wider px-3 py-2 rounded-xl border border-amber-100 flex items-center space-x-2">
                            <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>Debe abrir una jornada para registrar abonos</span>
                        </div>
                    @endif
                </div>

                <div class="flex items-center bg-slate-50 border border-slate-100 rounded-xl px-4 py-2">
                    <svg class="w-4 h-4 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="text" wire:model.live.debounce.300ms="searchClient" placeholder="Buscar por Cliente..." class="bg-transparent border-none p-0 focus:ring-0 text-xs font-bold text-slate-700 w-48">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Fecha/Sesión</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Cliente</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Monto Total</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Abonado</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Deuda Restante</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Acciones de Cobro</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($pendingDebts as $debt)
                            <tr wire:key="debt-{{ $debt->id }}" class="hover:bg-amber-50/20 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="font-bold text-slate-900">{{ $debt->created_at->format('d/m/Y') }}</div>
                                    <div class="text-[10px] text-slate-400">Sesión #{{ $debt->sales_session_id }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="font-bold text-slate-900">{{ $debt->client?->name ?? 'Caminante' }}</div>
                                    @if($debt->payments->count() > 0)
                                        <div class="mt-1 flex flex-wrap gap-1">
                                            @foreach($debt->payments as $p)
                                                <span class="text-[8px] bg-slate-100 px-1.5 py-0.5 rounded text-slate-500">S/{{ number_format($p->amount, 2) }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="px-8 py-5 font-bold text-slate-700">S/ {{ number_format($debt->amount, 2) }}</td>
                                <td class="px-8 py-5 font-bold text-emerald-600">S/ {{ number_format($debt->paid_amount, 2) }}</td>
                                <td class="px-8 py-5 font-black text-rose-600">S/ {{ number_format($debt->amount - $debt->paid_amount, 2) }}</td>
                                <td class="px-8 py-5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end space-x-2">
                                        <input type="number" wire:model="payment_increments.{{ $debt->id }}" step="0.01" placeholder="Abonar" class="w-24 bg-slate-50 border-slate-100 rounded-lg text-xs font-bold p-2 focus:ring-amber-500">
                                        <button wire:click="addPayment({{ $debt->id }})" class="bg-amber-500 hover:bg-amber-600 text-white p-2 rounded-lg transition-all shadow-sm" title="Registrar Abono">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                        </button>
                                        <button wire:click="markAsPaid({{ $debt->id }})" class="bg-emerald-500 hover:bg-emerald-600 text-white p-2 rounded-lg transition-all shadow-sm" title="Liquidar Deuda">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-12 text-center text-slate-400 italic">No hay cuentas por cobrar pendientes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
