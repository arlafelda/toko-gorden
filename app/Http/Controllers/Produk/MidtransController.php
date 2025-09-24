<?php

namespace App\Http\Controllers\Produk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KeranjangItem;
use App\Models\Order;
use App\Models\OrderItem;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MidtransController extends Controller
{
    /**
     * Halaman checkout
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $itemIds = $request->items;

        if (!$itemIds || !is_array($itemIds)) {
            return redirect()->route('keranjang.tampil')->with('error', 'Silakan pilih produk terlebih dahulu.');
        }

        $items = KeranjangItem::with(['product', 'keranjang'])
            ->whereIn('id', $itemIds)
            ->get()
            ->filter(function ($item) {
                return $item->keranjang && $item->keranjang->user_id === Auth::id();
            });

        if ($items->isEmpty()) {
            return redirect()->route('keranjang.tampil')->with('error', 'Tidak ada item yang valid.');
        }

        $total = 0;
        foreach ($items as $item) {
            $harga = $item->harga ?? $item->product->harga;
            $total += $harga * $item->quantity;
        }

        return view('Produk.Transaksi', [
            'items' => $items,
            'total' => $total,
            'user'  => Auth::user(),
        ]);
    }

    /**
     * Ambil Snap Token dari Midtrans
     */
    public function getSnapToken(Request $request)
    {
        $amount = (int) $request->input('amount');

        if ($amount <= 0) {
            return response()->json(['error' => 'Jumlah pembayaran tidak valid'], 422);
        }

        Config::$serverKey    = config('midtrans.server_key');
        Config::$clientKey    = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.sanitized');
        Config::$is3ds        = config('midtrans.3ds');

        $user = Auth::user();
        $orderId = uniqid('ORDER-');

        $order = Order::create([
            'order_id'     => $orderId,
            'user_id'      => $user->id,
            'order_date'   => now(),
            'total'        => $amount,
            'status'       => 'pending',
            'payment_type' => null,
        ]);

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
                'phone'      => $user->phone_number ?? '08123456789',
                'billing_address' => [
                    'address' => $user->address ?? 'Alamat tidak tersedia',
                ],
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Gagal mendapatkan Snap Token Midtrans: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mendapatkan Snap Token'], 500);
        }
    }

    /**
     * Callback dari Midtrans (dipanggil otomatis)
     */
    public function callback(Request $request)
{
    try {
        $payload = $request->all();

        Log::info('Midtrans Callback Diterima', $payload);

        $orderId = $payload['order_id'] ?? null;
        $status  = $payload['transaction_status'] ?? 'pending';
        $type    = $payload['payment_type'] ?? 'unknown';

        $order = Order::where('order_id', $orderId)->first();

        if (!$order) {
            Log::warning("Order tidak ditemukan: $orderId");
            return response()->json(['error' => 'Order tidak ditemukan'], 404);
        }

        DB::beginTransaction();

        $order->update([
            'status'       => $status,
            'payment_type' => $type,
        ]);

        // Jika pembayaran berhasil dan item belum dibuat
        if (in_array($status, ['settlement', 'capture']) && $order->items()->count() === 0) {

            $userId = $order->user_id;

            // Ambil item keranjang milik user
            $keranjangItems = KeranjangItem::with(['product', 'keranjang'])
                ->whereHas('keranjang', fn($q) => $q->where('user_id', $userId))
                ->get();

            Log::info("Item keranjang ditemukan: {$keranjangItems->count()} item.");

            foreach ($keranjangItems as $item) {
                $produkId = $item->product_id ?? $item->produk_id; // disesuaikan dengan struktur
                $harga    = $item->harga ?? $item->product->harga;

                OrderItem::create([
                    'order_id'  => $order->id,
                    'produk_id' => $produkId,
                    'ukuran'    => $item->ukuran,
                    'jumlah'    => $item->quantity,
                    'harga'     => (int) $harga,
                ]);
            }

            // Bersihkan keranjang user
            KeranjangItem::whereHas('keranjang', fn($q) => $q->where('user_id', $userId))->delete();
        }

        DB::commit();

        return response()->json(['message' => 'Pesanan berhasil diproses']);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Midtrans Callback Error: ' . $e->getMessage());
        return response()->json(['error' => 'Terjadi kesalahan saat memproses pesanan'], 500);
    }
}
/**
 * Simulasi callback untuk testing
 * Hanya digunakan untuk development/testing, jangan digunakan di production
 */
public function callbackSimulasi(Request $request)
{
    try {
        $payload = $request->all();

        Log::info('Midtrans Callback Diterima (Simulasi)', $payload);

        $orderId = $payload['order_id'] ?? null;
        $status  = $payload['transaction_status'] ?? 'pending';
        $type    = $payload['payment_type'] ?? 'unknown';

        $order = Order::where('order_id', $orderId)->first();

        if (!$order) {
            Log::warning("Order tidak ditemukan: $orderId");
            return response()->json(['error' => 'Order tidak ditemukan'], 404);
        }

        DB::beginTransaction();

        $order->update([
            'status'       => $status,
            'payment_type' => $type,
        ]);

        if (in_array($status, ['settlement', 'capture']) && $order->items()->count() === 0) {
            $cartItems = \App\Models\KeranjangItem::with(['product', 'keranjang'])
                ->whereHas('keranjang', fn($q) => $q->where('user_id', $order->user_id))
                ->get();

            Log::info("Item keranjang ditemukan: {$cartItems->count()} item.");

            foreach ($cartItems as $item) {
                if (!$item->product_id || !$item->product) {
                    Log::warning("Item keranjang tidak valid", [
                        'item_id'    => $item->id,
                        'product_id' => $item->product_id,
                    ]);
                    continue;
                }

                Log::info("Menyimpan OrderItem", [
                    'order_id'   => $order->id,
                    'produk_id'  => $item->product_id,
                    'ukuran'     => $item->ukuran,
                    'jumlah'     => $item->quantity,
                    'harga'      => (int) ($item->harga ?? $item->product->harga),
                ]);

                OrderItem::create([
                    'order_id'  => $order->id,
                    'produk_id' => $item->product_id,
                    'ukuran'    => $item->ukuran,
                    'jumlah'    => $item->quantity,
                    'harga'     => (int) ($item->harga ?? $item->product->harga),
                ]);
            }

            \App\Models\KeranjangItem::whereHas('keranjang', fn($q) => $q->where('user_id', $order->user_id))->delete();
        }

        DB::commit();
        return response()->json(['message' => 'Pesanan berhasil diproses (Simulasi)']);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Midtrans Callback Error (Simulasi): ' . $e->getMessage());
        return response()->json(['error' => 'Terjadi kesalahan saat memproses pesanan'], 500);
    }
}
public function bayarLangsung(Request $request)
{
    // Proses pembayaran langsung di sini
    // Validasi, ambil data produk, dll.
    // Redirect ke halaman pembayaran atau tampilkan notifikasi sukses
    return back()->with('success', 'Pembayaran berhasil diproses (dummy).');
}
}
