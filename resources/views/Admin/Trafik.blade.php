<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Trafik Admin</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="min-h-screen bg-white font-sans">
    <!-- Sidebar -->
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
            <a href="{{ route('admin.pelanggan') }}" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
                <span class="material-icons">groups</span>
                <span class="text-sm font-medium">Pelanggan</span>
            </a>
            <a href="{{ route('admin.trafik') }}" class="flex items-center gap-3 px-3 py-2 rounded-full bg-[#f0f2f4]">
                <span class="material-icons">analytics</span>
                <span class="text-sm font-medium">Trafik</span>
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
    <main class="ml-80 px-10 py-6">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Trafik Pengunjung</h1>
            <p class="text-sm text-gray-500">Analisis trafik pengunjung toko Anda.</p>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="border rounded-xl p-6">
                <p class="text-base font-medium text-blue-900">Total Pengunjung</p>
                <p class="text-3xl font-bold text-blue-900 truncate">{{ $totalPengunjung }}</p>
            </div>

            <div class="border rounded-xl p-6">
                <p class="text-base font-medium text-green-900">Hari Ini</p>
                <p class="text-3xl font-bold text-green-900 truncate">{{ $pengunjungHariIni }}</p>
            </div>

            <div class="border rounded-xl p-6">
                <p class="text-base font-medium text-orange-900">Minggu Ini</p>
                <p class="text-3xl font-bold text-orange-900 truncate">{{ $pengunjungMingguIni }}</p>
            </div>

            <div class="border rounded-xl p-6">
                <p class="text-base font-medium text-purple-900">Bulan Ini</p>
                <p class="text-3xl font-bold text-purple-900 truncate">{{ $pengunjungBulanIni }}</p>
            </div>
        </div>

        <!-- Grafik Trafik Harian -->
        <div class="mt-10">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Trafik Pengunjung Harian (7 Hari Terakhir)</h2>
            <div class="bg-white p-4 border rounded-xl w-full overflow-x-auto">
                <canvas id="trafficChart" height="100"></canvas>
            </div>
        </div>
    </main>

    <!-- Script -->
    <script>
        const ctx = document.getElementById('trafficChart').getContext('2d');

        const chartData = {
            labels: [],
            datasets: [{
                label: 'Pengunjung Harian',
                data: [],
                fill: true,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                tension: 0.4
            }]
        };

        const trafficChart = new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });

        fetch('{{ route("admin.trafik.data") }}')
            .then(response => response.json())
            .then(data => {
                chartData.labels = data.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
                });
                chartData.datasets[0].data = data.map(item => item.count);
                trafficChart.update();
            })
            .catch(error => console.error('Error loading traffic data:', error));
    </script>
</body>
</html>
