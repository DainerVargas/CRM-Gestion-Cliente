<div class="space-y-8">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-[#0f172a]">
                Gestión de <span class="text-primary font-black">Clientes</span>

            </h2>
            <p class="mt-2 text-sm text-slate-500 font-medium">
                Listado completo y administración de tu cartera de clientes.
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4 space-x-2">
            <!-- Export Button -->
            <button wire:click="export" type="button"
                class="inline-flex items-center px-4 py-3 border border-slate-200 rounded-2xl shadow-sm text-sm font-bold text-slate-700 bg-white hover:bg-slate-50 transition-all duration-200">
                <svg class="mr-2 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Exportar
            </button>

            <!-- Import Button/Input -->
            <div class="relative">
                <input type="file" wire:model="importFile" class="hidden" id="import_file_input" accept=".xlsx,.xls,.csv">
                <label for="import_file_input" class="cursor-pointer inline-flex items-center px-4 py-3 border border-slate-200 rounded-2xl shadow-sm text-sm font-bold text-slate-700 bg-white hover:bg-slate-50 transition-all duration-200">
                    <svg class="mr-2 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Importar
                </label>
                <div wire:loading wire:target="importFile" class="absolute -top-1 -right-1">
                     <span class="flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-500"></span>
                    </span>
                </div>
            </div>

            <button wire:click="$dispatch('open-create-modal')" type="button"
                class="inline-flex items-center px-6 py-3 border border-transparent rounded-2xl shadow-lg shadow-indigo-500/20 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-200">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nuevo Cliente
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="bg-emerald-50 border-l-4 border-emerald-400 p-4 rounded-xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-emerald-800">
                        {{ session('message') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-rose-50 border-l-4 border-rose-400 p-4 rounded-xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-rose-800">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Filters and Search -->
    <div
        class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text"
                placeholder="Buscar por nombre, email o teléfono..."
                class="block w-full pl-11 pr-4 py-3 border-transparent bg-slate-50 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all duration-200 text-sm font-medium">
        </div>
        <div class="w-full md:w-64">
            <select wire:model.live="status"
                class="block w-full px-4 py-3 border-transparent bg-slate-50 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all duration-200 text-sm font-medium appearance-none">
                <option value="">Todos los estados</option>
                <option value="prospect">Prospecto</option>
                <option value="active">Activo</option>
                <option value="inactive">Inactivo</option>
                <option value="libre">Libre</option>
                <option value="not_interested">No interesado</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">
                            Nombre</th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">
                            Contacto</th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">
                            Empresa / Rubro</th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">
                            Estado</th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">
                            Última Llamada</th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">
                            Asesor</th>

                        <th class="px-8 py-4 text-right text-xs font-bold text-slate-400 uppercase tracking-widest">
                            Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($clients as $client)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-8 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 font-bold text-sm group-hover:bg-indigo-600 group-hover:text-white transition-all duration-200">
                                        {{ substr($client->name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-slate-900">{{ $client->name }}</div>
                                        <div class="text-xs {{ $client->next_billing_date ? 'text-indigo-500 font-bold' : 'text-slate-400 font-medium' }}">
                                            {{ $client->next_billing_date ? 'Próximo cobro: ' . $client->next_billing_date->format('d/m/Y') : 'Sin fecha de cobro' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-5 whitespace-nowrap">
                                <div class="text-sm text-slate-600 font-medium">{{ $client->email ?? 'Sin email' }}
                                </div>
                                <div class="text-xs text-slate-400">{{ $client->phone }}</div>
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap">
                                <div class="text-sm text-slate-900 font-bold">{{ $client->company ?: '-' }}</div>
                                <div class="text-[10px] text-slate-400 font-black uppercase tracking-widest">
                                    {{ $client->rubro ?: 'Sin Rubro' }}
                                </div>
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        'active' => 'bg-emerald-100 text-emerald-700',
                                        'libre' => 'bg-slate-100 text-slate-500',
                                        'prospect' => 'bg-amber-100 text-amber-700',
                                        'inactive' => 'bg-rose-100 text-rose-700',
                                        'not_interested' => 'bg-slate-200 text-slate-600',
                                    ];
                                    $currentClass = $statusClasses[$client->status] ?? 'bg-slate-100 text-slate-700';
                                @endphp
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-black uppercase tracking-wider {{ $currentClass }}">
                                    @if($client->status == 'libre')
                                        Libre
                                    @elseif($client->status == 'not_interested')
                                        No interesado
                                    @else
                                        {{ ucfirst($client->status) }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-8 py-5 max-w-xs">
                                @if($client->latestCall)
                                    <div class="text-xs text-slate-600 line-clamp-2 italic">
                                        {{ $client->latestCall->observations }}
                                    </div>
                                    @if($client->latestCall->next_call_at)
                                        <div class="mt-1 inline-flex items-center px-2 py-0.5 rounded-md bg-indigo-50 text-[10px] font-bold text-indigo-600 border border-indigo-100">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            PRÓXIMA: {{ $client->latestCall->next_call_at->format('d/m H:i') }}
                                        </div>
                                    @endif
                                @else
                                    <span class="text-xs text-slate-400 italic">Sin observaciones</span>
                                @endif
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap">
                                <span class="text-sm font-medium text-slate-900">
                                    {{ $client->user->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('clients.show', ['id' => $client->id, 'page' => $clients->currentPage(), 'search' => $search, 'status' => $status]) }}"
                                        class="inline-block text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <button wire:click="delete({{ $client->id }})" wire:confirm="¿Estás seguro?"
                                        class="text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 p-2 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-10 text-center text-slate-400 italic">
                                No se encontraron clientes que coincidan con los filtros.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="px-8 py-2 bg-slate-50/50 border-t border-slate-100">
            {{ $clients->links() }}
        </div>
    </div>
    @livewire('clients.create-modal')

    <!-- Import Results Modal -->
    @if($showImportModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <!-- Background Overlay con opacidad más baja y menos blur -->
            <div class="fixed inset-0 bg-slate-900/80 transition-opacity" wire:click="$set('showImportModal', false)"></div>

            <!-- Modal Content -->
            <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200 transform transition-all">
                <div class="p-8">
                    <div class="flex items-center mb-6">
                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-2xl bg-indigo-600 text-white shadow-lg">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-black text-slate-900 leading-tight">
                                Resultado de la <span class="text-indigo-600">Importación</span>
                            </h3>
                            <p class="text-sm text-slate-500 font-medium">Resumen del proceso finalizado</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl text-center">
                            <div class="text-3xl font-black text-emerald-600">{{ $importResults['created'] }}</div>
                            <div class="text-[10px] font-bold text-emerald-700 uppercase tracking-widest mt-1">Creados</div>
                        </div>
                        <div class="bg-blue-50 border border-blue-100 p-4 rounded-2xl text-center">
                            <div class="text-3xl font-black text-blue-600">{{ $importResults['updated'] }}</div>
                            <div class="text-[10px] font-bold text-blue-700 uppercase tracking-widest mt-1">Actualizados</div>
                        </div>
                        <div class="bg-rose-50 border border-rose-100 p-4 rounded-2xl text-center">
                            <div class="text-3xl font-black text-rose-600">{{ $importResults['errors'] }}</div>
                            <div class="text-[10px] font-bold text-rose-700 uppercase tracking-widest mt-1">Errores</div>
                        </div>
                    </div>

                    @if(count($importResults['details']) > 0)
                        <div class="mt-6">
                            <h4 class="text-sm font-bold text-slate-900 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Detalles de errores:
                            </h4>
                            <div class="max-h-48 overflow-y-auto bg-slate-50 rounded-2xl p-4 border border-slate-100">
                                <ul class="space-y-2">
                                    @foreach($importResults['details'] as $detail)
                                        <li class="flex items-start text-xs text-slate-600 font-medium leading-relaxed">
                                            <span class="text-rose-500 mr-2 flex-shrink-0 mt-0.5">•</span>
                                            <span>{{ $detail }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="bg-slate-50 px-8 py-6 border-t border-slate-100 flex justify-end">
                    <button type="button" wire:click="$set('showImportModal', false)"
                        class="w-full sm:w-auto inline-flex justify-center rounded-2xl px-8 py-4 bg-slate-900 text-sm font-bold text-white hover:bg-indigo-600 shadow-xl shadow-slate-900/10 transition-all duration-300">
                        Cerrar y ver listado
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
