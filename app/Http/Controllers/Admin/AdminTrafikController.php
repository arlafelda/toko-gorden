<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use Carbon\Carbon;

class AdminTrafikController extends Controller
{
    public function index()
    {
        // Pengunjung
        $totalPengunjung = User::count();
        $pengunjungHariIni = User::whereDate('created_at', Carbon::today())->count();
        $pengunjungMingguIni = User::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $pengunjungBulanIni = User::whereMonth('created_at', Carbon::now()->month)
                                   ->whereYear('created_at', Carbon::now()->year)
                                   ->count();

        // Penjualan
        $totalPesanan = Order::whereIn('status', ['settlement', 'capture'])->count();
        $pesananHariIni = Order::whereIn('status', ['settlement', 'capture'])
            ->whereDate('created_at', Carbon::today())->count();
        $pesananMingguIni = Order::whereIn('status', ['settlement', 'capture'])
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $pesananBulanIni = Order::whereIn('status', ['settlement', 'capture'])
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        return view('Admin.Trafik', compact(
            'totalPengunjung',
            'pengunjungHariIni',
            'pengunjungMingguIni',
            'pengunjungBulanIni',
            'totalPesanan',
            'pesananHariIni',
            'pesananMingguIni',
            'pesananBulanIni'
        ));
    }

    public function data()
    {
        $pengunjungData = [];
        $penjualanData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();

            // Pengunjung per hari
            $pengunjungCount = User::whereDate('created_at', $date)->count();
            $pengunjungData[] = [
                'date' => $date,
                'count' => $pengunjungCount
            ];

            // Penjualan per hari
            $penjualanCount = Order::whereIn('status', ['settlement', 'capture'])
                ->whereDate('created_at', $date)->count();
            $penjualanData[] = [
                'date' => $date,
                'count' => $penjualanCount
            ];
        }

        return response()->json([
            'pengunjung' => $pengunjungData,
            'penjualan' => $penjualanData
        ]);
    }
}
