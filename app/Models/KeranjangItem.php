<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeranjangItem extends Model
{
    protected $table = 'keranjang_items';

    protected $fillable = [
        'keranjang_id',
        'product_id',
        'quantity',
        'ukuran',    // tambahkan ini
        'harga'      // tambahkan ini
    ];

    public function keranjang()
    {
        return $this->belongsTo(Keranjang::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
