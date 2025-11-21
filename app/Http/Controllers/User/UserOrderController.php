<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class UserOrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth::user();
        $orders = Order::with('items.product')
            ->where('user_id', $user->id)
            ->orderBy('order_date', 'desc')
            ->get();

        return view('Produk.PesananUser', compact('orders'));
    }
}
