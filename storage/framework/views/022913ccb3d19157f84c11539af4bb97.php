<?php $__env->startSection('title', 'Pesanan - Toko Gorden'); ?>

<?php $__env->startSection('content'); ?>

      <!-- Main content -->
      <main class="px-10 py-8">
        <h1 class="text-2xl font-bold mb-6">Pesanan Saya</h1>
        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left border rounded-xl overflow-hidden">
            <thead class="bg-gray-50 border-b">
              <tr>
                <th class="px-4 py-3 font-medium text-gray-900">Kode</th>
                <th class="px-4 py-3 font-medium text-gray-900">Tanggal</th>
                <th class="px-4 py-3 font-medium text-gray-900">Status</th>
                <th class="px-4 py-3 font-medium text-gray-900">Total</th>
                <th class="px-4 py-3 font-medium text-gray-900">Jumlah Barang</th>
                <th class="px-4 py-3 font-medium text-gray-900">Jenis Gorden</th>
              </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-t">
                  <td class="px-4 py-3">#<?php echo e($order->kode_pesanan); ?></td>
                  <td class="px-4 py-3 text-gray-600"><?php echo e($order->created_at->translatedFormat('d F Y')); ?></td>
                  <td class="px-4 py-3">
                    <span class="inline-block bg-gray-100 px-4 py-1 rounded-full capitalize"><?php echo e($order->status); ?></span>
                  </td>
                  <td class="px-4 py-3 text-gray-600">Rp<?php echo e(number_format($order->total, 0, ',', '.')); ?></td>
                  <td class="px-4 py-3 text-gray-600"><?php echo e($order->items->sum('kuantitas')); ?> barang</td>
                  <td class="px-4 py-3 text-gray-600">
                    <?php echo e($order->items->pluck('jenis_gorden')->unique()->join(', ')); ?>

                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                  <td colspan="6" class="text-center py-6 text-gray-500">Belum ada pesanan.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </main>

    </div>
  </div>
   
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.Chat', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('layouts.Navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Website\website-gorden\resources\views/Produk/PesananUser.blade.php ENDPATH**/ ?>