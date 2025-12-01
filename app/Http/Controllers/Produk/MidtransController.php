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
            ->whereHas('keranjang', fn($q) => $q->where('user_id', Auth::id()))
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('keranjang.tampil')->with('error', 'Tidak ada item yang valid.');
        }

        $total = $items->sum(function ($item) {
            return ($item->harga ?? $item->product->harga) * $item->quantity;
        });

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

        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $user = Auth::user();
        $orderId = uniqid('ORDER-');

        $order = Order::create([
            'order_id' => $orderId,
            'user_id'  => $user->id,
            'order_date' => now(),
            'total'    => $amount,
            'status'   => 'pending',
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
            ]
        ];

        try {
            return response()->json(['token' => Snap::getSnapToken($params)]);
        } catch (\Exception $e) {
            Log::error('Midtrans Error: '.$e->getMessage());
            return response()->json(['error' => 'Gagal dapat Token'], 500);
        }
    }

    /**
     * Callback dari Midtrans
     */
    public function callback(Request $request)
    {
        try {
            $orderId = $request->order_id;
            $status  = $request->transaction_status;

            $order = Order::where('order_id', $orderId)->first();

            if (!$order) return response()->json(['error' => 'Order tidak ditemukan'], 404);

            DB::beginTransaction();

            $order->update([
                'status'       => $status,
                'payment_type' => $request->payment_type ?? 'unknown',
            ]);

            if (in_array($status, ['settlement', 'capture']) && $order->items()->count() === 0) {

                $items = KeranjangItem::with('product')
                    ->whereHas('keranjang', fn($q) => $q->where('user_id', $order->user_id))
                    ->get();

                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id'  => $order->id,
                        'produk_id' => $item->product_id,
                        'ukuran'    => $item->ukuran,
                        'quantity'  => $item->quantity,  // sesuai DB
                        'price'     => $item->harga ?? $item->product->harga, // sesuai DB
                    ]);
                }

                KeranjangItem::whereHas('keranjang', fn($q) => $q->where('user_id', $order->user_id))->delete();
            }

            DB::commit();
            return response()->json(['message' => 'Pesanan berhasil diproses']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Callback Error: ".$e->getMessage());
            return response()->json(['error' => 'Terjadi Kesalahan'], 500);
        }
    }

    /**
     * Simulasi callback untuk testing
     */
    public function callbackSimulasi(Request $request)
    {
        return $this->callback($request);
    }

    public function bayarLangsung()
    {
        return back()->with('success', 'Pembayaran berhasil diproses (dummy).');
    }
}
