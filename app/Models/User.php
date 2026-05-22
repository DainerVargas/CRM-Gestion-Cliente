<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'parent_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
     * Get IDs of users whose clients this user can manage.
     * Assistants: Share the same IDs as their parent.
     * Others: [Self ID, ...Descendants IDs]
     */
    public function getManagedUserIds(): array
    {
        if ($this->isAssistant() && $this->parent) {
            return $this->parent->getManagedUserIds();
        }

        return $this->getDescendantIds();
    }

    /**
     * Internal recursive method to get all child IDs without back-calls to parents.
     */
    public function getDescendantIds(): array
    {
        $ids = [$this->id];

        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->getDescendantIds());
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
