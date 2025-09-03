<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Daftar Pesanan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
</head>

<body class="bg-white font-sans text-gray-800">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 h-screen w-64 bg-white p-4 border-r border-gray-200 z-10">
      <h1 class="text-xl font-semibold text-gray-900 mb-6">Admin Panel</h1>
      <nav class="flex flex-col gap-2">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-gray-100">
          <span class="material-icons">dashboard</span>
          <span class="text-sm font-medium">Dashboard</span>
        </a>
        <a href="{{ route('produk.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-gray-100">
          <span class="material-icons">inventory_2</span>
          <span class="text-sm font-medium">Produk</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-full bg-gray-100">
          <span class="material-icons">receipt</span>
          <span class="text-sm font-medium">Pesanan</span>
        </a>
        <a href="{{ route('admin.pelanggan') }}" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-gray-100">
          <span class="material-icons">groups</span>
          <span class="text-sm font-medium">Pelanggan</span>
        </a>
        <a href="{{ route('admin.laporan') }}" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
          <span class="material-icons">bar_chart</span>
          <span class="text-sm font-medium">Laporan</span>
        </a>
        <a href="{{ route('chat.admin') }}" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
          <span class="material-icons">chat</span>
          <span class="text-sm font-medium">Obrolan</span>
        </a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-64 p-6">
      <h2 class="text-3xl font-bold text-gray-900 mb-6">Daftar Pesanan</h2>

      @if ($orders->isEmpty())
      <p class="text-gray-600">Belum ada pesanan.</p>
      @else
      <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300">
          <thead class="bg-gray-100 text-left">
            <tr>
              <th class="px-4 py-2 border">Nama User</th>
              <th class="px-4 py-2 border">Tanggal</th>
              <th class="px-4 py-2 border">Total</th>
              <th class="px-4 py-2 border">Status</th>
              <th class="px-4 py-2 border">Jumlah Item</th>
              <th class="px-4 py-2 border">Metode Pembayaran</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($orders as $order)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-2 border">{{ $order->user->name }}</td>
              <td class="px-4 py-2 border">{{ $order->order_date->format('d-m-Y H:i') }}</td>
              <td class="px-4 py-2 border">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
              <td class="px-4 py-2 border capitalize">{{ $order->status }}</td>
              <td class="px-4 py-2 border">{{ $order->items->count() }} item</td>
              <td class="px-4 py-2 border capitalize">
                {{ $order->payment_type ? ucfirst($order->payment_type) : 'Belum dibayar' }}
              </td>
            </tr>

            @if ($order->items->count() > 0)
            <tr>
              <td colspan="6" class="px-4 py-2 border bg-gray-50">
                <div class="text-sm font-semibold mb-1">Detail Produk:</div>
                <ul class="list-disc ml-5 text-sm text-gray-700">
                  @foreach ($order->items as $item)
                  <li>
                    {{ $item->product->nama ?? 'Produk Dihapus' }} - Ukuran: {{ $item->ukuran }},
                    Qty: {{ $item->jumlah }},
                    Harga: Rp{{ number_format($item->harga, 0, ',', '.') }}
                  </li>
                  @endforeach
                </ul>
              </td>
            </tr>
            @endif

            @endforeach
          </tbody>
        </table>
      </div>
      @endif
    </main>
  </div>
</body>

</html>