<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">CUENTAS TV POR INTERNET</h1>
            <p class="text-slate-500 mt-2 font-medium">Gestiona las cuentas de streaming para tus clientes.</p>
        </div>
        <button wire:click="$set('showCreateModal', true)" class="inline-flex items-center justify-center px-8 py-4 bg-[#043d8a] hover:bg-[#054eb1] text-white text-sm font-black uppercase tracking-widest rounded-3xl transition-all duration-300 shadow-xl shadow-[#043d8a]/20 group">
            <svg class="w-5 h-5 mr-3 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nueva Cuenta
        </button>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($accounts as $account)
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-500 group relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-500/5 to-cyan-500/5 rounded-bl-[5rem] -mr-8 -mt-8 transition-all duration-500 group-hover:scale-150"></div>
                
                <div class="flex justify-between items-start relative z-10 mb-6">
                    <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 21h6l-.75-4M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="px-5 py-2 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] {{ $account->type === 'Premium' ? 'bg-amber-50 text-amber-600 border border-amber-100' : ($account->type === 'Adulto' ? 'bg-rose-50 text-rose-600 border border-rose-100' : 'bg-slate-50 text-slate-600 border border-slate-100') }}">
                        {{ $account->type }}
                    </span>
                </div>
                
                <h3 class="text-xl font-black text-slate-900 mb-6 group-hover:text-[#043d8a] transition-colors">{{ $account->name }}</h3>
                
                <div class="space-y-4 relative z-10">
                    <div class="flex flex-col gap-2">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Contraseña / Clave</span>
                        <div class="flex items-center gap-3 bg-slate-50 px-5 py-4 rounded-2xl border border-slate-100 group-hover:border-indigo-100 transition-colors">
                            <input type="{{ ($showPassword[$account->id] ?? false) ? 'text' : 'password' }}" value="{{ $account->password }}" readonly class="bg-transparent border-none focus:ring-0 text-sm font-bold text-slate-700 w-full p-0">
                            <button wire:click="togglePassword({{ $account->id }})" class="text-slate-400 hover:text-indigo-600 transition-colors">
                                @if($showPassword[$account->id] ?? false)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.539 7.539l3.29 3.29M3 3l18 18"/></svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                @endif
                            </button>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Cuenta de Uso Libre</span>
                        </div>
                        <button wire:click="deleteAccount({{ $account->id }})" wire:confirm="¿Estás seguro de eliminar esta cuenta?" class="p-3 text-slate-300 hover:text-rose-600 hover:bg-rose-50 rounded-2xl transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-6">
            <div class="fixed inset-0 bg-[#043d8a]/40 backdrop-blur-md transition-opacity" wire:click="$set('showCreateModal', false)"></div>
            <div class="bg-white rounded-[3rem] w-full max-w-xl p-12 relative z-10 shadow-2xl border border-slate-100 overflow-hidden transform transition-all">
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50/50 rounded-bl-[10rem] -mr-32 -mt-32"></div>
                
                <h3 class="text-3xl font-black text-slate-900 mb-8 relative z-10">NUEVA CUENTA TV</h3>
                
                <form wire:submit="createAccount" class="space-y-6 relative z-10">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Nombre de la Cuenta</label>
                        <input type="text" wire:model="name" class="w-full bg-slate-50 border-none rounded-3xl px-8 py-5 text-slate-700 font-bold focus:ring-4 focus:ring-indigo-100 transition-all placeholder:text-slate-300" placeholder="Ej: Netflix Premium 01">
                        @error('name') <span class="text-rose-500 text-[10px] font-black uppercase tracking-widest ml-4">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Contraseña / Clave</label>
                        <input type="text" wire:model="password" class="w-full bg-slate-50 border-none rounded-3xl px-8 py-5 text-slate-700 font-bold focus:ring-4 focus:ring-indigo-100 transition-all placeholder:text-slate-300" placeholder="********">
                        @error('password') <span class="text-rose-500 text-[10px] font-black uppercase tracking-widest ml-4">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Tipo</label>
                            <select wire:model="type" class="w-full bg-slate-50 border-none rounded-3xl px-8 py-5 text-slate-700 font-bold focus:ring-4 focus:ring-indigo-100 transition-all appearance-none cursor-pointer">
                                <option value="Premium">Premium</option>
                                <option value="Adulto">Adulto</option>
                                <option value="Otros">Otros</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-8">
                        <button type="button" wire:click="$set('showCreateModal', false)" class="flex-1 py-5 rounded-[2rem] bg-slate-50 text-slate-400 text-[11px] font-black uppercase tracking-widest hover:bg-slate-100 transition-all">
                            Cancelar
                        </button>
                        <button type="submit" class="flex-1 py-5 rounded-[2rem] bg-[#043d8a] text-white text-[11px] font-black uppercase tracking-widest hover:bg-[#054eb1] shadow-xl shadow-[#043d8a]/20 transition-all transform active:scale-95">
                            Crear Ahora
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
