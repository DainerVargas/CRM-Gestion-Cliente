<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GasSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_session_id',
        'client_id',
        'service_id',
        'client_name_manual',
        'cylinder_type',
        'expiry_date',
        'quantity',
        'amount',
        'paid_amount',
        'payment_method',
        'status',
        'notes',
        'expiration_notified',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function session()
    {
        return $this->belongsTo(SalesSession::class, 'sales_session_id');
    }

    public function payments()
    {
        return $this->hasMany(SalePayment::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class, 'gas_sale_id');
    }
}
