<div class="space-y-10">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between px-4">
        <div class="flex-1 min-w-0">
            <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-[#0f172a]">
                Gestión de <span class="text-[#45C2ED] font-black">Usuarios</span>
            </h2>
            <p class="mt-2 text-sm text-slate-500 font-medium tracking-tight">Administración de equipo y accesos del sistema.</p>
        </div>
        <div class="mt-6 flex md:mt-0 md:ml-4">
            <button wire:click="openCreateModal" class="inline-flex items-center px-8 py-4 border border-transparent rounded-[1.5rem] shadow-xl shadow-primary/30 text-xs font-black text-white bg-gradient-to-r from-primary to-secondary hover:brightness-110 transition-all duration-300 uppercase tracking-widest transform active:scale-95">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Nuevo Usuario
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl border-l-4 border-emerald-400 font-bold text-sm">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-rose-50 text-rose-700 p-4 rounded-xl border-l-4 border-rose-400 font-bold text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Users Table Card -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-slate-50/50">
                <tr>
                    <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Nombre</th>
                    <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Email</th>
                    <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Rol</th>
                    <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Fecha Alta</th>
                    <th class="px-8 py-5 text-right text-xs font-black text-slate-400 uppercase tracking-widest">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @foreach($users as $user)
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="px-8 py-5 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 font-black text-xs uppercase">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="ml-4 text-sm font-black text-slate-900">{{ $user->name }}</div>
                        </div>
                    </td>
                    <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-600 font-medium">{{ $user->email }}</td>
                    <td class="px-8 py-5 whitespace-nowrap">
                        @php
                            $roleClasses = [
                                'super_admin' => 'bg-indigo-100 text-indigo-700',
                                'manager' => 'bg-emerald-100 text-emerald-700',
                                'assistant' => 'bg-amber-100 text-amber-700',
                            ];
                            $class = $roleClasses[$user->role] ?? 'bg-slate-100 text-slate-600';
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $class }}">
                            {{ str_replace('_', ' ', $user->role) }}
                        </span>
                    </td>
                    <td class="px-8 py-5 whitespace-nowrap text-sm text-slate-500 font-medium">{{ $user->created_at->format('d/m/Y') }}</td>
                    <td class="px-8 py-5 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            <button wire:click="editUser({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            @if($user->id !== auth()->id())
                            <button wire:click="delete({{ $user->id }})" wire:confirm="¿Estás seguro?" class="text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 p-2 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-8 py-6 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Edit User Modal -->
    @if($showEditModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 flex items-center justify-between border-b border-slate-50">
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Editar <span class="text-indigo-600">Usuario</span></h3>
                <button wire:click="$set('showEditModal', false)" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="updateUser" class="p-8 space-y-5">
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nombre Completo</label>
                    <input type="text" wire:model="name" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    @error('name') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Email</label>
                    <input type="email" wire:model="email" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    @error('email') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Contraseña (dejar en blanco para no cambiar)</label>
                    <input type="password" wire:model="password" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    @error('password') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Rol / Función</label>
                    <select wire:model="role" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all appearance-none">
                        @if(auth()->user()->isSuperAdmin())
                        <option value="super_admin">Super Administrador</option>
                        <option value="manager">Maneja su propia gestión (Manager)</option>
                        @endif
                        <option value="assistant">Administra mi gestión (Asistente)</option>
                    </select>
                </div>

                <div class="flex space-x-3 pt-4">
                    <button type="button" wire:click="$set('showEditModal', false)" class="flex-1 px-6 py-3 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 hover:bg-slate-50 transition-all">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 px-8 py-3 bg-indigo-600 text-white rounded-2xl shadow-lg shadow-indigo-500/20 text-sm font-bold hover:bg-indigo-700 transition-all uppercase tracking-widest">
                        Actualizar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Create User Modal -->
    @if($showCreateModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 flex items-center justify-between border-b border-slate-50">
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Nuevo <span class="text-indigo-600">Usuario</span></h3>
                <button wire:click="$set('showCreateModal', false)" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="save" class="p-8 space-y-5">
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nombre Completo</label>
                    <input type="text" wire:model="name" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    @error('name') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Email</label>
                    <input type="email" wire:model="email" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    @error('email') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Contraseña</label>
                    <input type="password" wire:model="password" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    @error('password') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Rol / Función</label>
                    <select wire:model="role" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all appearance-none">
                        @if(auth()->user()->isSuperAdmin())
                        <option value="manager">Maneja su propia gestión (Manager)</option>
                        @endif
                        <option value="assistant">Administra mi gestión (Asistente)</option>
                    </select>
                    <p class="mt-2 text-[10px] text-slate-400 font-medium leading-tight px-1">
                        * Un Manager tendrá sus propios clientes. Un Asistente compartirá tu lista de clientes para ayudarte.
                    </p>
                    @error('role') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex space-x-3 pt-4">
                    <button type="button" wire:click="$set('showCreateModal', false)" class="flex-1 px-6 py-3 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 hover:bg-slate-50 transition-all">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 px-8 py-3 bg-indigo-600 text-white rounded-2xl shadow-lg shadow-indigo-500/20 text-sm font-bold hover:bg-indigo-700 transition-all uppercase tracking-widest">
                        Crear Cuenta
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
