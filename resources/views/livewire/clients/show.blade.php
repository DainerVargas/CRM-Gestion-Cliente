<div class="space-y-10 pb-20">
    <div>
        <a href="{{ route('clients.index', ['page' => $index_page, 'search' => $index_search, 'status' => $index_status]) }}" 
           class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-primary to-secondary text-white rounded-xl shadow-lg shadow-primary/20 hover:brightness-110 transition-all duration-300 transform active:scale-95 group">
            <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/>
            </svg>
            <span class="text-[10px] font-black uppercase tracking-widest">Volver al Listado</span>
        </a>
    </div>

    <!-- Client Profile Header -->
    <div class="bg-white rounded-[3rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-8 md:p-12 overflow-hidden relative">
        <div class="absolute top-0 right-0 p-8 opacity-5">
            <svg class="w-60 h-60 text-primary" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08s5.97 1.09 6 3.08c-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
        </div>
        
        <div class="md:flex items-center justify-between relative z-10">
            <div class="flex items-center space-x-8">
                <div class="w-28 h-28 bg-gradient-to-tr from-primary via-secondary to-accent rounded-[2.5rem] flex items-center justify-center text-white text-5xl font-black shadow-2xl shadow-primary/30 transform hover:rotate-3 transition-transform duration-300">
                    {{ substr($client->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-4xl md:text-5xl font-black text-slate-900 leading-tight tracking-tighter">
                        {{ $client->name }}
                    </h1>
                    <div class="flex flex-wrap items-center mt-4 gap-3">
                        <span class="inline-flex items-center px-5 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest 
                            {{ $client->status == 'active' ? 'bg-accent-teal/10 text-accent-teal border border-accent-teal/20' : ($client->status == 'libre' ? 'bg-slate-100 text-slate-500 border border-slate-200' : ($client->status == 'not_interested' ? 'bg-slate-200 text-slate-600 border border-slate-300' : 'bg-primary/10 text-primary border border-primary/20')) }}">
                            @if($client->status == 'libre')
                                Libre
                            @elseif($client->status == 'not_interested')
                                No interesado
                            @else
                                {{ ucfirst($client->status) }}
                            @endif
                        </span>
                        <span class="text-slate-400 font-bold text-sm flex items-center bg-slate-50 px-4 py-2 rounded-2xl border border-slate-100">
                            <svg class="w-4 h-4 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            {{ $client->company ?? 'Independiente' }}
                            @if($client->rubro)
                                <span class="mx-3 text-slate-300">|</span>
                                <span class="text-slate-500">{{ $client->rubro }}</span>
                            @endif
                        </span>
                    </div>

                    <!-- WhatsApp Template Selector -->
                    <div class="mt-6 flex items-center space-x-3 bg-emerald-50/50 p-2 rounded-2xl border border-emerald-100 max-w-xs" x-data="{ templateId: '' }">
                        <div class="flex-1">
                            <select x-model="templateId" class="w-full bg-transparent border-none text-emerald-700 text-xs font-black uppercase tracking-tight focus:ring-0 cursor-pointer">
                                <option value="">Enviar Mensaje...</option>
                                @foreach($whatsappTemplates as $template)
                                    <option value="{{ $template->id }}">{{ $template->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button 
                            x-show="templateId"
                            @click="$wire.sendWhatsapp(templateId)"
                            class="bg-emerald-500 hover:bg-emerald-600 text-white p-2 rounded-xl transition-all shadow-lg shadow-emerald-500/20 transform active:scale-90"
                            title="Enviar por WhatsApp"
                        >
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="mt-8 md:mt-0 flex flex-wrap gap-4">
                <button wire:click="openEditModal" class="px-8 py-4 border-2 border-slate-100 rounded-[1.5rem] text-sm font-black text-slate-600 bg-white hover:bg-slate-50 hover:border-slate-200 transition-all duration-300 transform active:scale-95 shadow-sm">
                    Editar Perfil
                </button>
                <button wire:click="$dispatch('open-call-modal')" class="px-10 py-4 bg-gradient-to-r from-primary to-secondary text-white rounded-[1.5rem] shadow-xl shadow-primary/30 text-sm font-black uppercase tracking-widest hover:brightness-110 transition-all duration-300 transform active:scale-95">
                    Registrar Llamada
                </button>
            </div>
        </div>

        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('open-whatsapp', (event) => {
                    window.open(event.url, '_blank');
                });
            });
        </script>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mt-12 pt-10 border-t border-slate-50">
            <div class="bg-slate-50/50 p-6 rounded-[2rem] border border-slate-100/50 transition-colors hover:bg-slate-50">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 flex items-center">
                    <svg class="w-3 h-3 mr-1.5 text-accent" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                    Email Contatco
                </p>
                <p class="text-slate-900 font-bold break-all">{{ $client->email ?? 'N/A' }}</p>
            </div>
            <div class="bg-slate-50/50 p-6 rounded-[2rem] border border-slate-100/50 transition-colors hover:bg-slate-50">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 flex items-center">
                    <svg class="w-3 h-3 mr-1.5 text-accent-teal" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 004.87 4.87l.774-1.548a1 1 0 011.06-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                    Teléfono
                </p>
                <p class="text-slate-900 font-bold">{{ $client->phone }}</p>
            </div>
            <div class="bg-slate-50/50 p-6 rounded-[2rem] border border-slate-100/50 transition-colors hover:bg-slate-50">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 flex items-center">
                    <svg class="w-3 h-3 mr-1.5 text-primary" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 00-1-1H6zm3 3V3H7v2h2zm2 0V3h2v2h-2zM4 6h12v10H4V6zm2 2a1 1 0 000 2h1a1 1 0 000-2H6z" clip-rule="evenodd"/></svg>
                    Cliente Desde
                </p>
                <p class="text-slate-900 font-bold">{{ $client->created_at->format('M d, Y') }}</p>
            </div>
            <div class="bg-slate-50/50 p-6 rounded-[2rem] border border-slate-100/50 transition-colors hover:bg-slate-50">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 flex items-center">
                    <svg class="w-3 h-3 mr-1.5 text-secondary" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                    Agente Responsable
                </p>
                <p class="text-secondary font-black">{{ $client->user->name ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Credit Management Section -->
    @if($client->gasSales()->where('status', 'pending')->exists())
    <div class="bg-amber-50 rounded-[3rem] border border-amber-100 shadow-xl shadow-amber-200/20 overflow-hidden">
        <div class="p-8 md:p-10 border-b border-amber-100 flex justify-between items-center">
            <div>
                <h3 class="text-2xl font-black text-amber-900 uppercase tracking-tight">Gestión de Crédito</h3>
                <p class="text-amber-700/60 text-[10px] font-bold uppercase tracking-widest mt-1">Cuentas por cobrar acumuladas</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-1">Deuda Total</p>
                <p class="text-3xl font-black text-amber-700">S/ {{ number_format($client->gasSales()->where('status', 'pending')->get()->sum(fn($s) => $s->amount - $s->paid_amount), 2) }}</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-amber-100/50">
                    <tr>
                        <th class="px-8 py-4 text-[9px] font-black uppercase text-amber-700">Fecha</th>
                        <th class="px-8 py-4 text-[9px] font-black uppercase text-amber-700">Producto</th>
                        <th class="px-8 py-4 text-[9px] font-black uppercase text-amber-700 text-center">Monto</th>
                        <th class="px-8 py-4 text-[9px] font-black uppercase text-amber-700 text-center">Deuda</th>
                        <th class="px-8 py-4 text-[9px] font-black uppercase text-amber-700 text-right">Abonar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-100/50">
                    @foreach($client->gasSales()->where('status', 'pending')->latest()->get() as $sale)
                        <tr class="hover:bg-amber-100/20 transition-colors">
                            <td class="px-8 py-5">
                                <div class="font-bold text-amber-900">{{ $sale->created_at->format('d/m/Y') }}</div>
                                <div class="text-[9px] text-amber-600">Sesión #{{ $sale->sales_session_id }}</div>
                            </td>
                            <td class="px-8 py-5 uppercase text-xs font-bold text-amber-800">{{ $sale->cylinder_type }} x{{ $sale->quantity }}</td>
                            <td class="px-8 py-5 font-bold text-amber-900 text-center">S/ {{ number_format($sale->amount, 2) }}</td>
                            <td class="px-8 py-5 font-black text-rose-600 text-center">S/ {{ number_format($sale->amount - $sale->paid_amount, 2) }}</td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <input type="number" wire:model="payment_increment" step="0.01" placeholder="0.00" class="w-20 bg-white border-amber-200 rounded-xl text-xs font-bold p-2 focus:ring-amber-500 focus:border-amber-500">
                                    <button wire:click="addPayment({{ $sale->id }})" class="bg-amber-500 hover:bg-amber-600 text-white p-2 rounded-xl transition-all shadow-lg shadow-amber-500/20" title="Registrar Abono">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                    <button wire:click="markAsPaid({{ $sale->id }})" class="bg-emerald-500 hover:bg-emerald-600 text-white p-2 rounded-xl transition-all shadow-lg shadow-emerald-500/20" title="Liquidar Total">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif


    <!-- Interactions Timeline -->
    <div class="space-y-8">
        <h3 class="text-3xl font-black text-slate-900 tracking-tight ml-4">Historial de <span class="bg-clip-text text-transparent bg-gradient-to-r from-primary to-accent">Interacciones</span></h3>
        
        <div class="relative pl-10 ml-4 border-l-2 border-slate-100">
            <div class="space-y-12">
                @forelse($calls as $call)
                <div class="relative">
                    <!-- Timeline dot -->
                    <div class="absolute -left-[3.05rem] top-2 w-8 h-8 rounded-2xl border-4 border-white flex items-center justify-center shadow-lg transition-transform hover:scale-110
                        {{ $call->type == 'inbound' ? 'bg-primary shadow-primary/30' : 'bg-slate-900 shadow-slate-900/30' }}">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($call->type == 'inbound')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            @endif
                        </svg>
                    </div>
                    
                    <!-- Content Card -->
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-500 group relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-slate-50 rounded-bl-[5rem] -mr-16 -mt-16 group-hover:bg-primary/5 transition-colors duration-500"></div>
                        
                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 relative z-10">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="text-sm font-black text-slate-900 uppercase tracking-widest">
                                    Llamada de {{ $call->type == 'inbound' ? 'Entrada' : 'Salida' }}
                                </span>
                                <span class="px-4 py-1.5 bg-slate-100 rounded-xl text-[10px] font-black uppercase text-slate-500 tracking-widest border border-slate-200/50">
                                    {{ $call->duration }} min
                                </span>
                                @if($call->next_call_at)
                                <span class="px-4 py-1.5 bg-accent/10 text-accent rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center shadow-sm border border-accent/20">
                                    <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/></svg>
                                    Próxima Cita: {{ $call->next_call_at->format('d/m H:i') }}
                                </span>
                                @endif
                            </div>
                            <span class="text-xs font-bold text-slate-400 italic mt-2 md:mt-0">
                                {{ $call->called_at->format('d M, Y - H:i') }}
                            </span>
                        </div>
                        
                        <div class="bg-slate-50/50 rounded-3xl p-6 mb-6 border border-slate-100 group-hover:bg-white group-hover:border-slate-200 transition-all duration-500">
                            <p class="text-slate-600 leading-relaxed text-sm italic">
                                "{{ $call->observations ?? 'Sin observaciones adicionales registradas.' }}"
                            </p>

                            @if($call->recording)
                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <div class="flex items-center space-x-3 mb-2">
                                    <div class="p-1.5 bg-indigo-50 rounded-lg">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Grabación ({{ $call->recording->duration }})</span>
                                </div>
                                <div class="flex items-center space-x-2 w-full">
                                    <audio controls class="flex-1 h-10 rounded-2xl outline-none shadow-sm bg-slate-50">
                                        <source src="{{ route('call-recordings.playback', $call->recording->id) }}">
                                        Tu navegador no soporta el elemento de audio.
                                    </audio>
                                    <a href="{{ route('call-recordings.playback', $call->recording->id) }}" download class="p-2.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" title="Descargar grabación">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <div class="flex items-center justify-between pt-2 relative z-10">
                            <div class="flex items-center space-x-3">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Resultado:</span>
                                @php
                                    $resultClasses = [
                                        'interested' => 'text-accent-teal bg-accent-teal/10 border-accent-teal/20',
                                        'not_interested' => 'text-rose-600 bg-rose-50 border-rose-100',
                                        'pending' => 'text-amber-600 bg-amber-50 border-amber-100',
                                        'closed' => 'text-primary bg-primary/10 border-primary/20',
                                    ];
                                    $class = $resultClasses[$call->result] ?? 'text-slate-600 bg-slate-50 border-slate-100';
                                @endphp
                                <span class="px-5 py-2 border rounded-xl text-[10px] font-black uppercase tracking-widest {{ $class }}">
                                    {{ str_replace('_', ' ', $call->result) }}
                                </span>
                            </div>
                            <button class="opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-4 group-hover:translate-x-0 text-xs font-black uppercase tracking-widest text-primary hover:text-secondary flex items-center">
                                Detalles <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-indigo-50 rounded-3xl p-10 text-center border-2 border-dashed border-indigo-100">
                    <p class="text-indigo-400 font-bold italic tracking-tight">No hay llamadas registradas para este cliente.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    @livewire('calls.log-modal', ['clientId' => $client->id])

    <!-- Edit Client Modal -->
    @if($showEditModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 flex items-center justify-between border-b border-slate-50">
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Editar <span class="text-indigo-600">Cliente</span></h3>
                <button wire:click="$set('showEditModal', false)" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="updateClient" class="p-8 space-y-5">
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nombre Completo *</label>
                    <input type="text" wire:model="name" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    @error('name') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Email</label>
                        <input type="email" wire:model="email" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('email') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Teléfono *</label>
                        <input type="text" wire:model="phone" placeholder="987654321" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('phone') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Empresa</label>
                        <input type="text" wire:model="company" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Rubro</label>
                        <input type="text" wire:model="rubro" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Agente Beit</label>
                        <select wire:model="user_id" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Estado</label>
                        <select wire:model="status" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <option value="prospect">Prospecto</option>
                            <option value="active">Activo</option>
                            <option value="libre">Libre</option>
                            <option value="inactive">Inactivo</option>
                            <option value="not_interested">No interesado</option>
                        </select>
                        @error('status') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex space-x-3 pt-4">
                    <button type="button" wire:click="$set('showEditModal', false)" class="flex-1 px-6 py-3 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 hover:bg-slate-50 transition-all">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 px-8 py-3 bg-indigo-600 text-white rounded-2xl shadow-lg shadow-indigo-500/20 text-sm font-bold hover:bg-indigo-700 transition-all">
                        Actualizar Información
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
