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
      <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
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
      <a href="<?php echo e(route('admin.laporan')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-full bg-[#f0f2f4]">
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

  <!-- Konten utama -->
  <main class="ml-80 px-10 py-6">
    <h1 class="text-2xl font-bold mb-6">Laporan Bulanan</h1>

    <?php if(session('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
      <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <!-- Filter Bulan -->
    <form method="GET" class="mb-6 flex gap-3 items-center">
      <input type="month" name="bulan" value="<?php echo e($bulan); ?>"
        class="border rounded-lg px-3 py-2">
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Filter</button>
    </form>

    <!-- Tabel Laporan -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
      <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-700">
          <tr>
            <th class="px-4 py-2">Tanggal</th>
            <th class="px-4 py-2">Pelanggan</th>
            <th class="px-4 py-2">Produk</th>
            <th class="px-4 py-2">Jumlah</th>
            <th class="px-4 py-2">Harga</th>
            <th class="px-4 py-2">Total</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <tr class="border-b">
            <td class="px-4 py-2"><?php echo e($order->order_date->format('d-m-Y')); ?></td>
            <td class="px-4 py-2"><?php echo e($order->user->name ?? 'Guest'); ?></td>
            <td class="px-4 py-2"><?php echo e($item->product->nama ?? 'Produk Dihapus'); ?></td>
            <td class="px-4 py-2"><?php echo e($item->jumlah); ?></td>
            <td class="px-4 py-2">Rp <?php echo e(number_format($item->harga, 0, ',', '.')); ?></td>
            <td class="px-4 py-2">Rp <?php echo e(number_format($item->jumlah * $item->harga, 0, ',', '.')); ?></td>
          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="6" class="text-center py-4 text-gray-500">Tidak ada data laporan untuk bulan ini.</td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Total -->
    <div class="mt-6 text-right">
      <p class="text-lg font-bold">Total Pendapatan:
        <span class="text-green-600">Rp <?php echo e(number_format($totalPendapatan, 0, ',', '.')); ?></span>
      </p>
    </div>
  </main>
</body>

</html><?php /**PATH D:\Website\website-gorden\resources\views/Admin/Laporan.blade.php ENDPATH**/ ?>