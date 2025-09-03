<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Menampilkan daftar semua pesanan.
     */
    public function index()
    {
        // Ambil semua order dengan relasi user dan item
        $orders = Order::with(['user', 'items'])->latest()->get();

        return view('Produk.PesananAdmin', compact('orders'));
    }
}
