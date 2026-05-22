<div>
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 flex items-center justify-between border-b border-slate-50">
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Nuevo <span class="text-indigo-600">Cliente</span></h3>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="save" class="p-8 space-y-5">
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nombre Completo *</label>
                    <input type="text" wire:model="name" placeholder="Ej: Juan Pérez" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    @error('name') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <div class="flex items-center space-x-2 px-1">
                            <input type="checkbox" wire:model.live="hasEmail" id="hasEmail" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                            <label for="hasEmail" class="text-xs font-black text-slate-400 uppercase tracking-widest cursor-pointer">¿Tiene email?</label>
                        </div>
                        <div x-show="$wire.hasEmail" x-transition>
                            <input type="email" wire:model="email" placeholder="juan@ejemplo.com" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            @error('email') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div x-show="!$wire.hasEmail" class="px-4 py-3 bg-slate-50/50 border-2 border-dashed border-slate-100 rounded-xl text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-tight">
                            Email deshabilitado
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Teléfono *</label>
                        <input type="text" wire:model="phone" placeholder="+34 600 000 000" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('phone') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Empresa</label>
                        <input type="text" wire:model="company" placeholder="Nombre (opcional)" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Rubro</label>
                        <input type="text" wire:model="rubro" placeholder="¿A qué se dedican?" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
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
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Estado Inicial</label>
                        <select wire:model="status" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <option value="libre">Libre</option>
                            <option value="prospect">Prospecto</option>
                            <option value="active">Activo</option>
                            <option value="inactive">Inactivo</option>
                            <option value="not_interested">No interesado</option>
                        </select>
                    </div>
                </div>

                <div class="flex space-x-3 pt-4">
                    <button type="button" wire:click="$set('showModal', false)" class="flex-1 px-6 py-3 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 hover:bg-slate-50 transition-all">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 px-8 py-3 bg-indigo-600 text-white rounded-2xl shadow-lg shadow-indigo-500/20 text-sm font-bold hover:bg-indigo-700 transition-all">
                        Guardar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
