<div class="min-h-screen flex bg-canvas">
    <!-- Left panel (Form) -->
    <div class="flex-1 flex flex-col justify-center py-12 px-6 sm:px-12 lg:flex-none lg:w-[45%] bg-white/70 backdrop-blur-xl border-r border-slate-200/50 shadow-2xl relative z-10">
        <div class="mx-auto w-full max-w-sm">
            <!-- Branding -->
            <div class="text-center lg:text-left mb-10">
                <div class="flex items-center justify-center lg:justify-start mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Yahveh" class="h-20 w-auto object-contain">
                </div>
                <div class="pl-0.5 text-center lg:text-left">
                    <span class="text-[9px] font-sans text-primary tracking-[0.25em] uppercase font-bold">Centro Estético</span>
                    <span class="mx-2 text-slate-300">|</span>
                    <span class="text-[9px] font-sans text-slate-400 tracking-[0.2em] uppercase font-black">CRM Clientes</span>
                </div>
            </div>

            <!-- Login Form -->
            <form wire:submit.prevent="authenticate" class="mt-8 space-y-6">
                <div>
                    <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5 ml-1">Correo Corporativo</label>
                    <div class="relative group">
                        <input wire:model="email" id="email" name="email" type="email" autocomplete="email" required 
                            class="appearance-none block w-full px-6 py-4 border-2 border-slate-100 bg-slate-50/50 text-slate-900 rounded-[1.5rem] placeholder-slate-300 focus:outline-none focus:ring-4 focus:ring-primary/5 focus:border-primary focus:bg-white transition-all duration-300 text-sm font-bold" 
                            placeholder="usuario@yahveh.com">
                    </div>
                    @error('email') <span class="text-rose-500 text-[10px] font-black uppercase mt-2 block ml-1 tracking-wider">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="password" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5 ml-1">Contraseña</label>
                    <div class="relative group">
                        <input wire:model="password" id="password" name="password" type="password" autocomplete="current-password" required 
                            class="appearance-none block w-full px-6 py-4 border-2 border-slate-100 bg-slate-50/50 text-slate-900 rounded-[1.5rem] placeholder-slate-300 focus:outline-none focus:ring-4 focus:ring-primary/5 focus:border-primary focus:bg-white transition-all duration-300 text-sm font-bold" 
                            placeholder="••••••••">
                    </div>
                    @error('password') <span class="text-rose-500 text-[10px] font-black uppercase mt-2 block ml-1 tracking-wider">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-between px-1">
                    <div class="flex items-center">
                        <input wire:model="remember" id="remember-me" name="remember-me" type="checkbox" class="h-5 w-5 text-primary focus:ring-primary/20 border-slate-200 rounded-xl transition-all cursor-pointer">
                        <label for="remember-me" class="ml-3 block text-xs text-slate-500 font-bold cursor-pointer">
                            Recordarme
                        </label>
                    </div>

                    <div class="text-xs">
                        <a href="#" class="font-black text-primary hover:text-indigo-700 transition-colors uppercase tracking-widest">
                            Recuperar
                        </a>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="group relative w-full flex justify-center py-5 px-4 border border-transparent text-xs font-black rounded-[1.5rem] text-white bg-gradient-to-r from-primary to-indigo-700 shadow-2xl shadow-primary/20 focus:outline-none focus:ring-4 focus:ring-primary/20 transition-all duration-300 uppercase tracking-[0.2em] transform active:scale-95 hover:brightness-110">
                        Sincronizar Acceso
                    </button>
                </div>
            </form>

            <!-- Footer Copyright -->
            <div class="mt-12 pt-8 border-t border-slate-100 text-center relative">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white px-4 text-[9px] font-black text-slate-300 uppercase tracking-widest whitespace-nowrap">Seguridad Encriptada</div>
                <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.25em] opacity-60">
                    &copy; {{ date('Y') }} YAHVEH CENTRO ESTÉTICO • SYSTEM CORE
                </p>
            </div>
        </div>
    </div>

    <!-- Right panel (Image / Aesthetic Visuals) -->
    <div class="hidden lg:block relative flex-1">
        <img class="absolute inset-0 h-full w-full object-cover" src="{{ asset('images/yahveh_clinic_spa_bg.png') }}" alt="Yahveh Wellness interior">
        <!-- Golden / Dark Overlay -->
        <div class="absolute inset-0 bg-gradient-to-tr from-secondary/85 via-secondary/70 to-primary/30 mix-blend-multiply"></div>
        <div class="absolute inset-0 flex flex-col justify-end p-20 z-20 text-white">
            <div class="max-w-xl">
                <span class="text-[10px] font-bold text-accent uppercase tracking-[0.3em]">Dermatología Avanzada • Centro Estético</span>
                <h1 class="text-5xl font-serif text-white mt-4 mb-6 leading-tight">Belleza precisa<br>y luminosidad real</h1>
                <p class="text-sm text-slate-300 leading-relaxed max-w-md">
                    Un espacio estético de nueva generación donde la tecnología, el criterio profesional y la sensibilidad visual se unen para crear resultados refinados.
                </p>
            </div>
        </div>
    </div>
</div>
