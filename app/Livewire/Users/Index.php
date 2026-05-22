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
            $parentId = 1;
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

    public function delete($id)
    {
        if ($id === auth()->id()) {
            session()->flash('error', 'No puedes eliminarte a ti mismo.');
            return;
        }

        $user = User::find($id);
        
        // Check permissions to delete
        if (!auth()->user()->isSuperAdmin() && $user->parent_id !== auth()->id()) {
            session()->flash('error', 'No tienes permiso para eliminar este usuario.');
            return;
        }

        $user->delete();
        session()->flash('message', 'Usuario eliminado.');
    }

    public function render()
    {
        $isSuperAdmin = auth()->user()->isSuperAdmin();
        $query = User::query();

        if ($isSuperAdmin) {
            $query->where(function ($q) {
                $q->where('role', '!=', 'assistant')
                  ->orWhereDoesntHave('parent', function ($sub) {
                      $sub->where('role', 'manager');
                  });
            });
        } else {
            $managedIds = auth()->user()->getManagedUserIds();
            $query->whereIn('id', array_diff($managedIds, [auth()->id()]));
        }

        return view('livewire.users.index', [
            'users' => $query->latest()->paginate(10)
        ])->layout('layouts.app');
    }
}
