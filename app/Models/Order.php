<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\OrderItem;

class Order extends Model
{
    // Jika nama tabel berbeda dari 'orders', gunakan: protected $table = 'nama_tabel';

    protected $fillable = [
        'order_id',
        'user_id',
        'order_date',
        'total',
        'status',
        'payment_type',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'total' => 'integer',
    ];

    /**
     * Relasi ke User (yang melakukan pemesanan)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
