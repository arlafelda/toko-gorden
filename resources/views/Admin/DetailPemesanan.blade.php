<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detail Pesanan</title>
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
        <a href="{{ route('pesananAdmin') }}" class="flex items-center gap-3 px-3 py-2 rounded-full bg-gray-100">
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
      <!-- Breadcrumb -->
      <nav class="text-gray-500 text-sm mb-6">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-500">Beranda</a> /
        <a href="{{ route('pesananAdmin') }}" class="hover:text-blue-500">Pesanan</a> /
        <span>Detail Pesanan</span>
      </nav>

      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold">Pesanan #{{ $order->invoice_number }}</h1>
          <p class="text-gray-500">{{ $order->order_date->format('d F Y') }}</p>
        </div>
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
          {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
          {{ session('error') }}
        </div>
        @endif
        <div class="flex space-x-2">
          @if($order->status === 'selesai')
          <form action="{{ route('admin.addToReport', $order->id) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 flex items-center gap-2">
              📊 Tambah ke Laporan Penjualan
            </button>
          </form>
          @endif
          <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 flex items-center gap-2">
            ✉ Hubungi Pelanggan
          </button>
          <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" onclick="openModal()">
            Ubah Status
          </button>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Item Pesanan -->
        <div class="md:col-span-2 bg-white p-6 rounded shadow">
          <h2 class="font-semibold text-lg mb-4">Item Pesanan</h2>
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-gray-100">
                <th class="p-2">Gambar</th>
                <th class="p-2">Nama</th>
                <th class="p-2">Jenis</th>
                <th class="p-2">Deskripsi</th>
                <th class="p-2">Ukuran</th>
                <th class="p-2">Harga</th>
                <th class="p-2">Jumlah</th>
              </tr>
            </thead>
            <tbody>
              @forelse($order->items as $item)
              <tr class="border-b">
                <td class="p-2">
                  @if($item->product && $item->product->gambar)
                  <img src="{{ asset('uploads/' . $item->product->gambar) }}" alt="{{ $item->product->nama_produk ?? 'Product' }}" class="w-16 h-16 object-cover rounded">
                  @else
                  <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">No Image</div>
                  @endif
                </td>
                <td class="p-2 font-medium text-gray-800">{{ $item->product->nama_produk ?? 'Product not found' }}</td>
                <td class="p-2 text-gray-700">{{ $item->product->jenis_gorden ?? '-' }}</td>
                <td class="p-2 text-gray-700">{{ $item->product->deskripsi ?? '-' }}</td>
                <td class="p-2 text-gray-700">{{ $item->ukuran }}</td>
                <td class="p-2 font-semibold text-gray-900">Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                <td class="p-2 text-gray-700">{{ $item->jumlah }} pcs</td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="p-2 text-center text-gray-500">Tidak ada item dalam pesanan ini.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Ringkasan Pesanan & Detail -->
        <div class="space-y-6">
          <!-- Ringkasan Pesanan -->
          <div class="bg-white p-4 rounded shadow">
            <h2 class="font-semibold mb-2">Ringkasan Pesanan</h2>
            <p><span class="font-medium">Status Pesanan:</span> <span class="text-blue-500">{{ $order->status }}</span></p>
            <p><span class="font-medium">Nama Pelanggan:</span> {{ $order->user->name }}</p>
            <p><span class="font-medium">Email:</span> {{ $order->user->email }}</p>
            <p><span class="font-medium">Telepon:</span> {{ $order->user->phone_number }}</p>
          </div>

          <!-- Detail Pengiriman -->
          <div class="bg-white p-4 rounded shadow">
            <h2 class="font-semibold mb-2">Detail Pengiriman</h2>
            <p><span class="font-medium">Alamat:</span> {{ $order->user->address }}</p>
            <p><span class="font-medium">Metode:</span> Pengiriman Standar</p>
            <p><span class="font-medium">Biaya:</span> Rp 20.000</p>
          </div>

          <!-- Detail Pembayaran -->
          <div class="bg-white p-4 rounded shadow">
            <h2 class="font-semibold mb-2">Detail Pembayaran</h2>
            <p><span class="font-medium">Metode:</span> {{ $order->payment_type }}</p>
            <p><span class="font-medium">Status:</span> <span class="text-green-500">Lunas</span></p>
            <p><span class="font-medium">Subtotal:</span> Rp{{ number_format($order->total - 20000, 0, ',', '.') }}</p>
            <p><span class="font-medium">Pengiriman:</span> Rp20.000</p>
            <p class="font-bold mt-2">Total: Rp{{ number_format($order->total, 0, ',', '.') }}</p>
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- Modal Ubah Status -->
  <div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
      <div class="mt-3">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Ubah Status Pesanan</h3>
        <form action="{{ route('admin.updateStatus', $order->id) }}" method="POST">
          @csrf
          <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Pesanan</label>
            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="pesanan diterima" {{ $order->status == 'pesanan diterima' ? 'selected' : '' }}>Pesanan Diterima</option>
              <option value="sedang diproses" {{ $order->status == 'sedang diproses' ? 'selected' : '' }}>Sedang Diproses</option>
              <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
          </div>
          <div class="flex justify-end space-x-2">
            <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Batal</button>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function openModal() {
      document.getElementById('statusModal').classList.remove('hidden');
    }

    function closeModal() {
      document.getElementById('statusModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
      const modal = document.getElementById('statusModal');
      if (event.target == modal) {
        modal.classList.add('hidden');
      }
    }
  </script>
</body>

</html>
