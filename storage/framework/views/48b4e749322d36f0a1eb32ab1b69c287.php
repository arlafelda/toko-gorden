<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Pesanan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
</head>

<body class="bg-white font-sans text-gray-800">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 h-screen w-64 bg-white p-4 border-r border-gray-200 z-10">
      <h1 class="text-xl font-semibold text-gray-900 mb-6">Admin Panel</h1>
      <nav class="flex flex-col gap-2">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-gray-100">
          <span class="material-icons">dashboard</span>
          <span class="text-sm font-medium">Dashboard</span>
        </a>
        <a href="<?php echo e(route('produk.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-gray-100">
          <span class="material-icons">inventory_2</span>
          <span class="text-sm font-medium">Produk</span>
        </a>
        <a href="<?php echo e(route('pesananAdmin')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-full bg-gray-100">
          <span class="material-icons">receipt</span>
          <span class="text-sm font-medium">Pesanan</span>
        </a>
        <a href="<?php echo e(route('admin.pelanggan')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-gray-100">
          <span class="material-icons">groups</span>
          <span class="text-sm font-medium">Pelanggan</span>
        </a>
        <a href="<?php echo e(route('admin.laporan')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-gray-100">
          <span class="material-icons">bar_chart</span>
          <span class="text-sm font-medium">Laporan</span>
        </a>
        <a href="<?php echo e(route('chat.admin')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-gray-100">
          <span class="material-icons">chat</span>
          <span class="text-sm font-medium">Obrolan</span>
        </a>
        <a href="<?php echo e(route('admin.trafik')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-gray-100">
          <span class="material-icons">analytics</span>
          <span class="text-sm font-medium">Trafik</span>
        </a>
        <form action="<?php echo e(route('admin.logout')); ?>" method="POST">
          <?php echo csrf_field(); ?>
          <button type="submit" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4] w-full text-left">
            <span class="material-icons">logout</span>
            <span class="text-sm font-medium">Logout</span>
          </button>
        </form>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-64 p-8">
      <h2 class="text-3xl font-bold text-gray-900 mb-6">Daftar Pesanan</h2>

      <?php if($orders->isEmpty()): ?>
        <p class="text-gray-600">Belum ada pesanan yang sudah dibayar.</p>
      <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('admin.detailPesanan', $order->id)); ?>" 
               class="flex items-center justify-between p-5 bg-[#f8fafc] rounded-2xl shadow-sm hover:shadow-md transition transform hover:-translate-y-1">
              <div>
                <!-- Nomor urut -->
                <span class="text-sm text-gray-400 font-medium"><?php echo e($loop->iteration); ?>.</span>
                
                <!-- Nama user -->
                <h3 class="text-lg font-semibold text-gray-900"><?php echo e($order->user->name); ?></h3>
                
                <!-- Invoice number -->
                <p class="text-sm text-gray-600">#<?php echo e($order->invoice_number); ?></p>
                
                <!-- Tanggal & jam transaksi -->
                <p class="text-xs text-gray-500 mt-1">
                  <?php echo e($order->order_date->format('d M Y H:i')); ?>

                </p>
                
                <!-- Status pesanan -->
                <p class="text-xs mt-1">
                  <span class="inline-block bg-gray-100 px-2 py-1 rounded-full capitalize">
                    <?php echo e($order->status); ?>

                  </span>
                </p>
              </div>
              <span class="material-icons text-gray-500">arrow_forward_ios</span>
            </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      <?php endif; ?>
    </main>
  </div>
</body>

</html>
<?php /**PATH D:\Website\website-gorden\resources\views/Produk/PesananAdmin.blade.php ENDPATH**/ ?>