<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_session_id',
        'description',
        'amount',
        'category',
    ];

    public function session()
    {
        return $this->belongsTo(SalesSession::class, 'sales_session_id');
    }
}
