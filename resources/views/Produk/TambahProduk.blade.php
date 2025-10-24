<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Tambah Produk</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
</head>

<body class="bg-gray-100 font-sans">

    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 h-screen w-64 bg-white p-4 border-r border-gray-200 z-10">
        <h1 class="text-xl font-semibold text-[#111418] mb-6">Admin Panel</h1>
        <nav class="flex flex-col gap-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
                <span class="material-icons">dashboard</span>
                <span class="text-sm font-medium">Dasbor</span>
            </a>
            <a href="{{ route('produk.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-full bg-[#f0f2f4]">
                <span class="material-icons">inventory_2</span>
                <span class="text-sm font-medium">Produk</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
                <span class="material-icons">receipt</span>
                <span class="text-sm font-medium">Pesanan</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
                <span class="material-icons">groups</span>
                <span class="text-sm font-medium">Pelanggan</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
                <span class="material-icons">bar_chart</span>
                <span class="text-sm font-medium">Laporan</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
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
    <main class="ml-64 p-8 max-w-3xl mx-auto bg-white rounded-lg shadow-md">

        <h1 class="text-3xl font-bold mb-6">Tambah Produk Baru</h1>

        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Nama Produk -->
            <div class="mb-6">
                <label for="nama_produk" class="block text-lg font-medium text-gray-700 mb-2">Nama Produk</label>
                <input type="text" id="nama_produk" name="nama_produk" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Deskripsi -->
            <div class="mb-6">
                <label for="deskripsi" class="block text-lg font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50 resize-none"
                    placeholder="Tulis deskripsi produk di sini...">{{ old('deskripsi') }}</textarea>
            </div>

            <!-- Harga Per 100cm -->
            <div class="mb-6">
                <label for="harga" class="block text-lg font-medium text-gray-700 mb-2">Harga</label>
                <input type="number" id="harga" name="harga" required min="0" step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Upload Gambar -->
            <div class="mb-6">
                <label for="upload_gambar" class="block text-lg font-medium text-gray-700 mb-2">Upload Gambar</label>
                <input type="file" id="upload_gambar" name="upload_gambar" accept="image/*"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:font-semibold file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300" />
            </div>

            <!-- Jenis Gorden -->
            <div class="mb-6">
                <label for="jenis_gorden" class="block text-lg font-medium text-gray-700 mb-2">Jenis Gorden</label>
                <select id="jenis_gorden" name="jenis_gorden" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled selected>-- Pilih Jenis --</option>
                    <option value="Gorden Vertikal">Gorden Vertikal</option>
                    <option value="Gorden Natural">Gorden Natural</option>
                    <option value="Gorden Roll Blind">Gorden Roll Blind</option>
                    <option value="Gorden Slimbin">Gorden Slimbin</option>
                    <option value="Gorden Wooden Blind">Gorden Wooden Blind</option>
                    <option value="Aksesoris Rel Gorden">Aksesoris Rel Gorden</option>
                    <option value="Aksesoris Pengait/Tali">Aksesoris Pengait/Tali</option>
                    <option value="Aksesoris Bracket">Aksesoris Bracket</option>
                    <option value="Aksesoris Hook">Aksesoris Hook</option>

                </select>
            </div>

            <!-- Ukuran Produk (Lebar, Tinggi, Harga, Stok) -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Ukuran Produk</h2>
                <div id="ukuran-container" class="flex flex-col gap-4">
                    <!-- Contoh input ukuran -->
                    <div class="flex gap-4 items-center p-4 border rounded">
                        <div>
                            <label class="block text-gray-700 font-medium mb-1" for="lebar_0">Lebar (cm)</label>
                            <input type="number" step="0.1" min="0" required id="lebar_0" name="lebar[]"
                                class="w-24 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-1" for="tinggi_0">Tinggi (cm)</label>
                            <input type="number" step="0.1" min="0" required id="tinggi_0" name="tinggi[]"
                                class="w-24 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-1" for="harga_0">Harga (Rp)</label>
                            <input type="number" min="0" required id="harga_0" name="harga_ukuran[]"
                                class="w-32 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-1" for="stok_0">Stok</label>
                            <input type="number" min="0" required id="stok_0" name="stok_ukuran[]"
                                class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <button type="button" onclick="this.closest('div').remove()" title="Hapus Ukuran"
                            class="text-red-600 hover:text-red-800 font-bold text-xl leading-none mt-6">&times;</button>
                    </div>
                </div>
                <button type="button" id="tambahUkuranBtn"
                    class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + Tambah Ukuran Baru
                </button>
            </div>

            <!-- Tombol Simpan -->
            <div class="flex items-center space-x-4">
                <button type="submit"
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-md">
                    Simpan Produk
                </button>
                <a href="{{ route('produk.index') }}"
                    class="bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-2 px-6 rounded-md">
                    Kembali
                </a>
            </div>
        </form>
    </main>

    <script>
        document.getElementById('tambahUkuranBtn').addEventListener('click', function() {
            const container = document.getElementById('ukuran-container');
            const index = container.children.length;

            const div = document.createElement('div');
            div.classList.add('flex', 'gap-4', 'items-center', 'p-4', 'border', 'rounded');

            div.innerHTML = `
                <div>
                    <label class="block text-gray-700 font-medium mb-1" for="lebar_${index}">Lebar (cm)</label>
                    <input type="number" step="0.1" min="0" required id="lebar_${index}" name="lebar[]" 
                           class="w-24 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-1" for="tinggi_${index}">Tinggi (cm)</label>
                    <input type="number" step="0.1" min="0" required id="tinggi_${index}" name="tinggi[]" 
                           class="w-24 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-1" for="harga_${index}">Harga (Rp)</label>
                    <input type="number" min="0" required id="harga_${index}" name="harga_ukuran[]" 
                           class="w-32 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-1" for="stok_${index}">Stok</label>
                    <input type="number" min="0" required id="stok_${index}" name="stok_ukuran[]" 
                           class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <button type="button" onclick="this.closest('div').remove()" title="Hapus Ukuran" 
                        class="text-red-600 hover:text-red-800 font-bold text-xl leading-none mt-6">&times;</button>
            `;

            container.appendChild(div);
        });
    </script>

</body>

</html>