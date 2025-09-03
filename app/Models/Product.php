<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Jika nama tabel bukan 'products', tulis manual:
    // protected $table = 'products';

    // Field yang boleh diisi secara mass-assignment
    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'harga',
        'stok',
        'gambar',
        'jenis_gorden',
    ];

    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
}
