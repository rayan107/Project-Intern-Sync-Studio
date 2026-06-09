<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = [
        'event_id',
        'name',
        'email',
        'subject',
        'message',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}