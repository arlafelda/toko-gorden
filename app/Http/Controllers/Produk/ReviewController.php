<?php

namespace App\Http\Controllers\Produk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        // Pastikan user login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk memberikan ulasan.');
        }

        // Validasi input
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'required|string|max:1000',
        ]);

        // Simpan ulasan
        Review::create([
            'product_id' => $validated['product_id'],
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'ulasan' => $validated['ulasan'],
        ]);

        return back()->with('success', 'Ulasan berhasil dikirim.');
    }
}
