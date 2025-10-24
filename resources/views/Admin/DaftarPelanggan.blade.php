<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel - Daftar Pelanggan</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&family=Noto+Sans:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
</head>

<body class="bg-gray-100 font-sans" style='font-family: "Work Sans", "Noto Sans", sans-serif;'>

    <!-- Sidebar Fixed -->
    <aside class="fixed top-0 left-0 h-screen w-80 bg-white p-4 border-r border-gray-200 z-10">
        <h1 class="text-xl font-semibold text-[#111418] mb-6">Admin Panel</h1>
        <nav class="flex flex-col gap-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
                <span class="material-icons">dashboard</span>
                <span class="text-sm font-medium">Dashboard</span>
            </a>
            <a href="{{ route('produk.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
                <span class="material-icons">inventory_2</span>
                <span class="text-sm font-medium">Produk</span>
            </a>
            <a href="{{ route('pesananAdmin') }}" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
                <span class="material-icons">receipt</span>
                <span class="text-sm font-medium">Pesanan</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-full bg-[#f0f2f4]">
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

    <!-- Konten Utama -->
    <div class="ml-80 min-h-screen p-8">
        <main class="flex-1 bg-gray-50">
            <div class="mx-auto max-w-7xl">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold leading-tight tracking-tight text-gray-900">Daftar Pelanggan</h1>
                    <p class="mt-2 text-sm text-gray-600">Kelola informasi pelanggan Anda di satu tempat.</p>
                </div>

                <!-- Form Pencarian -->
                <form method="GET" action="{{ route('admin.pelanggan') }}" class="flex flex-1 gap-4 mb-6">
                    <div class="relative w-full">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M10 2a8 8 0 015.292 13.708l4.292 4.292-1.414 1.414-4.292-4.292A8 8 0 1110 2zm0 2a6 6 0 100 12 6 6 0 000-12z" />
                            </svg>
                        </div>
                        <input
                            type="search"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Cari pelanggan berdasarkan nama atau alamat"
                            class="w-full rounded-md border-gray-300 py-2.5 pl-10 pr-4 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <button type="submit"
                        class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
                        Cari
                    </button>
                </form>

                <!-- TABEL -->
                <div class="overflow-hidden border border-gray-200 rounded-lg bg-white shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-max table-auto">
                            <thead class="bg-gray-50 text-left">
                                <tr>
                                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Nama Pelanggan</th>
                                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Alamat</th>
                                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Nomor Telepon</th>
                                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Riwayat Pesanan</th>
                                    <th class="px-6 py-4 text-sm font-semibold text-gray-600"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($pelanggan as $user)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->address ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->phone_number ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->orders->count() }} pesanan</td>
                                    <td class="px-6 py-4 text-right">
                                        {{-- Tombol aksi jika dibutuhkan --}}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada pelanggan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>