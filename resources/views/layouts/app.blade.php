<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CRM Pro') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-white.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-white.png') }}">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
    
    <style>
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full overflow-hidden">
    <div class="flex h-full" x-data="{ mobileMenuOpen: false }">
        <!-- Backdrop Mobile -->
        <div 
            x-cloak
            x-show="mobileMenuOpen" 
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="mobileMenuOpen = false"
            class="fixed inset-0 bg-secondary/60 backdrop-blur-sm z-40 md:hidden"
        ></div>

        <!-- Sidebar Mobile -->
        <aside 
            x-cloak
            x-show="mobileMenuOpen"
            x-transition:enter="transition ease-in-out duration-300 transform"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 w-72 bg-secondary border-r border-slate-800/30 z-50 md:hidden flex flex-col overflow-y-auto"
        >
            <div class="flex-shrink-0 flex items-center justify-between px-6 py-5 border-b border-white/5">
                <a href="{{ route('dashboard') }}" class="flex items-center select-none">
                    <img src="{{ asset('images/logo.png') }}" alt="Yahveh" class="h-10 w-auto object-contain brightness-0 invert">
                </a>
                <button @click="mobileMenuOpen = false" class="p-2 text-white hover:bg-white/10 rounded-xl transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <nav class="flex-1 px-3 space-y-1.5 py-4 uppercase">
                <a href="{{ route('dashboard') }}" class="group relative flex items-center px-4 py-2.5 text-xs font-black rounded-[0.75rem] transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary scale-[1.02] border-l-[4px] border-primary' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="mr-2 h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-slate-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('clients.index') }}" class="group relative flex items-center px-4 py-2.5 text-xs font-black rounded-[0.75rem] transition-all duration-300 {{ request()->routeIs('clients.*') ? 'bg-primary/10 text-primary scale-[1.02] border-l-[4px] border-primary' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="mr-2 h-5 w-5 {{ request()->routeIs('clients.*') ? 'text-primary' : 'text-slate-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Clientes
                </a>
                <a href="{{ route('admin.sales.settlement') }}" class="group relative flex items-center px-4 py-2.5 text-xs font-black rounded-[0.75rem] transition-all duration-300 {{ request()->routeIs('admin.sales.*') ? 'bg-primary/10 text-primary scale-[1.02] border-l-[4px] border-primary' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="mr-2 h-5 w-5 {{ request()->routeIs('admin.sales.*') ? 'text-primary' : 'text-slate-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Ventas y Cierre
                </a>
                <a href="{{ route('calls.index') }}" class="group relative flex items-center px-4 py-2.5 text-xs font-black rounded-[0.75rem] transition-all duration-300 {{ request()->routeIs('calls.*') ? 'bg-primary/10 text-primary scale-[1.02] border-l-[4px] border-primary' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="mr-2 h-5 w-5 {{ request()->routeIs('calls.*') ? 'text-primary' : 'text-slate-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    Llamadas
                </a>
                <a href="{{ route('whatsapp-templates.index') }}" class="group relative flex items-center px-4 py-2.5 text-xs font-black rounded-[0.75rem] transition-all duration-300 {{ request()->routeIs('whatsapp-templates.*') ? 'bg-primary/10 text-primary scale-[1.02] border-l-[4px] border-primary' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="mr-2 h-5 w-5 {{ request()->routeIs('whatsapp-templates.*') ? 'text-primary' : 'text-slate-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    Mensajes
                </a>
                <a href="{{ route('services.index') }}" class="group relative flex items-center px-4 py-2.5 text-xs font-black rounded-[0.75rem] transition-all duration-300 {{ request()->routeIs('services.*') ? 'bg-primary/10 text-primary scale-[1.02] border-l-[4px] border-primary' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="mr-2 h-5 w-5 {{ request()->routeIs('services.*') ? 'text-primary' : 'text-slate-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Servicios
                </a>
                @if(!auth()->user()->isAssistant())
                <a href="{{ route('users.index') }}" class="group relative flex items-center px-4 py-2.5 text-xs font-black rounded-[0.75rem] transition-all duration-300 {{ request()->routeIs('users.*') ? 'bg-primary/10 text-primary scale-[1.02] border-l-[4px] border-primary' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="mr-2 h-5 w-5 {{ request()->routeIs('users.*') ? 'text-primary' : 'text-slate-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Usuarios
                </a>
                @endif
                <a href="{{ route('profile.show') }}" class="group relative flex items-center px-4 py-2.5 text-xs font-black rounded-[0.75rem] transition-all duration-300 {{ request()->routeIs('profile.*') ? 'bg-primary/10 text-primary scale-[1.02] border-l-[4px] border-primary' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="mr-2 h-5 w-5 {{ request()->routeIs('profile.*') ? 'text-primary' : 'text-slate-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Mi Perfil
                </a>
            </nav>
            
            <div class="p-6 mt-auto">
                <div class="bg-slate-900/40 rounded-[2.5rem] p-6 border border-white/5 backdrop-blur-md shadow-2xl">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 bg-primary rounded-2xl flex items-center justify-center shadow-lg shadow-primary/20 hover:scale-105 transition-transform">
                                <span class="text-secondary font-black text-xl uppercase font-black">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-sm font-black text-white leading-none truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-slate-300 mt-1 uppercase font-bold tracking-widest truncate">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full py-3 px-4 bg-slate-800 hover:bg-primary/20 hover:text-primary text-white text-[11px] font-black uppercase tracking-widest rounded-2xl transition-all duration-300 shadow-lg shadow-black/20">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Sidebar Desktop -->
        <aside class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0 bg-secondary border-r border-slate-800/30">
            <div class="flex-1 flex flex-col min-h-0">
            <div class="flex-grow flex flex-col pt-5 pb-4 overflow-y-auto">
                <div class="flex items-center flex-shrink-0 px-8 py-5 border-b border-white/5 mb-4 justify-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center justify-center select-none w-full">
                        <img src="{{ asset('images/logo.png') }}" alt="Yahveh" class="h-12 w-auto object-contain brightness-0 invert">
                    </a>
                </div>
                
                <nav class="flex-1 px-3 space-y-1.5 uppercase">
                    <a href="{{ route('dashboard') }}" class="group relative flex items-center px-4 py-2.5 text-xs font-black rounded-[0.75rem] transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary scale-[1.02] border-l-[4px] border-primary' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="mr-2 h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-slate-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('clients.index') }}" class="group relative flex items-center px-4 py-2.5 text-xs font-black rounded-[0.75rem] transition-all duration-300 {{ request()->routeIs('clients.*') ? 'bg-primary/10 text-primary scale-[1.02] border-l-[4px] border-primary' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="mr-2 h-5 w-5 {{ request()->routeIs('clients.*') ? 'text-primary' : 'text-slate-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Clientes
                    </a>


                    <a href="{{ route('admin.sales.settlement') }}" class="group relative flex items-center px-4 py-2.5 text-xs font-black rounded-[0.75rem] transition-all duration-300 {{ request()->routeIs('admin.sales.*') ? 'bg-primary/10 text-primary scale-[1.02] border-l-[4px] border-primary' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="mr-2 h-5 w-5 {{ request()->routeIs('admin.sales.*') ? 'text-primary' : 'text-slate-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Ventas y Cierre
                    </a>

                    <a href="{{ route('calls.index') }}" class="group relative flex items-center px-4 py-2.5 text-xs font-black rounded-[0.75rem] transition-all duration-300 {{ request()->routeIs('calls.*') ? 'bg-primary/10 text-primary scale-[1.02] border-l-[4px] border-primary' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="mr-2 h-5 w-5 {{ request()->routeIs('calls.*') ? 'text-primary' : 'text-slate-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        Llamadas
                    </a>

                    <a href="{{ route('whatsapp-templates.index') }}" class="group relative flex items-center px-4 py-2.5 text-xs font-black rounded-[0.75rem] transition-all duration-300 {{ request()->routeIs('whatsapp-templates.*') ? 'bg-primary/10 text-primary scale-[1.02] border-l-[4px] border-primary' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="mr-2 h-5 w-5 {{ request()->routeIs('whatsapp-templates.*') ? 'text-primary' : 'text-slate-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        Mensajes
                    </a>
                    <a href="{{ route('services.index') }}" class="group relative flex items-center px-4 py-2.5 text-xs font-black rounded-[0.75rem] transition-all duration-300 {{ request()->routeIs('services.*') ? 'bg-primary/10 text-primary scale-[1.02] border-l-[4px] border-primary' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="mr-2 h-5 w-5 {{ request()->routeIs('services.*') ? 'text-primary' : 'text-slate-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Servicios
                    </a>
                    @if(!auth()->user()->isAssistant())
                    <a href="{{ route('users.index') }}" class="group relative flex items-center px-4 py-2.5 text-xs font-black rounded-[0.75rem] transition-all duration-300 {{ request()->routeIs('users.*') ? 'bg-primary/10 text-primary scale-[1.02] border-l-[4px] border-primary' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="mr-2 h-5 w-5 {{ request()->routeIs('users.*') ? 'text-primary' : 'text-slate-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Usuarios
                    </a>
                    @endif

                    <a href="{{ route('profile.show') }}" class="group relative flex items-center px-4 py-2.5 text-xs font-black rounded-[0.75rem] transition-all duration-300 {{ request()->routeIs('profile.*') ? 'bg-primary/10 text-primary scale-[1.02] border-l-[4px] border-primary' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="mr-2 h-5 w-5 {{ request()->routeIs('profile.*') ? 'text-primary' : 'text-slate-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Mi Perfil
                    </a>
                </nav>
                
                <div class="p-6 mt-auto">
                    <div class="bg-slate-900/40 rounded-[2.5rem] p-6 border border-white/5 backdrop-blur-md shadow-2xl">
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 bg-primary rounded-2xl flex items-center justify-center shadow-lg shadow-primary/20 hover:scale-105 transition-transform">
                                    <span class="text-secondary font-black text-xl uppercase font-black">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-sm font-black text-white leading-none truncate">{{ auth()->user()->name }}</p>
                                <p class="text-[10px] text-slate-300 mt-1 uppercase font-bold tracking-widest truncate">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full py-3 px-4 bg-slate-800 hover:bg-primary/20 hover:text-primary text-white text-[11px] font-black uppercase tracking-widest rounded-2xl transition-all duration-300 shadow-lg shadow-black/20">
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 md:pl-64 flex flex-col h-full bg-slate-50 overflow-hidden">
            <!-- Topbar (Mobile Header) -->
            <header class="h-20 flex items-center justify-between px-8 bg-canvas/80 backdrop-blur-md border-b border-slate-200/50 md:hidden sticky top-0 z-40">
                <div class="flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Yahveh" class="h-10 w-auto object-contain">
                </div>
                <button @click="mobileMenuOpen = true" class="p-3 text-slate-400 hover:bg-slate-50 hover:text-primary rounded-2xl transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" /></svg>
                </button>
            </header>


            <!-- Page Content -->
            <div class="flex-1 overflow-y-auto p-6 md:p-8 lg:p-10">
                <livewire:notification-checker />
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
