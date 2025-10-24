<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Stitch Design</title>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;700;900&family=Work+Sans:wght@400;500;700;900&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100 font-sans" style='font-family: "Work Sans", "Noto Sans", sans-serif;'>

  <!-- ✅ Sidebar Fixed -->
  <aside class="fixed top-0 left-0 h-screen w-80 bg-white p-4 border-r border-gray-200 z-10">
    <h1 class="text-xl font-semibold text-[#111418] mb-6">Admin Panel</h1>
    <nav class="flex flex-col gap-2">
      <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-full bg-[#f0f2f4]">
        <span class="material-icons">dashboard</span>
        <span class="text-sm font-medium">Dashboard</span>
      </a>
      <a href="<?php echo e(route('produk.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
        <span class="material-icons">inventory_2</span>
        <span class="text-sm font-medium">Produk</span>
      </a>
      <a href="<?php echo e(route('pesananAdmin')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
        <span class="material-icons">receipt</span>
        <span class="text-sm font-medium">Pesanan</span>
      </a>
      <a href="<?php echo e(route('admin.pelanggan')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
        <span class="material-icons">groups</span>
        <span class="text-sm font-medium">Pelanggan</span>
      </a>
      <a href="<?php echo e(route('admin.laporan')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
        <span class="material-icons">bar_chart</span>
        <span class="text-sm font-medium">Laporan</span>
      </a>
      <a href="<?php echo e(route('chat.admin')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
        <span class="material-icons">chat</span>
        <span class="text-sm font-medium">Obrolan</span>
      </a>
      <a href="<?php echo e(route('admin.trafik')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
        <span class="material-icons">analytics</span>
        <span class="text-sm font-medium">Trafik</span>
      </a>

    </nav>
  </aside>

  <!-- ✅ Main Dashboard Content -->
  <main class="ml-80 p-6">
    <h1 class="text-[32px] font-bold text-[#111418] mb-6">Dashboard</h1>

    <!-- Statistik -->
    <div class="flex flex-wrap gap-4 mb-8">
      <div class="flex flex-col gap-2 bg-green-100 p-6 rounded-xl min-w-[158px] flex-1">
        <p class="text-base font-medium text-green-900">Total Penjualan</p>
        <p class="text-2xl font-bold text-green-900">Rp <?php echo e(number_format($totalPendapatan, 0, ',', '.')); ?></p>
      </div>
      <div class="flex flex-col gap-2 bg-blue-100 p-6 rounded-xl min-w-[158px] flex-1">
        <p class="text-base font-medium text-blue-900">Total Pesanan</p>
        <p class="text-2xl font-bold text-blue-900"><?php echo e($totalPesanan); ?></p>
      </div>

      <div class="flex flex-col gap-2 bg-red-100 p-6 rounded-xl min-w-[158px] flex-1">
        <p class="text-base font-medium text-red-900">Pelanggan Baru</p>
        <p class="text-2xl font-bold text-red-900"><?php echo e($jumlahPelanggan); ?></p>
      </div>
    </div>

    <!-- Ringkasan Penjualan -->
    <h2 class="text-[22px] font-bold text-[#111418] mb-3">Ringkasan Penjualan</h2>
    <div class="bg-white border border-[#dce0e5] p-6 rounded-xl mb-8">
      <p class="text-base font-medium text-[#111418] mb-2">Penjualan Harian</p>
      <p class="text-[32px] font-bold text-[#111418] mb-1">Rp <?php echo e(number_format($totalPendapatan, 0, ',', '.')); ?></p>
      <div class="w-full overflow-x-auto">
        <canvas id="grafikPenjualan" class="w-full h-full"></canvas>
      </div>
    </div>

    <!-- Aktivitas Pelanggan -->
    <h2 class="text-[22px] font-bold text-[#111418] mb-3">Aktivitas Pelanggan Terbaru</h2>
    <div class="overflow-auto bg-white border border-[#dce0e5] rounded-xl">
      <table class="w-full">
        <thead class="bg-white">
          <tr>
            <th class="text-left px-4 py-3 text-sm font-medium text-[#111418]">Nama Pelanggan</th>
            <th class="text-left px-4 py-3 text-sm font-medium text-[#111418]">Tanggal Pesanan</th>
            <th class="text-left px-4 py-3 text-sm font-medium text-[#111418]">Total Pesanan</th>
            <th class="text-left px-4 py-3 text-sm font-medium text-[#111418]">Status</th>
          </tr>
        </thead>
        <tbody>
          <!-- Tambahkan data riil sesuai kebutuhan -->
        </tbody>
      </table>
    </div>

  </main>

  <script>
    const ctx = document.getElementById('grafikPenjualan').getContext('2d');

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

    // Fungsi mengambil data dari backend
    function loadTrafikPendapatan(tanggal) {
      fetch(`/admin/laporan/pendapatan-harian?tanggal=${tanggal}`)
        .then(response => response.json())
        .then(data => {
          chartData.labels = data.labels;
          chartData.datasets[0].data = data.data;
          salesChart.update();
        });
    }

    // Ambil tanggal hari ini sebagai default
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    const todayStr = `${yyyy}-${mm}-${dd}`;

    // Load grafik saat pertama kali dashboard dibuka
    loadTrafikPendapatan(todayStr);
  </script>



</body>

</html><?php /**PATH D:\Website\website-gorden\resources\views/Admin/DashboardAdmin.blade.php ENDPATH**/ ?>