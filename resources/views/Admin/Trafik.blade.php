<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laporan Admin</title>
  <link rel="icon" type="image/x-icon" href="data:image/x-icon;base64," />
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
      <a href="{{ route('admin.laporan') }}" class="flex items-center gap-3 px-3 py-2 rounded-full bg-[#f0f2f4]">
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
    <!-- Judul Laporan -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900">Laporan Admin</h1>
      <p class="text-sm text-gray-500">Analisis kinerja toko Anda secara harian.</p>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="border rounded-xl p-6">
        <p class="text-base font-medium text-red-900">Jumlah Pelanggan</p>
        <p class="text-3xl font-bold text-red-900 truncate">{{ $jumlahPelanggan }}</p>
      </div>

      <div class="border rounded-xl p-6">
        <p class="text-base font-medium text-green-900">Total Pendapatan</p>
        <p class="text-3xl font-bold text-green-900 truncate">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
      </div>
    </div>


    <!-- Grafik Trafik Harian -->
    <div class="mt-10">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-900">Trafik Pendapatan Harian</h2>
        <input type="date" id="dateFilter" class="border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>
      <div class="bg-white p-4 border rounded-xl w-full overflow-x-auto">
        <canvas id="salesChart" height="100"></canvas>
      </div>
    </div>
  </main>

  <!-- Script -->
  <script>
    const ctx = document.getElementById('salesChart').getContext('2d');

    const chartData = {
      labels: [],
      datasets: [{
        label: 'Pendapatan Harian',
        data: [],
        fill: true,
        borderColor: 'rgb(59, 130, 246)',
        backgroundColor: 'rgba(59, 130, 246, 0.2)',
        tension: 0.4
      }]
    };

    const salesChart = new Chart(ctx, {
      type: 'line',
      data: chartData,
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return 'Rp ' + value.toLocaleString('id-ID');
              }
            }
          }
        }
      }
    });

    function updateChartWithDate(dateStr) {
      fetch(`/admin/laporan/pendapatan-harian?tanggal=${dateStr}`)
        .then(response => response.json())
        .then(data => {
          chartData.labels = data.labels;
          chartData.datasets[0].data = data.data;
          salesChart.update();
        });
    }

    const dateInput = document.getElementById('dateFilter');

    // Set default ke hari ini
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    const todayStr = `${yyyy}-${mm}-${dd}`;
    dateInput.value = todayStr;

    // Event saat ganti tanggal
    dateInput.addEventListener('change', function() {
      if (!this.value) {
        this.value = todayStr; // atur ke tanggal hari ini
      }
      updateChartWithDate(this.value);
    });

    // Saat halaman pertama kali dibuka
    updateChartWithDate(todayStr);
  </script>
</body>

</html>
