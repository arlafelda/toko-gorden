<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Gorden</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
</head>

<body class="bg-gray-100 font-sans">
    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 h-screen w-80 bg-white p-4 border-r border-gray-200 z-10">
        <h1 class="text-xl font-semibold text-[#111418] mb-6">Admin Panel</h1>
        <nav class="flex flex-col gap-2">
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
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
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="ml-80 p-8">
        <div class="bg-white p-8 rounded-lg shadow-md max-w-3xl mx-auto">
            <h2 class="text-3xl font-bold mb-6">Edit Gorden</h2>

            <form action="{{ route('produk.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Nama Produk -->
                <div class="mb-6">
                    <label for="nama_produk" class="block text-lg font-medium text-gray-700 mb-2">Nama Produk</label>
                    <input id="nama_produk" name="nama_produk" type="text"
                        value="{{ old('nama_produk', $product->nama_produk) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50" required />
                </div>

                <!-- Deskripsi -->
                <div class="mb-6">
                    <label for="deskripsi" class="block text-lg font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50 resize-none"
                        placeholder="Tulis deskripsi produk di sini...">{{ old('deskripsi', $product->deskripsi) }}</textarea>
                </div>


                <!-- Harga Dasar -->
                <div class="mb-6">
                    <label for="harga" class="block text-lg font-medium text-gray-700 mb-2">Harga </label>
                    <input id="harga" name="harga" type="number"
                        value="{{ old('harga', $product->harga) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50" required />
                </div>

                <!-- Upload Gambar -->
                <div class="mb-6">
                    <label for="upload_gambar" class="block text-lg font-medium text-gray-700 mb-2">Gambar Baru</label>
                    <input id="upload_gambar" name="upload_gambar" type="file"
                        class="w-full text-sm file:py-2 file:px-4 file:rounded file:border-0 file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300" />
                    @if ($product->gambar)
                    <p class="mt-2 text-sm text-gray-600">Gambar saat ini:</p>
                    <img src="{{ asset('uploads/' . $product->gambar) }}"
                        class="w-32 h-32 object-cover mt-2 rounded-md" alt="Gambar Produk">
                    @endif
                </div>

                <!-- Jenis Gorden -->
                <div class="mb-8">
                    <label for="jenis_gorden" class="block text-lg font-medium text-gray-700 mb-2">Jenis Gorden</label>
                    <select id="jenis_gorden" name="jenis_gorden"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50" required>
                        <option disabled value="">-- Pilih Jenis --</option>
                        <option value="Gorden Vertikal" {{ old('jenis_gorden', $product->jenis_gorden) == 'Gorden Vertikal' ? 'selected' : '' }}>Gorden Vertikal</option>
                        <option value="Gorden Natural" {{ old('jenis_gorden', $product->jenis_gorden) == 'Gorden Natural' ? 'selected' : '' }}>Gorden Natural</option>
                        <option value="Gorden Roll Blind" {{ old('jenis_gorden', $product->jenis_gorden) == 'Gorden Roll Blind' ? 'selected' : '' }}>Gorden Roll Blind</option>
                        <option value="Gorden Slimbin" {{ old('jenis_gorden', $product->jenis_gorden) == 'Gorden Slimbin' ? 'selected' : '' }}>Gorden Slimbin</option>
                        <option value="Gorden Wooden Blind" {{ old('jenis_gorden', $product->jenis_gorden) == 'Gorden Wooden Blind' ? 'selected' : '' }}>Gorden Wooden Blind</option>
                        <option value="Aksesoris Rel Gorden" {{ old('jenis_gorden', $product->jenis_gorden) == 'Aksesoris Rel Gorden' ? 'selected' : '' }}>Aksesoris Rel Gorden</option>
                        <option value="Aksesoris Pengait/Tali" {{ old('jenis_gorden', $product->jenis_gorden) == 'Aksesoris Pengait/Tali' ? 'selected' : '' }}>Aksesoris Pengait/Tali</option>
                        <option value="Aksesoris Bracket" {{ old('jenis_gorden', $product->jenis_gorden) == 'Aksesoris Bracket' ? 'selected' : '' }}>Aksesoris Bracket</option>
                        <option value="Aksesoris Hook" {{ old('jenis_gorden', $product->jenis_gorden) == 'Aksesoris Hook' ? 'selected' : '' }}>Aksesoris Hook</option>
                    </select>
                </div>


                <!-- Ukuran Lama -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4">Ukuran Produk (Lama)</h3>

                    <div id="ukuran-lama-container" class="flex flex-col gap-4">
                        @foreach($product->sizes as $index => $ukuran)
                        <div class="flex gap-4 items-center p-4 border rounded">
                            <input type="hidden" name="ukuran_id[]" value="{{ $ukuran->id }}" />

                            <div>
                                <label class="block text-gray-700 font-medium mb-1" for="ukuran_{{ $index }}">Ukuran (cm)</label>
                                <input id="ukuran_{{ $index }}" type="text" name="ukuran[]" required
                                    value="{{ old('ukuran.'.$index, $ukuran->ukuran) }}"
                                    class="w-40 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>

                            <div>
                                <label class="block text-gray-700 font-medium mb-1" for="harga_{{ $index }}">Harga Ukuran (Rp)</label>
                                <input id="harga_{{ $index }}" type="number" name="harga_ukuran[]" min="0" required
                                    value="{{ old('harga_ukuran.'.$index, $ukuran->harga) }}"
                                    class="w-32 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>

                            <div>
                                <label class="block mb-1 text-gray-700">Stok</label>
                                <input type="number" name="stok_ukuran[]" value="{{ old('stok_ukuran.'.$index, $ukuran->stok) }}"
                                    class="w-24 px-3 py-2 border border-gray-300 rounded-md" required />
                            </div>

                            <button type="button" onclick="this.closest('div').remove()" title="Hapus Ukuran"
                                class="text-red-600 hover:text-red-800 font-bold text-xl leading-none mt-6">&times;</button>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Ukuran Baru -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4">Ukuran Produk (Baru)</h3>
                    <div id="ukuran-baru-container" class="flex flex-col gap-4">
                        <!-- Ukuran baru akan ditambah disini -->
                    </div>

                    <button type="button" id="tambahUkuranBaruBtn"
                        class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        + Tambah Ukuran Baru
                    </button>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">Simpan</button>
                    <a href="{{ route('produk.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded">Batal</a>
                </div>
            </form>
        </div>
    </main>

    <script>
        document.getElementById('tambahUkuranBaruBtn').addEventListener('click', function() {
            const container = document.getElementById('ukuran-baru-container');

            const div = document.createElement('div');
            div.className = 'flex flex-wrap gap-4 items-end p-4 border rounded';
            div.innerHTML = `
                <div>
                    <label class="block mb-1 text-gray-700">Lebar (cm)</label>
                    <input type="number" name="lebar[]" class="w-28 px-3 py-2 border border-gray-300 rounded-md" required />
                </div>
                <div>
                    <label class="block mb-1 text-gray-700">Tinggi (cm)</label>
                    <input type="number" name="tinggi[]" class="w-28 px-3 py-2 border border-gray-300 rounded-md" required />
                </div>
                <div>
                    <label class="block mb-1 text-gray-700">Harga (Rp)</label>
                    <input type="number" name="harga_ukuran[]" class="w-32 px-3 py-2 border border-gray-300 rounded-md" required />
                </div>
                <div>
                    <label class="block mb-1 text-gray-700">Stok</label>
                    <input type="number" name="stok_ukuran[]" class="w-24 px-3 py-2 border border-gray-300 rounded-md" required />
                </div>
                <button type="button" onclick="this.closest('div').remove()" class="text-red-600 text-xl font-bold">&times;</button>
            `;
            container.appendChild(div);
        });
    </script>
</body>

</html>