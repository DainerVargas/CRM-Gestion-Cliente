<div class="space-y-10">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between px-4">
        <div class="flex-1 min-w-0">
            <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-[#0f172a]">
                Gestión de <span class="text-primary font-black">Servicios</span>
            </h2>
            <p class="mt-2 text-sm text-slate-500 font-medium tracking-tight">Administra los servicios y tratamientos que ofreces a tus clientes.</p>
        </div>
        <div class="mt-6 flex md:mt-0 md:ml-4">
            <button wire:click="openCreateModal" class="inline-flex items-center px-8 py-4 border border-transparent rounded-[1.5rem] shadow-xl shadow-primary/30 text-xs font-black text-white bg-gradient-to-r from-primary to-secondary hover:brightness-110 transition-all duration-300 uppercase tracking-widest transform active:scale-95">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Nuevo Servicio
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl border-l-4 border-emerald-400 font-bold text-sm mx-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="px-4">
        <div class="relative max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-3 border-transparent bg-white shadow-sm rounded-xl text-sm placeholder-slate-400 focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Buscar por nombre...">
        </div>
    </div>

    <!-- Services Table Card -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden mx-4">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Servicio</th>
                        <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Precio</th>
                        <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Estado</th>
                        <th class="px-8 py-5 text-right text-xs font-black text-slate-400 uppercase tracking-widest">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($services as $service)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="text-sm font-black text-slate-900">{{ $service->name }}</div>
                            @if($service->description)
                            <div class="text-xs text-slate-500 font-medium mt-1">{{ Str::limit($service->description, 60) }}</div>
                            @endif
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap">
                            <div class="text-sm font-black text-emerald-600">S/ {{ number_format($service->price, 2) }}</div>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap">
                            <button wire:click="toggleActive({{ $service->id }})" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest transition-colors {{ $service->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                {{ $service->is_active ? 'Activo' : 'Inactivo' }}
                            </button>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <button wire:click="editService({{ $service->id }})" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button wire:click="delete({{ $service->id }})" wire:confirm="¿Estás seguro de eliminar este servicio?" class="text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 p-2 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-10 text-center text-slate-400 font-medium">No hay servicios creados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-8 py-6 border-t border-slate-100">
            {{ $services->links() }}
        </div>
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 flex items-center justify-between border-b border-slate-50">
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Nuevo <span class="text-primary">Servicio</span></h3>
                <button wire:click="$set('showCreateModal', false)" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="save" class="p-8 space-y-5">
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nombre del Servicio</label>
                    <input type="text" wire:model="name" placeholder="Ej: Bótox, Limpieza Facial..." class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-primary focus:bg-white transition-all">
                    @error('name') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Precio (S/)</label>
                    <input type="number" step="0.01" wire:model="price" placeholder="0.00" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-primary focus:bg-white transition-all">
                    @error('price') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Descripción (Opcional)</label>
                    <textarea wire:model="description" rows="3" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-primary focus:bg-white transition-all resize-none" placeholder="Breve descripción del tratamiento..."></textarea>
                    @error('description') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="flex items-center mt-4">
                    <input type="checkbox" wire:model="is_active" id="is_active" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm font-bold text-slate-700">
                        Servicio Activo
                    </label>
                </div>

                <div class="flex space-x-3 pt-4">
                    <button type="button" wire:click="$set('showCreateModal', false)" class="flex-1 px-6 py-3 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 hover:bg-slate-50 transition-all">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 px-8 py-3 bg-gradient-to-r from-primary to-secondary text-white rounded-2xl shadow-lg shadow-primary/20 text-sm font-bold hover:brightness-110 transition-all uppercase tracking-widest">
                        Crear Servicio
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Edit Modal -->
    @if($showEditModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 flex items-center justify-between border-b border-slate-50">
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Editar <span class="text-primary">Servicio</span></h3>
                <button wire:click="$set('showEditModal', false)" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="updateService" class="p-8 space-y-5">
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nombre del Servicio</label>
                    <input type="text" wire:model="name" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-primary focus:bg-white transition-all">
                    @error('name') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Precio (S/)</label>
                    <input type="number" step="0.01" wire:model="price" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-primary focus:bg-white transition-all">
                    @error('price') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Descripción (Opcional)</label>
                    <textarea wire:model="description" rows="3" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-primary focus:bg-white transition-all resize-none"></textarea>
                    @error('description') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="flex items-center mt-4">
                    <input type="checkbox" wire:model="is_active" id="edit_is_active" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                    <label for="edit_is_active" class="ml-2 block text-sm font-bold text-slate-700">
                        Servicio Activo
                    </label>
                </div>

                <div class="flex space-x-3 pt-4">
                    <button type="button" wire:click="$set('showEditModal', false)" class="flex-1 px-6 py-3 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 hover:bg-slate-50 transition-all">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 px-8 py-3 bg-gradient-to-r from-primary to-secondary text-white rounded-2xl shadow-lg shadow-primary/20 text-sm font-bold hover:brightness-110 transition-all uppercase tracking-widest">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
