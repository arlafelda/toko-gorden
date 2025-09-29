<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminLaporanController extends Controller
{
    // Halaman utama laporan
    public function index(Request $request)
    {
        // Ambil tanggal dari request, default: 30 hari terakhir
        $startDate = $request->input('start_date', Carbon::now()->subDays(30));
        $endDate = $request->input('end_date', Carbon::now());

        // Total pendapatan keseluruhan
        $totalPendapatan = Order::sum('total');

        // Total order dalam rentang tanggal
        $jumlahOrder = Order::whereBetween('created_at', [$startDate, $endDate])->count();

        // Jumlah pelanggan terdaftar
        $jumlahPelanggan = User::count();

        return view('Admin.Laporan', compact(
            'totalPendapatan',
            'jumlahOrder',
            'jumlahPelanggan',
            'startDate',
            'endDate'
        ));
    }

    // Endpoint AJAX: grafik pendapatan harian
    public function pendapatanHarian(Request $request)
    {
        $tanggal = $request->input('tanggal'); // format: Y-m-d
        $end = Carbon::parse($tanggal);
        $start = $end->copy()->subDays(6); // ambil 7 hari ke belakang

        // Ambil pendapatan berdasarkan tanggal
        $pendapatan = DB::table('orders')
            ->selectRaw('DATE(created_at) as tanggal, SUM(total) as total')
            ->whereBetween('created_at', [$start->startOfDay(), $end->endOfDay()])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        // Buat data labels dan data untuk chart
        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $hari = $end->copy()->subDays($i)->toDateString(); // contoh: 2024-07-14
            $labels[] = Carbon::parse($hari)->translatedFormat('d M'); // contoh: 14 Jul
            $data[] = $pendapatan[$hari]->total ?? 0; // isi 0 jika tidak ada pendapatan
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
