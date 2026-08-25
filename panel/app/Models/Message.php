<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'user_id',
        'whatsapp_device_id',
        'message_id',
        'target',
        'message',
        'type',
        'media_url',
        'media_name',
        'media_mimetype',
        'status',
        'error_message',
        'sent_at',
        'delivered_at'
    ];

    public function device()
    {
        return $this->belongsTo(WhatsappDevice::class, 'whatsapp_device_id');
    }
}
