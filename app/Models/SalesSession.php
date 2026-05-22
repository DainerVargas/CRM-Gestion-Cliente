<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'starting_cash',
        'closing_cash',
        'status',
        'user_id',
    ];

    public function gasSales()
    {
        return $this->hasMany(GasSale::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function inventories()
    {
        return $this->hasMany(CylinderInventory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
