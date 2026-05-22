<div class="space-y-10 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-[#0f172a]">
                Mi <span class="text-[#45C2ED] font-black">Perfil</span>
            </h2>
            <p class="mt-2 text-sm text-slate-500 font-medium tracking-tight">Gestiona tu información personal y seguridad de acceso.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-50 border-l-4 border-emerald-400 p-4 rounded-xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-bold text-emerald-800">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Profile Info Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 bg-slate-50 border-b border-slate-100">
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">Información de Cuenta</h3>
                </div>
                <form wire:submit.prevent="updateProfile" class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nombre Completo</label>
                            <input type="text" wire:model="name" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            @error('name') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Email de Acceso</label>
                            <input type="email" wire:model="email" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            @error('email') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-2xl shadow-lg shadow-indigo-500/20 text-sm font-black hover:bg-indigo-700 transition-all uppercase tracking-widest">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Security Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 bg-slate-50 border-b border-slate-100">
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">Seguridad</h3>
                </div>
                <form wire:submit.prevent="updatePassword" class="p-8 space-y-5">
                    @if (session()->has('password_message'))
                        <div class="mb-4 text-emerald-600 text-xs font-bold">{{ session('password_message') }}</div>
                    @endif
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Contraseña Actual</label>
                        <input type="password" wire:model="current_password" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('current_password') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nueva Contraseña</label>
                        <input type="password" wire:model="new_password" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('new_password') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Confirmar</label>
                        <input type="password" wire:model="new_password_confirmation" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>

                    <button type="submit" class="w-full py-3 bg-slate-900 text-white rounded-2xl shadow-lg border border-transparent text-sm font-black hover:bg-slate-800 transition-all uppercase tracking-widest">
                        Actualizar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
