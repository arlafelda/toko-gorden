<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminLaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));
        [$tahun, $bulanNum] = explode('-', $bulan);

        $orders = Order::with(['items.product', 'user'])
            ->where('status', 'selesai')
            ->where('is_reported', true)
            ->whereYear('order_date', $tahun)
            ->whereMonth('order_date', $bulanNum)
            ->get();

        $totalPendapatan = $orders->sum(function($order) {
            return $order->items->sum(function($item) {
                return $item->harga * $item->jumlah;
            });
        });

        return view('Admin.Laporan', compact('orders', 'bulan', 'totalPendapatan'));
    }
}
