<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'parent_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function isAssistant()
    {
        return $this->role === 'assistant';
    }

    /**
     * Get IDs of users whose clients this user can manage.
     * Assistants: [Self ID, Parent ID]
     * Managers: [Self ID, ...Children IDs]
     */
    /**
     * Get the ID of the admin who owns the clients for this user.
     */
    public function getOwnerId()
    {
        // Managers always own their own data, regardless of their parent_id
        if ($this->isManager()) {
            return $this->id;
        }

        // Assistants and child super_admins belong to their parent
        if (($this->isAssistant() || $this->isSuperAdmin()) && $this->parent_id && $this->parent_id != 0) {
            return $this->parent_id;
        }

        // Root super_admins own their own data
        return $this->id;
    }

    /**
     * Get all IDs in the same team (the owner and all their descendants).
     */
    public function getTeamUserIds(): array
    {
        $ownerId = $this->getOwnerId();
        
        // If this user is the owner, return their descendants
        if ($ownerId === $this->id) {
            return $this->getDescendantIds();
        }

        // Otherwise, fetch the owner and return their descendants
        $owner = self::find($ownerId);
        if ($owner) {
            return $owner->getDescendantIds();
        }

        return [$this->id];
    }

    /**
     * Get IDs of users whose clients this user can manage.
     * Assistants: Share the same IDs as their parent.
     * Others: [Self ID, ...Descendants IDs]
     */
    public function getManagedUserIds(array &$visited = []): array
    {
        if (in_array($this->id, $visited)) {
            return [];
        }
        $visited[] = $this->id;

        if ($this->isAssistant() && $this->parent) {
            return $this->parent->getManagedUserIds($visited);
        }

        return $this->getDescendantIds($visited);
    }

    /**
     * Internal recursive method to get all child IDs without back-calls to parents.
     */
    public function getDescendantIds(array &$visited = []): array
    {
        if (in_array($this->id, $visited)) {
            return [];
        }
        
        $visited[] = $this->id;
        $ids = [$this->id];

        foreach ($this->children as $child) {
            // Managers are completely isolated, do not traverse into them
            if ($child->isManager()) {
                continue;
            }
            $ids = array_merge($ids, $child->getDescendantIds($visited));
        }

        return array_unique($ids);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
