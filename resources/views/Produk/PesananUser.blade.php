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
                <th class="px-4 py-3 font-medium text-gray-900">Kode</th>
                <th class="px-4 py-3 font-medium text-gray-900">Tanggal</th>
                <th class="px-4 py-3 font-medium text-gray-900">Status</th>
                <th class="px-4 py-3 font-medium text-gray-900">Total</th>
                <th class="px-4 py-3 font-medium text-gray-900">Jumlah Barang</th>
                <th class="px-4 py-3 font-medium text-gray-900">Jenis Gorden</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($orders as $order)
                <tr class="border-t">
                  <td class="px-4 py-3">#{{ $order->kode_pesanan }}</td>
                  <td class="px-4 py-3 text-gray-600">{{ $order->created_at->translatedFormat('d F Y') }}</td>
                  <td class="px-4 py-3">
                    <span class="inline-block bg-gray-100 px-4 py-1 rounded-full capitalize">{{ $order->status }}</span>
                  </td>
                  <td class="px-4 py-3 text-gray-600">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                  <td class="px-4 py-3 text-gray-600">{{ $order->items->sum('kuantitas') }} barang</td>
                  <td class="px-4 py-3 text-gray-600">
                    {{ $order->items->pluck('jenis_gorden')->unique()->join(', ') }}
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

    </div>
  </div>
   @extends('layouts.Chat')
@endsection