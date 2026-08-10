<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    // 'read_at' bilerek dışarıda: yalnızca admin tarafında güncellenir.
    protected $fillable = ['name', 'email', 'phone', 'subject', 'message', 'locale'];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function scopeUnread($q)
    {
        return $q->whereNull('read_at');
    }
}
