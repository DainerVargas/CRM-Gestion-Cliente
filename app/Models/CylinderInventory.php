<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CylinderInventory extends Model
{
    use HasFactory;

    protected $table = 'cylinder_inventory';

    protected $fillable = [
        'sales_session_id',
        'cylinder_type',
        'initial_full',
        'initial_empty',
        'final_full',
        'final_empty',
    ];

    public function session()
    {
        return $this->belongsTo(SalesSession::class, 'sales_session_id');
    }
}
