<!-- filepath: resources/views/Produk/CheckoutLangsung.blade.php -->
@extends('layouts.Navbar')
@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Checkout Beli Langsung</h1>
    <div class="bg-white rounded shadow p-4">
        <div class="flex items-center gap-4">
            <img src="{{ asset('uploads/' . $product->gambar) }}" class="w-24 h-24 rounded" alt="">
            <div>
                <div class="font-bold">{{ $product->nama_produk }}</div>
                <div>Ukuran: {{ $size->ukuran }}</div>
                <div>Jumlah: {{ $jumlah }}</div>
                <div class="text-green-600 font-bold">Rp{{ number_format($size->harga * $jumlah, 0, ',', '.') }}</div>
            </div>
        </div>
        <!-- Tambahkan form pembayaran atau tombol lanjut ke pembayaran -->
        <form action="{{ route('midtrans.bayarLangsung') }}" method="POST" class="mt-6">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="ukuran" value="{{ $size->ukuran }}">
            <input type="hidden" name="jumlah" value="{{ $jumlah }}">
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Bayar Sekarang</button>
        </form>
    </div>
</div>
@endsection