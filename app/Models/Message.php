<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Message extends Model
{
    use HasFactory;

    // Aktifkan timestamps (created_at, updated_at)
    public $timestamps = true;

    // Kolom yang bisa diisi
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
    ];

    // Relasi ke user pengirim
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relasi ke user penerima
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
