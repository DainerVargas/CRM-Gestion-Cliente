<div class="min-h-screen flex items-center justify-center bg-canvas py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute -top-20 -left-20 w-96 h-96 bg-primary/10 rounded-full blur-3xl opacity-50 capitalize"></div>
    <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-accent/10 rounded-full blur-3xl opacity-50 capitalize"></div>

    <div class="max-w-md w-full space-y-10 bg-white/80 backdrop-blur-xl p-12 rounded-[4rem] shadow-2xl shadow-primary/10 border border-white/50 relative z-10 transition-all hover:shadow-primary/15">
        <div>
            <div class="flex justify-center">
                <div class="p-6 bg-gradient-to-br from-primary to-secondary rounded-[2.5rem] flex items-center justify-center shadow-2xl shadow-primary/40 transform hover:scale-105 transition-transform duration-500">
                    <img src="{{ asset('images/logo-white.png') }}" alt="BEIT" class="w-16 h-auto drop-shadow-lg">
                </div>
            </div>
            <h2 class="mt-8 text-center text-4xl font-black text-slate-900 tracking-tighter">
                CRM <span class="bg-clip-text text-transparent bg-gradient-to-r from-primary to-accent">BEIT</span>
            </h2>
            <p class="mt-4 text-center text-xs text-slate-400 font-black uppercase tracking-widest leading-relaxed">
                Plataforma de Gestión Integral
            </p>
        </div>
        
        <form wire:submit.prevent="authenticate" class="mt-8 space-y-6">
            <div class="space-y-6">
                <div>
                    <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Correo Corporativo</label>
                    <div class="relative group">
                        <input wire:model="email" id="email" name="email" type="email" autocomplete="email" required class="appearance-none block w-full px-6 py-4 border-2 border-slate-50 bg-slate-50 text-slate-900 rounded-[1.5rem] placeholder-slate-300 focus:outline-none focus:ring-4 focus:ring-primary/5 focus:border-primary/20 focus:bg-white transition-all duration-300 text-sm font-bold" placeholder="usuario@beit.com">
                    </div>
                    @error('email') <span class="text-rose-500 text-[10px] font-black uppercase mt-2 block ml-1 tracking-wider">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="password" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Contraseña</label>
                    <div class="relative group">
                        <input wire:model="password" id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none block w-full px-6 py-4 border-2 border-slate-50 bg-slate-50 text-slate-900 rounded-[1.5rem] placeholder-slate-300 focus:outline-none focus:ring-4 focus:ring-primary/5 focus:border-primary/20 focus:bg-white transition-all duration-300 text-sm font-bold" placeholder="••••••••">
                    </div>
                    @error('password') <span class="text-rose-500 text-[10px] font-black uppercase mt-2 block ml-1 tracking-wider">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex items-center justify-between px-1">
                <div class="flex items-center">
                    <input wire:model="remember" id="remember-me" name="remember-me" type="checkbox" class="h-5 w-5 text-primary focus:ring-primary/20 border-slate-200 rounded-xl transition-all cursor-pointer">
                    <label for="remember-me" class="ml-3 block text-xs text-slate-500 font-bold cursor-pointer">
                        Recordarme
                    </label>
                </div>

                <div class="text-xs">
                    <a href="#" class="font-black text-primary hover:text-accent transition-colors uppercase tracking-widest">
                        Recuperar
                    </a>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="group relative w-full flex justify-center py-5 px-4 border border-transparent text-xs font-black rounded-[1.5rem] text-white bg-gradient-to-r from-primary to-secondary shadow-2xl shadow-primary/30 focus:outline-none focus:ring-4 focus:ring-primary/20 transition-all duration-300 uppercase tracking-[0.2em] transform active:scale-95 hover:brightness-110">
                    Sincronizar Acceso
                </button>
            </div>
        </form>

        <div class="mt-8 pt-8 border-t border-slate-100 text-center relative">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white px-4 text-[9px] font-black text-slate-300 uppercase tracking-widest whitespace-nowrap">Seguridad Encriptada</div>
            <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.3em] opacity-50">
                &copy; {{ date('Y') }} BEIT PERÚ • SYSTEM CORE
            </p>
        </div>
    </div>
</div>
