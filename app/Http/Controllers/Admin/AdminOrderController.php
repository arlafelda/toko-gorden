<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    // Menampilkan semua pesanan
    public function index()
    {
        $orders = Order::with(['items.product', 'user'])
            ->orderBy('order_date', 'desc') // urut berdasarkan waktu pesanan
            ->get();

        return view('Produk.PesananAdmin', compact('orders'));
    }

    // Menampilkan detail pesanan
    public function show($id)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);
        return view('Admin.DetailPemesanan', compact('order'));
    }

    // Mengubah status pesanan
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pesanan diterima,sedang diproses,selesai',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    // Menambahkan pesanan ke laporan
    public function addToReport($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'selesai') {
            return redirect()->back()->with('error', 'Hanya pesanan dengan status "selesai" yang dapat ditambahkan ke laporan.');
        }

        $order->is_reported = true;
        $order->save();

        $bulan = $order->order_date->format('Y-m');
        return redirect()->route('admin.laporan', ['bulan' => $bulan])
            ->with('success', 'Pesanan berhasil ditambahkan ke laporan penjualan.');
    }
}
