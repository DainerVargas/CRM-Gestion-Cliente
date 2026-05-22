<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallRecording extends Model
{
    protected $fillable = [
        'user_id',
        'call_id',
        'file_path',
        'duration',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function call()
    {
        return $this->belongsTo(Call::class);
    }
}
