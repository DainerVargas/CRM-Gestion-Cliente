<div class="space-y-10">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-[#0f172a]">
                Historial de <span class="text-[#45C2ED] font-black">Llamadas</span>
            </h2>
            <p class="mt-2 text-sm text-slate-500 font-medium">
                Resumen global de todas las interacciones realizadas con clientes.
            </p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <input wire:model.live="search" type="text" placeholder="Buscar cliente..." class="block w-full pl-11 pr-4 py-3 border-transparent bg-slate-50 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all duration-200 text-sm font-medium">
        </div>
        
        <div>
            <select wire:model.live="type" class="block w-full px-4 py-3 border-transparent bg-slate-50 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all duration-200 text-sm font-medium appearance-none">
                <option value="">Todos los tipos</option>
                <option value="inbound">Entrada</option>
                <option value="outbound">Salida</option>
            </select>
        </div>

        <div>
            <select wire:model.live="result" class="block w-full px-4 py-3 border-transparent bg-slate-50 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all duration-200 text-sm font-medium appearance-none">
                <option value="">Todos los resultados</option>
                <option value="interested">Interesado</option>
                <option value="closed">Cerrado</option>
                <option value="pending">Pendiente</option>
                <option value="not_interested">No Interesado</option>
            </select>
        </div>
    </div>

    <!-- Calls Table Card -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Cliente</th>
                        <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Tipo</th>
                        <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Duración</th>
                        <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Resultado</th>
                        <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Siguiente Cita</th>
                        <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Fecha y Hora</th>
                        <th class="px-8 py-5 text-right text-xs font-black text-slate-400 uppercase tracking-widest"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($calls as $call)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-8 py-5 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 font-black text-xs">
                                    {{ substr($call->client->name, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-black text-slate-900">{{ $call->client->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">{{ $call->client->company ?? 'Empresa N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap">
                            <span class="inline-flex items-center px-4 py-1 rounded-xl text-[10px] font-black uppercase tracking-wider 
                                {{ $call->type == 'inbound' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-900 text-white' }}">
                                {{ $call->type == 'inbound' ? 'Entrada' : 'Salida' }}
                            </span>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-600 font-bold tabular-nums">
                            {{ $call->duration }} min
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap">
                            @php
                                $statusClasses = [
                                    'interested' => 'bg-emerald-100 text-emerald-700',
                                    'closed' => 'bg-indigo-100 text-indigo-700',
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'not_interested' => 'bg-rose-100 text-rose-700',
                                ];
                                $currentClass = $statusClasses[$call->result] ?? 'bg-slate-100 text-slate-700';
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $currentClass }}">
                                {{ str_replace('_', ' ', $call->result) }}
                            </span>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap">
                            @if($call->next_call_at)
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-indigo-600">{{ $call->next_call_at->format('d/m/Y H:i') }}</span>
                                    <span class="text-[10px] {{ $call->notified ? 'text-emerald-500' : 'text-amber-500 font-bold' }} flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/></svg>
                                        {{ $call->notified ? 'Notificado' : 'Pendiente' }}
                                    </span>
                                </div>
                            @else
                                <span class="text-xs text-slate-300 italic font-medium">Sin seguimiento</span>
                            @endif
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-500 font-medium">
                            {{ $call->called_at->format('d/m/Y H:i') }}
                            <div class="text-[10px] text-slate-400 lowercase">{{ $call->called_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap text-right">
                            <a href="{{ route('clients.show', $call->client->id) }}" class="p-2 text-indigo-100 hover:text-indigo-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-12 text-center text-slate-400 italic font-medium">
                            No se encontraron registros de llamadas que coincidan con los filtros.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($calls->hasPages())
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
            {{ $calls->links() }}
        </div>
        @endif
    </div>
</div>
