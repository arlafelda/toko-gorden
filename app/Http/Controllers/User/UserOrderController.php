<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Carbon\Carbon;

class UserOrderController extends Controller
{
    // Menampilkan daftar pesanan user
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $orders = Order::with('items.product')
            ->where('user_id', $user->id)
            ->orderBy('order_date', 'desc')
            ->get();

        return view('Produk.PesananUser', compact('orders'));
    }

    // Membuat pesanan baru saat checkout
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Misal total dihitung dari keranjang atau request
        $total = $request->total;

        $order = new Order();
        $order->user_id = $user->id;
        $order->order_date = Carbon::now(); // tanggal + jam saat ini
        $order->total = $total;
        $order->status = 'pending';
        $order->save();

        return redirect()->route('user.orders.index')
            ->with('success', 'Pesanan berhasil dibuat!');
    }
}
