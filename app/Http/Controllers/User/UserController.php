<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use App\Models\KeranjangItem;


class UserController extends Controller
{
    /**
     * Tampilkan halaman profil user.
     */
    public function showProfile()
    {
        $user = Auth::user(); // Ambil user login
        return view('User.ProfilUser', compact('user'));
    }

    /**
     * Tampilkan halaman edit profil user.
     */
    public function editProfile()
    {
        $user = Auth::user(); // Ambil user login
        return view('User.EditProfile', compact('user'));
    }

    /**
     * Simpan perubahan profil user.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user || !($user instanceof User)) {
            return redirect()->route('login')->withErrors(['error' => 'Silakan login terlebih dahulu.']);
        }

        // Validasi form
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'jalan' => 'required|string|max:255',
            'desa' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
        ]);

        // Gabungkan alamat ke dalam satu string
        $fullAddress = $request->jalan . ', Desa ' . $request->desa . ', Kec. ' . $request->kecamatan . ', ' . $request->kota;

        // Simpan perubahan
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->address = $fullAddress;
        $user->save();

        return redirect()->route('user.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function landingPage()
    {
        $userId = Auth::id();

        $produkTerbaru = Product::latest()->take(5)->get();

        $produkPopuler = Product::withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating')
            ->take(5)
            ->get();

        $jumlahKeranjang = 0;

        if ($userId) {
            $jumlahKeranjang = KeranjangItem::whereHas('keranjang', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->count();
        }

        return view('User.LandingPage', compact('produkTerbaru', 'produkPopuler', 'jumlahKeranjang'));
    }
}
