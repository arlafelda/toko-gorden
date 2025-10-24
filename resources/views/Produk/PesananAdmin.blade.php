<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Pesanan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
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
        <a href="{{ route('admin.laporan') }}" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-gray-100">
          <span class="material-icons">bar_chart</span>
          <span class="text-sm font-medium">Laporan</span>
        </a>
        <a href="{{ route('chat.admin') }}" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-gray-100">
          <span class="material-icons">chat</span>
          <span class="text-sm font-medium">Obrolan</span>
        </a>
        <a href="{{ route('admin.trafik') }}" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-gray-100">
          <span class="material-icons">analytics</span>
          <span class="text-sm font-medium">Trafik</span>
        </a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-64 p-8">
      <h2 class="text-3xl font-bold text-gray-900 mb-6">Daftar Pesanan</h2>

      @if ($orders->isEmpty())
      <p class="text-gray-600">Belum ada pesanan.</p>
      @else
      <!-- Grid pesanan -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach ($orders as $order)
        <a href="{{ route('admin.detailPesanan', $order->id) }}" class="flex items-center justify-between p-5 bg-[#f8fafc] rounded-2xl shadow-sm hover:shadow-md transition transform hover:-translate-y-1">
          <div>
            <h3 class="text-lg font-semibold text-gray-900">{{ $order->user->name }}</h3>
            <p class="text-sm text-gray-600">#{{ $order->invoice_number }}</p>
            <p class="text-xs text-gray-500 mt-1">
              {{ $order->order_date->format('d M Y, H:i') }}
            </p>
          </div>
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke-width="1.8" stroke="currentColor" class="w-7 h-7 text-gray-500">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M7.5 3.75h9a2.25 2.25 0 012.25 2.25v12a2.25 2.25 0 01-2.25 2.25h-9A2.25 2.25 0 015.25 18V6a2.25 2.25 0 012.25-2.25z" />
          </svg>
        </a>
        @endforeach
      </div>
      @endif
    </main>
  </div>
</body>

</html>