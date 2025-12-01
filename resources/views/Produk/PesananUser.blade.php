@extends('layouts.Navbar')

@section('title', 'Pesanan - Toko Gorden')

@section('content')

<!-- Main content -->
<main class="px-10 py-8">
    <h1 class="text-2xl font-bold mb-6">Pesanan Saya</h1>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border rounded-xl overflow-hidden">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 font-medium text-gray-900">No</th>
                    <th class="px-4 py-3 font-medium text-gray-900">Tanggal</th>
                    <th class="px-4 py-3 font-medium text-gray-900">Status</th>
                    <th class="px-4 py-3 font-medium text-gray-900">Total</th>
                    <th class="px-4 py-3 font-medium text-gray-900">Jumlah Barang</th>
                    <th class="px-4 py-3 font-medium text-gray-900">Nama Produk</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                <tr class="border-t">
                    <!-- Gunakan $loop->iteration untuk nomor urut -->
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $order->order_date->translatedFormat('d F Y') }}</td>
                    <td>
                        <span class="inline-block bg-gray-100 px-4 py-1 rounded-full capitalize">{{ $order->status }}</span>
                    </td>
                    <td>Rp{{ number_format($order->total,0,',','.') }}</td>
                    <td>{{ $order->items->sum('jumlah') }} barang</td>
                    <td>
                        @foreach($order->items as $item)
                        {{ $item->product->nama_produk ?? 'Produk tidak tersedia' }}<br>
                        @endforeach
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-6 text-gray-500">Belum ada pesanan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>

@endsection

@extends('layouts.Chat')
