<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detail Pesanan</title>
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
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-64 p-8">
      <nav class="text-gray-500 text-sm mb-6">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="hover:text-blue-500">Beranda</a> /
        <a href="<?php echo e(route('pesananAdmin')); ?>" class="hover:text-blue-500">Pesanan</a> /
        <span>Detail Pesanan</span>
      </nav>

      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold">Pesanan #<?php echo e($order->invoice_number); ?></h1>
          <p class="text-gray-500"><?php echo e($order->order_date->format('d F Y H:i')); ?></p>
        </div>
        <div class="flex space-x-2">
          <?php if($order->status === 'selesai' && !$order->is_reported): ?>
          <form action="<?php echo e(route('admin.addToReport', $order->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 flex items-center gap-2">
              📊 Tambah ke Laporan Penjualan
            </button>
          </form>
          <?php endif; ?>
          <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" onclick="openModal()">Ubah Status</button>
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
                <th class="p-2">Ukuran</th>
                <th class="p-2">Harga</th>
                <th class="p-2">Jumlah</th>
              </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <tr class="border-b">
                <td class="p-2">
                  <?php if($item->product && $item->product->gambar): ?>
                  <img src="<?php echo e(asset('uploads/' . $item->product->gambar)); ?>" class="w-16 h-16 object-cover rounded">
                  <?php else: ?>
                  <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">No Image</div>
                  <?php endif; ?>
                </td>
                <td class="p-2 font-medium"><?php echo e($item->product->nama_produk ?? 'Produk Dihapus'); ?></td>
                <td class="p-2"><?php echo e($item->product->jenis_gorden ?? '-'); ?></td>
                <td class="p-2"><?php echo e($item->ukuran ?? '-'); ?></td>
                <td class="p-2 font-semibold">Rp<?php echo e(number_format($item->price, 0, ',', '.')); ?></td>
                <td class="p-2"><?php echo e($item->quantity); ?> pcs</td>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr>
                <td colspan="7" class="text-center p-2 text-gray-500">Tidak ada item dalam pesanan ini.</td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Ringkasan & Detail -->
        <div class="space-y-6">
          <div class="bg-white p-4 rounded shadow">
            <h2 class="font-semibold mb-2">Ringkasan Pesanan</h2>
            <p><strong>Status:</strong> <?php echo e($order->status); ?></p>
            <p><strong>Nama Pelanggan:</strong> <?php echo e($order->user->name); ?></p>
            <p><strong>Email:</strong> <?php echo e($order->user->email); ?></p>
            <p><strong>Telepon:</strong> <?php echo e($order->user->phone_number); ?></p>
          </div>

          <div class="bg-white p-4 rounded shadow">
            <h2 class="font-semibold mb-2">Detail Pengiriman</h2>
            <p><strong>Alamat:</strong> <?php echo e($order->user->address); ?></p>
            <p><strong>Metode:</strong> Standard</p>
            <p><strong>Biaya:</strong> Rp20.000</p>
          </div>

          <div class="bg-white p-4 rounded shadow">
            <h2 class="font-semibold mb-2">Detail Pembayaran</h2>
            <p><strong>Metode:</strong> <?php echo e($order->payment_type); ?></p>
            <p><strong>Subtotal:</strong> Rp<?php echo e(number_format($order->total - 20000, 0, ',', '.')); ?></p>
            <p><strong>Pengiriman:</strong> Rp20.000</p>
            <p class="font-bold mt-2">Total: Rp<?php echo e(number_format($order->total, 0, ',', '.')); ?></p>
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- Modal -->
  <div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
      <h3 class="text-lg font-medium text-gray-900 mb-4">Ubah Status Pesanan</h3>
      <form action="<?php echo e(route('admin.updateStatus', $order->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <select name="status" class="w-full px-3 py-2 border rounded mb-4">
          <option value="pesanan diterima" <?php echo e($order->status == 'pesanan diterima' ? 'selected' : ''); ?>>Pesanan Diterima</option>
          <option value="sedang diproses" <?php echo e($order->status == 'sedang diproses' ? 'selected' : ''); ?>>Sedang Diproses</option>
          <option value="selesai" <?php echo e($order->status == 'selesai' ? 'selected' : ''); ?>>Selesai</option>
        </select>
        <div class="flex justify-end">
          <button onclick="closeModal()" type="button" class="mr-2 px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
          <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openModal() { document.getElementById('statusModal').classList.remove('hidden'); }
    function closeModal() { document.getElementById('statusModal').classList.add('hidden'); }
  </script>
</body>
</html>
<?php /**PATH D:\Website\website-gorden\resources\views/Admin/DetailPemesanan.blade.php ENDPATH**/ ?>