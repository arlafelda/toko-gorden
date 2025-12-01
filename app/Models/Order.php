<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'order_date',
        'total',
        'status',
        'payment_type',
        'is_reported',
    ];

    protected $casts = [
        'order_date' => 'datetime',   // supaya bisa pakai format() di Blade
        'total' => 'decimal:2',       // sesuai tipe decimal di database
        'is_reported' => 'boolean',   // untuk status laporan
    ];

    // Relasi ke OrderItem
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
