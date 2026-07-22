<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'gas_sale_id',
        'service_id',
        'quantity',
        'price',
        'subtotal',
    ];

    public function sale()
    {
        return $this->belongsTo(GasSale::class, 'gas_sale_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
