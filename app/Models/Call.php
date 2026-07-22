<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id',
        'called_at',
        'type',
        'duration',
        'observations',
        'result',
        'next_call_at',
        'notified',
        'notified_2h',
        'notified_5m'
    ];

    protected $casts = [
        'called_at' => 'datetime',
        'next_call_at' => 'datetime',
        'notified' => 'boolean',
        'notified_2h' => 'boolean',
        'notified_5m' => 'boolean'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recording()
    {
        return $this->hasOne(CallRecording::class);
    }
}
