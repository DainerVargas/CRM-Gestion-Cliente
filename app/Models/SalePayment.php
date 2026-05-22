<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'gas_sale_id',
        'sales_session_id',
        'amount',
        'payment_method',
        'notes',
    ];

    public function sale()
    {
        return $this->belongsTo(GasSale::class, 'gas_sale_id');
    }

    public function session()
    {
        return $this->belongsTo(SalesSession::class, 'sales_session_id');
    }
}
