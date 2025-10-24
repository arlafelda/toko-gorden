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

    /**
     * Menampilkan detail pesanan.
     */
    public function show($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);

        return view('Admin.DetailPemesanan', compact('order'));
    }

    /**
     * Memperbarui status pesanan.
     */
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

    /**
     * Menambahkan pesanan ke laporan penjualan (hanya untuk pesanan yang sudah selesai).
     */
    public function addToReport($id)
    {
        $order = Order::findOrFail($id);

        // Pastikan status saat ini adalah "selesai"
        if ($order->status !== 'selesai') {
            return redirect()->back()->with('error', 'Hanya pesanan dengan status "selesai" yang dapat ditambahkan ke laporan.');
        }

        // Pesanan sudah selesai, tidak perlu mengubah status lagi
        // Pesanan akan muncul di laporan berdasarkan status "selesai"

        // Redirect ke halaman laporan dengan bulan pesanan tersebut
        $bulan = $order->order_date->format('Y-m');
        return redirect()->route('admin.laporan', ['bulan' => $bulan])->with('success', 'Pesanan berhasil ditambahkan ke laporan penjualan.');
    }
}
