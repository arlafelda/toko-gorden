<?php

namespace App\Http\Controllers\Produk;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\KeranjangItem;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    // Tambah produk ke keranjang
    public function tambah(Request $request, $productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Validasi input ukuran dan jumlah
        $request->validate([
            'ukuran' => 'required|string',
            'jumlah' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $product = Product::findOrFail($productId);

        // Cari ukuran produk
        $size = ProductSize::where('product_id', $productId)
            ->where('ukuran', $request->ukuran)
            ->first();

        if (!$size) {
            return back()->with('error', 'Ukuran produk tidak ditemukan.');
        }

        // Buat atau ambil keranjang user
        $keranjang = Keranjang::firstOrCreate(['user_id' => $user->id]);

        // Cari item di keranjang berdasarkan produk dan ukuran
        $item = $keranjang->items()
            ->where('product_id', $productId)
            ->where('ukuran', $request->ukuran)
            ->first();

        if ($item) {
            $item->increment('quantity', $request->jumlah);
        } else {
            $keranjang->items()->create([
                'product_id' => $productId,
                'ukuran' => $request->ukuran,
                'harga' => $size->harga,
                'quantity' => $request->jumlah,
            ]);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    // Tampilkan isi keranjang
    public function tampil()
    {
        $user = Auth::user();

        // Ambil keranjang milik user beserta item dan produk
        $keranjang = Keranjang::with('items.product')->where('user_id', $user->id)->first();

        // Jika tidak ada keranjang atau item, buat koleksi kosong
        $keranjangItems = $keranjang && $keranjang->items ? $keranjang->items : collect();

        // Tambahkan informasi stok ke setiap item
        foreach ($keranjangItems as $item) {
            $size = ProductSize::where('product_id', $item->product_id)
                ->where('ukuran', $item->ukuran)
                ->first();
            $item->stok = $size ? $size->stok : 0;
        }

        return view('User.keranjang', compact('keranjang', 'keranjangItems'));
    }

public function hapus($itemId)
    {
        $userId = Auth::id();

        // Ambil item dengan relasi keranjang dan product, pastikan milik user login
        $item = KeranjangItem::with(['keranjang', 'product'])
            ->whereHas('keranjang', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->findOrFail($itemId);

        // Optional: validasi jika produk tidak ditemukan
        if (!$item->product) {
            return back()->with('error', 'Produk tidak ditemukan.');
        }

        $item->delete();

        return back()->with('success', 'Produk "' . $item->product->nama . '" berhasil dihapus dari keranjang.');
    }


}
