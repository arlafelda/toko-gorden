<?php

namespace App\Http\Controllers\Produk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSize;

class ProductController extends Controller
{
    /** 
     * ===============================
     * BAGIAN ADMIN
     * ===============================
     */

    // 🔹 Tampilkan semua produk di halaman admin
    public function index()
    {
        $products = Product::with('sizes')->get();
        return view('Produk.ProdukAdmin', compact('products'));
    }

    // 🔹 Form tambah produk
    public function create()
    {
        return view('Produk.TambahProduk');
    }

    // 🔹 Simpan produk baru beserta ukuran dan stok
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

        $product = new Product([
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'jenis_gorden' => $request->jenis_gorden,
        ]);

        if ($request->hasFile('upload_gambar')) {
            $file = $request->file('upload_gambar');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $product->gambar = $filename;
        }

        $product->save();

        // Simpan ukuran
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

    // 🔹 Form edit produk
    public function edit($id)
    {
        $product = Product::with('sizes')->findOrFail($id);
        return view('Produk.EditProduk', compact('product'));
    }

    // 🔹 Update produk + ukuran
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
            'lebar.*' => 'sometimes|required|numeric',
            'tinggi.*' => 'sometimes|required|numeric',
            'harga_ukuran.*' => 'required|numeric',
            'stok_ukuran.*' => 'required|integer|min:0',
        ]);

        $product->fill([
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'jenis_gorden' => $request->jenis_gorden,
        ]);

        if ($request->hasFile('upload_gambar')) {
            $file = $request->file('upload_gambar');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $product->gambar = $filename;
        }

        $product->save();

        // Hapus ukuran lama
        ProductSize::where('product_id', $product->id)->delete();

        // Tambah ulang ukuran (lama + baru)
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
                $ukuranBaru = "L{$lebar}xT{$tinggi}";
                ProductSize::create([
                    'product_id' => $product->id,
                    'ukuran' => $ukuranBaru,
                    'harga' => $request->harga_ukuran[$startIndex + $i],
                    'stok' => $request->stok_ukuran[$startIndex + $i],
                ]);
            }
        }

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    // 🔹 Hapus produk
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->gambar && file_exists(public_path('uploads/' . $product->gambar))) {
            unlink(public_path('uploads/' . $product->gambar));
        }

        $product->delete();
        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }

    /** 
     * ===============================
     * BAGIAN USER
     * ===============================
     */

    // 🔍 Tampilkan produk dengan fitur pencarian dan filter jenis
public function showUserProducts(Request $request)
{
    $query = Product::with('sizes');
    $search = trim($request->input('q', ''));

    // Case-insensitive search
    if ($search !== '') {
        $lowerSearch = strtolower($search);
        $query->where(function ($q) use ($lowerSearch) {
            $q->whereRaw('LOWER(nama_produk) LIKE ?', ["%{$lowerSearch}%"])
              ->orWhereRaw('LOWER(deskripsi) LIKE ?', ["%{$lowerSearch}%"])
              ->orWhereRaw('LOWER(jenis_gorden) LIKE ?', ["%{$lowerSearch}%"]);
        });
    }

    // Filter jenis (slug → nama jenis)
    if ($request->filled('jenis')) {
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
    $notFound = $products->isEmpty();

    return view('Produk.ProdukUser', compact('products', 'notFound', 'search'));
}

    // 🔹 Detail produk user
    public function show($id)
    {
        $product = Product::with('sizes')->findOrFail($id);
        return view('Produk.DetailProduk', compact('product'));
    }

    /** 
     * ===============================
     * FITUR BELI LANGSUNG
     * ===============================
     */

    public function beliLangsung(Request $request, Product $product)
    {
        $request->validate([
            'ukuran' => 'required',
            'jumlah' => 'required|integer|min:1',
        ]);

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

        $product = Product::with('sizes')->findOrFail($data['product_id']);
        $selectedSize = $product->sizes->where('ukuran', $data['ukuran'])->first();

        return view('Produk.CheckoutLangsung', [
            'product' => $product,
            'size' => $selectedSize,
            'jumlah' => $data['jumlah'],
        ]);
    }

    public function checkoutLangsung(Request $request, Product $product)
    {
        $request->validate([
            'ukuran' => 'required',
            'jumlah' => 'required|integer|min:1',
        ]);

        $selectedSize = $product->sizes()->where('ukuran', $request->ukuran)->first();
        $harga = $selectedSize->harga ?? $product->harga;

        $item = (object)[
            'product' => $product,
            'ukuran' => $request->ukuran,
            'quantity' => $request->jumlah,
            'harga' => $harga,
        ];

        $items = [$item];
        $total = $harga * $item->quantity;

        return view('Produk.Transaksi', compact('items', 'total'));
    }
}
