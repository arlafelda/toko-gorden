<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminLaporanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil bulan dari filter
        $bulan = $request->get('bulan', now()->format('Y-m')); // default bulan ini

        // Pisah tahun dan bulan
        [$tahun, $bulanNum] = explode('-', $bulan);
        // ambil order sesuai bulan dan status selesai
        $orders = Order::with('orderItems', 'user')
            ->where('status', 'selesai')
            ->whereYear('order_date', $tahun)
            ->whereMonth('order_date', $bulanNum)
            ->get();

        // total pendapatan
        $totalPendapatan = $orders->sum('total');

        return view('Admin.Laporan', compact('orders', 'bulan', 'totalPendapatan'));
    }
}
