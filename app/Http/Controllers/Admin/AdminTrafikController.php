<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class AdminTrafikController extends Controller
{
    public function index()
    {
        $totalPengunjung = User::count();
        $pengunjungHariIni = User::whereDate('created_at', Carbon::today())->count();
        $pengunjungMingguIni = User::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $pengunjungBulanIni = User::whereMonth('created_at', Carbon::now()->month)
                                   ->whereYear('created_at', Carbon::now()->year)
                                   ->count();

        return view('Admin.Trafik', compact(
            'totalPengunjung',
            'pengunjungHariIni',
            'pengunjungMingguIni',
            'pengunjungBulanIni'
        ));
    }

    public function data()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $count = User::whereDate('created_at', $date)->count();
            $data[] = [
                'date' => $date,
                'count' => $count
            ];
        }

        return response()->json($data);
    }
}
