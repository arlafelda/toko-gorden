<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSize extends Model
{
    use HasFactory;

    protected $table = 'product_sizes'; // Nama tabel di database

    protected $fillable = [
        'product_id',
        'ukuran',
        'harga',
        'stok',
    ];

    // Relasi ke produk
    // app/Models/ProductSize.php
public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}

}
