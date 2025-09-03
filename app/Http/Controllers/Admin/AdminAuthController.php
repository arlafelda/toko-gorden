<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Order;
use Carbon\Carbon;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('Admin.LoginAdmin');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function index()
    {
        // Hitung jumlah pelanggan
        $jumlahPelanggan = User::count();

        // Hitung total pendapatan selama 30 hari terakhir
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();
        $totalPesanan = Order::count();
        $totalPendapatan = Order::whereBetween('created_at', [$startDate, $endDate])->sum('total');

        // Buat data penjualan bulanan
        $labels = [];
        $pendapatanBulanan = [];
        for ($i = 0; $i < 7; $i++) {
            $bulan = Carbon::now()->subMonths(6 - $i);
            $labels[] = $bulan->format('M');
            $total = Order::whereMonth('created_at', $bulan->month)
                          ->whereYear('created_at', $bulan->year)
                          ->sum('total');
            $pendapatanBulanan[] = $total;
        }

        return view('Admin.DashboardAdmin', compact(
            'jumlahPelanggan',
            'totalPendapatan',
            'totalPesanan',
            'labels',
            'pendapatanBulanan'
        ));
    }
}
