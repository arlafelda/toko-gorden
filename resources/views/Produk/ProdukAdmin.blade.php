<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Produk</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans">

  <!-- Sidebar -->
  <aside class="fixed top-0 left-0 h-screen w-64 bg-white p-4 border-r border-gray-200 z-10">
    <h1 class="text-xl font-semibold text-[#111418] mb-6">Admin Panel</h1>
    <nav class="flex flex-col gap-2">
      <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
        <span class="material-icons">dashboard</span>
        <span class="text-sm font-medium">Dashboard</span>
      </a>
      <a href="{{ route('produk.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-full bg-[#f0f2f4]">
        <span class="material-icons">inventory_2</span>
        <span class="text-sm font-medium">Produk</span>
      </a>
      <a href="{{ route('pesananAdmin') }}" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
        <span class="material-icons">receipt</span>
        <span class="text-sm font-medium">Pesanan</span>
      </a>
      <a href="{{ route('admin.pelanggan') }}" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
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
      <a href="{{ route('admin.trafik') }}" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
        <span class="material-icons">analytics</span>
        <span class="text-sm font-medium">Trafik</span>
      </a>

    </nav>
  </aside>

  <!-- Main Content -->
  <main class="ml-64 p-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Daftar Produk</h1>
      <a href="{{ route('produk.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Tambah Produk
      </a>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
      <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-700 uppercase">
          <tr>
            <td class="px-4 py-3">Id</td>
            <th class="px-4 py-3">Gambar</th>
            <th class="px-4 py-3">Nama</th>
            <th class="px-4 py-3">Jenis</th>
            <th class="px-4 py-3">Deskripsi</th>
            <th class="px-4 py-3">Ukuran</th>
            <th class="px-4 py-3">Harga</th>
            <th class="px-4 py-3">Stok</th>
            <th class="px-4 py-3 text-center w-40">Aksi</th>
          </tr>
        </thead>
        <tbody class="text-gray-700">
          @forelse ($products as $product)
          <tr class="border-t hover:bg-gray-50">
            <td class="px-4 py-3 align-top">{{ $product->id }}</td>
            <td class="px-4 py-3 align-top">
              <img src="{{ asset('uploads/' . $product->gambar) }}" alt="{{ $product->nama_produk }}"
                class="w-20 h-20 object-cover rounded-md border" />
            </td>
            <td class="px-4 py-3 align-top">{{ $product->nama_produk }}</td>
            <td class="px-4 py-3 align-top">{{ $product->jenis_gorden }}</td>
            <td class="px-4 py-3 align-top whitespace-pre-line">
              {!! nl2br(e($product->deskripsi)) !!}
            </td>


            <td class="px-4 py-3 align-top">
              @foreach ($product->sizes as $size)
              <div>{{ $size->ukuran }}</div>
              @endforeach
            </td>

            <td class="px-4 py-3 align-top">
              @foreach ($product->sizes as $size)
              <div>Rp{{ number_format($size->harga, 0, ',', '.') }}</div>
              @endforeach
            </td>

            <td class="px-4 py-3 align-top">
              @foreach ($product->sizes as $size)
              <div>{{ $size->stok }} pcs</div>
              @endforeach
            </td>

            <td class="px-4 py-3 text-center align-top">
              <div class="flex justify-center gap-2">
                <a href="{{ route('produk.edit', $product->id) }}"
                  class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded text-xs font-medium">
                  Edit
                </a>
                <form action="{{ route('produk.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                    class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs font-medium">
                    Hapus
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="px-4 py-6 text-center text-gray-500">Belum ada produk ditambahkan.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </main>

</body>

</html>