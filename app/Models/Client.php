<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'rubro',
        'status',
        'status_changed_by',
        'user_id',
        'next_billing_date'
    ];

    protected $casts = [
        'next_billing_date' => 'date',
    ];

    public function calls()
    {
        return $this->hasMany(Call::class);
    }

    public function latestCall()
    {
        return $this->hasOne(Call::class)->latestOfMany();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gasSales()
    {
        return $this->hasMany(GasSale::class);
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = preg_replace('/[^0-9]/', '', $value);
    }
}
