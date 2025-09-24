<?php

namespace App\Http\Controllers\Produk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSize;

class ProductController extends Controller
{
    // Tampilkan semua produk admin
    public function index()
    {
        $products = Product::with('sizes')->get();
        return view('Produk.ProdukAdmin', compact('products'));
    }

    // Tampilkan form tambah produk
    public function create()
    {
        return view('Produk.TambahProduk');
    }

    // Simpan produk baru + ukuran + stok per ukuran
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric',
            'upload_gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jenis_gorden' => 'nullable|string|max:255',
            'lebar.*' => 'required|numeric|min:0',
            'tinggi.*' => 'required|numeric|min:0',
            'harga_ukuran.*' => 'required|numeric',
            'stok_ukuran.*' => 'required|integer|min:0',
        ]);

        $product = new Product();
        $product->nama_produk = $request->nama_produk;
        $product->deskripsi = $request->deskripsi;
        $product->harga = $request->harga;
        $product->jenis_gorden = $request->jenis_gorden;

        if ($request->hasFile('upload_gambar')) {
            $file = $request->file('upload_gambar');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $product->gambar = $filename;
        }

        $product->save();

        foreach ($request->lebar as $i => $lebar) {
            $tinggi = $request->tinggi[$i];
            $ukuran = "L{$lebar}xT{$tinggi}";
            ProductSize::create([
                'product_id' => $product->id,
                'ukuran' => $ukuran,
                'harga' => $request->harga_ukuran[$i],
                'stok' => $request->stok_ukuran[$i],
            ]);
        }

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    // Tampilkan form edit produk
    public function edit($id)
    {
        $product = Product::with('sizes')->findOrFail($id);
        return view('Produk.EditProduk', compact('product'));
    }

    // Update produk + ukuran + stok per ukuran
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric',
            'upload_gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jenis_gorden' => 'nullable|string|max:255',
            'ukuran.*' => 'sometimes|required|string',
            'lebar.*' => 'sometimes|required|integer',
            'tinggi.*' => 'sometimes|required|integer',
            'harga_ukuran.*' => 'required|numeric',
            'stok_ukuran.*' => 'required|integer|min:0',
        ]);

        $product->nama_produk = $request->nama_produk;
        $product->deskripsi = $request->deskripsi;
        $product->harga = $request->harga;
        $product->jenis_gorden = $request->jenis_gorden;

        if ($request->hasFile('upload_gambar')) {
            $file = $request->file('upload_gambar');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $product->gambar = $filename;
        }

        $product->save();

        ProductSize::where('product_id', $product->id)->delete();

        if ($request->has('ukuran')) {
            foreach ($request->ukuran as $index => $ukuran) {
                ProductSize::create([
                    'product_id' => $product->id,
                    'ukuran' => $ukuran,
                    'harga' => $request->harga_ukuran[$index],
                    'stok' => $request->stok_ukuran[$index],
                ]);
            }
        }

        if ($request->has('lebar') && $request->has('tinggi')) {
            $startIndex = $request->has('ukuran') ? count($request->ukuran) : 0;

            foreach ($request->lebar as $i => $lebar) {
                $tinggi = $request->tinggi[$i];
                $ukuranBaru = 'L' . $lebar . 'xT' . $tinggi;

                ProductSize::create([
                    'product_id' => $product->id,
                    'ukuran' => $ukuranBaru,
                    'harga' => $request->harga_ukuran[$startIndex + $i],
                    'stok' => $request->stok_ukuran[$startIndex + $i],
                ]);
            }
        }

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui');
    }

    // Hapus produk
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->gambar && file_exists(public_path('uploads/' . $product->gambar))) {
            unlink(public_path('uploads/' . $product->gambar));
        }
        $product->delete();
        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus');
    }

    // ✅ Tampilkan produk untuk user dengan filter pencarian dan jenis
    public function showUserProducts(Request $request)
    {
        $query = Product::with('sizes');

        // Filter pencarian
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%')
                    ->orWhere('jenis_gorden', 'like', '%' . $search . '%');
            });
        }

        // Filter jenis gorden atau aksesoris
        if ($request->has('jenis')) {
            $slug = $request->input('jenis');

            $jenisMap = [
                'gorden-vertikal' => 'Gorden Vertikal',
                'gorden-natural' => 'Gorden Natural',
                'gorden-roll-blind' => 'Gorden Roll Blind',
                'gorden-slimbin' => 'Gorden Slimbin',
                'gorden-wooden-blind' => 'Gorden Wooden Blind',
                'aksesoris-rel-gorden' => 'Aksesoris Rel Gorden',
                'aksesoris-pengait-tali' => 'Aksesoris Pengait/Tali',
                'aksesoris-bracket' => 'Aksesoris Bracket',
                'aksesoris-hook' => 'Aksesoris Hook',
            ];

            if (isset($jenisMap[$slug])) {
                $query->where('jenis_gorden', $jenisMap[$slug]);
            }
        }

        $products = $query->get();
        return view('Produk.ProdukUser', compact('products'));
    }

    // Tampilkan detail produk
    public function show($id)
    {
        $product = Product::with('sizes')->findOrFail($id);
        return view('Produk.DetailProduk', compact('product'));
    }

    // public function landingPage()
    // {
    //     $produkTerbaru = Product::latest()->take(8)->get();
    //     return view('User.LandingPage', compact('produkTerbaru'));
    // }

// BELI LANGSUNG CONTROLLER
    public function beliLangsung(Request $request, \App\Models\Product $product)
    {
        $request->validate([
            'ukuran' => 'required',
            'jumlah' => 'required|integer|min:1',
        ]);
        // Simpan data ke session sementara
        session([
            'beli_langsung' => [
                'product_id' => $product->id,
                'ukuran' => $request->ukuran,
                'jumlah' => $request->jumlah,
            ]
        ]);
        return redirect()->route('checkout.beliLangsung');
    }

    public function checkoutBeliLangsung()
    {
        $data = session('beli_langsung');
        if (!$data) {
            return redirect()->route('produk.user')->with('error', 'Tidak ada produk yang dipilih.');
        }
        $product = \App\Models\Product::with('sizes')->findOrFail($data['product_id']);
        $selectedSize = $product->sizes->where('ukuran', $data['ukuran'])->first();
        return view('Produk.CheckoutLangsung', [
            'product' => $product,
            'size' => $selectedSize,
            'jumlah' => $data['jumlah'],
        ]);
    }

    public function checkoutLangsung(Request $request, \App\Models\Product $product)
    {
        $request->validate([
            'ukuran' => 'required',
            'jumlah' => 'required|integer|min:1',
        ]);

        // Buat data item seperti dari keranjang
        $item = (object)[
            'product' => $product,
            'ukuran' => $request->ukuran,
            'quantity' => $request->jumlah,
            'harga' => $product->sizes()->where('ukuran', $request->ukuran)->first()->harga ?? $product->harga,
        ];

        $items = [$item];
        $total = $item->harga * $item->quantity;

        // Kirim ke view transaksi
        return view('Produk.Transaksi', compact('items', 'total'));
    }
}
