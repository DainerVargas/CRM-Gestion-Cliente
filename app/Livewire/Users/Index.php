<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;

class Index extends Component
{
    use WithPagination;

    public $name, $email, $password, $role = 'manager';
    public $showCreateModal = false, $showEditModal = false, $userId;
    public $showDeleteConfirmModal = false, $userIdToDelete, $userNameToDelete;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8',
        'role' => 'required|in:manager,assistant',
    ];

    public function openCreateModal()
    {
        $this->reset(['name', 'email', 'password', 'role', 'userId', 'showEditModal']);
        
        if (auth()->user()->isManager()) {
            $this->role = 'assistant';
        }

        $this->showCreateModal = true;
    }

    public function save()
    {
        $this->validate();

        $currentUser = auth()->user();

        // Validations for roles
        // Only super_admin or managers can create users
        if (!$currentUser->isSuperAdmin() && !$currentUser->isManager()) {
            session()->flash('error', 'No tienes permiso para crear usuarios.');
            return;
        }

        // Managers can ONLY create Assistants. Only Super Admin can create Managers or Assistants.
        if ($currentUser->isManager() && $this->role !== 'assistant') {
            session()->flash('error', 'Como Manager, solo puedes crear Asistentes para tu gestión.');
            return;
        }

        $parentId = $currentUser->id;
        if ($currentUser->isSuperAdmin() && $this->role === 'assistant') {
            $parentId = $currentUser->getOwnerId();
        }

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'parent_id' => $parentId,
        ]);

        $this->showCreateModal = false;
        session()->flash('message', 'Usuario creado correctamente.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);

        if (!auth()->user()->isSuperAdmin() && $user->parent_id !== auth()->id()) {
            session()->flash('error', 'No tienes permiso para editar este usuario.');
            return;
        }

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = ''; 
        $this->showEditModal = true;
    }

    public function updateUser()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role' => 'required|in:super_admin,manager,assistant',
        ]);

        $user = User::findOrFail($this->userId);
        $currentUser = auth()->user();

        if (!$currentUser->isSuperAdmin() && $user->parent_id !== $currentUser->id) {
            session()->flash('error', 'No tienes permiso.');
            return;
        }

        // Managers cannot elevate roles to super_admin or manager easily if they are assistants
        if ($currentUser->isManager() && $this->role !== 'assistant') {
             session()->flash('error', 'Como Manager sólo puedes gestionar Asistentes.');
             return;
        }

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);
        $this->showEditModal = false;
        session()->flash('message', 'Usuario actualizado correctamente.');
    }

    public function confirmDelete($id)
    {
        if ($id === auth()->id()) {
            session()->flash('error', 'No puedes eliminarte a ti mismo.');
            return;
        }

        $user = User::findOrFail($id);
        $this->userIdToDelete = $user->id;
        $this->userNameToDelete = $user->name;
        $this->showDeleteConfirmModal = true;
    }

    public function cancelDelete()
    {
        $this->reset(['showDeleteConfirmModal', 'userIdToDelete', 'userNameToDelete']);
    }

    public function delete($id = null)
    {
        $id = $id ?? $this->userIdToDelete;
        if (!$id) {
            return;
        }

        if ($id === auth()->id()) {
            session()->flash('error', 'No puedes eliminarte a ti mismo.');
            $this->cancelDelete();
            return;
        }

        $user = User::find($id);
        
        // Check permissions to delete
        if ($user && !auth()->user()->isSuperAdmin() && $user->parent_id !== auth()->id()) {
            session()->flash('error', 'No tienes permiso para eliminar este usuario.');
            $this->cancelDelete();
            return;
        }

        if ($user) {
            $user->delete();
            session()->flash('message', 'Usuario eliminado. Sus registros relacionados (como clientes y llamadas) permanecen en el sistema para referencia histórica, pero el usuario ya no tiene acceso.');
        }

        $this->cancelDelete();
    }

    public function render()
    {
        $currentUser = auth()->user();
        $query = User::query();

        if (!$currentUser->isSuperAdmin()) {
            // Managers and assistants see only their team members
            $teamIds = $currentUser->getTeamUserIds();
            $query->whereIn('id', array_diff($teamIds, [$currentUser->id]));
        }

        return view('livewire.users.index', [
            'users' => $query->latest()->paginate(10)
        ])->layout('layouts.app');
    }
}
