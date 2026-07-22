<div class="space-y-10">
    <!-- Header Section -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-[#0f172a]">
                Dashboard <span class="text-primary font-black">Overview</span>
            </h2>
            <p class="mt-2 text-sm text-slate-500 font-medium">
                Bienvenido de nuevo. Aquí tienes un resumen de la actividad de hoy.
            </p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Total Clients -->
        <div class="relative group bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center group-hover:bg-indigo-600 transition-colors duration-300">
                    <svg class="h-8 w-8 text-indigo-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="flex items-center space-x-1 text-emerald-500 text-sm font-bold bg-emerald-50 px-2 py-1 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 10l7-7 7 7"/></svg>
                    <span>12%</span>
                </div>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Total Clientes</p>
                <div class="flex items-baseline space-x-2">
                    <h3 class="text-4xl font-black text-slate-900 tracking-tight">{{ $totalClients }}</h3>
                </div>
            </div>
        </div>

        <!-- Active Clients -->
        <div class="relative group bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center group-hover:bg-emerald-600 transition-colors duration-300">
                    <svg class="h-8 w-8 text-emerald-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex items-center space-x-1 text-emerald-500 text-sm font-bold bg-emerald-50 px-2 py-1 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 10l7-7 7 7"/></svg>
                    <span>8%</span>
                </div>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Clientes Activos</p>
                <div class="flex items-baseline space-x-2">
                    <h3 class="text-4xl font-black text-slate-900 tracking-tight">{{ $activeClients }}</h3>
                </div>
            </div>
        </div>

        <!-- Total Calls -->
        <div class="relative group bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-purple-500/5 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center group-hover:bg-purple-600 transition-colors duration-300">
                    <svg class="h-8 w-8 text-purple-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <div class="flex items-center space-x-1 text-indigo-500 text-sm font-bold bg-indigo-50 px-2 py-1 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 10l7-7 7 7"/></svg>
                    <span>24</span>
                </div>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Llamadas Totales</p>
                <div class="flex items-baseline space-x-2">
                    <h3 class="text-4xl font-black text-slate-900 tracking-tight">{{ $totalCalls }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-8 py-8 flex items-center justify-between border-b border-slate-100">
            <h3 class="text-xl font-black text-[#0f172a] tracking-tight">Actividad <span class="text-primary">Reciente</span></h3>
            <a href="/llamadas" class="text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors">Ver todas las llamadas</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Cliente</th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Tipo</th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Resultado</th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentCalls as $call)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-8 py-5 whitespace-nowrap text-sm font-bold text-slate-900">
                            {{ $call->client->name }}
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-600">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $call->type == 'inbound' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-700' }}">
                                {{ ucfirst($call->type) }}
                            </span>
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
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-black uppercase tracking-wider {{ $currentClass }}">
                                {{ str_replace('_', ' ', $call->result) }}
                            </span>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-400 font-medium tracking-tight">
                            {{ $call->called_at->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-10 text-center text-slate-400 italic">
                            No hay actividad reciente registrada.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
