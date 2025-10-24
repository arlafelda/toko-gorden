<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Halaman Transaksi</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo e(config('midtrans.client_key')); ?>"></script>
</head>

<body class="bg-gray-100 text-gray-800">
  <div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow space-y-6">

    <!-- Alamat Pengiriman -->
    <div>
      <h2 class="text-xl font-bold mb-2">Alamat Pengiriman</h2>
      <div class="bg-gray-50 p-4 rounded border">
        <p class="text-gray-800 font-semibold"><?php echo e(Auth::user()->name); ?></p>
        <p class="text-sm text-gray-700"><?php echo e(Auth::user()->phone_number); ?></p>
        <p class="text-sm text-gray-700" id="alamat-text">
          <?php echo e(Auth::user()->address ?: '-'); ?>

        </p>
      </div>
    </div>

    <!-- Detail Transaksi -->
    <div>
      <h2 class="text-2xl font-bold mb-4">Detail Transaksi</h2>

      <?php if(session('error')): ?>
        <div class="mb-4 bg-red-100 text-red-700 px-4 py-2 rounded">
          <?php echo e(session('error')); ?>

        </div>
      <?php endif; ?>

      <!-- Daftar Produk -->
      <div class="overflow-x-auto mt-4">
        <table class="min-w-full border border-gray-200 rounded">
          <thead class="bg-gray-100 text-gray-700">
            <tr>
              <th class="py-2 px-4 text-left">Produk</th>
              <th class="py-2 px-4 text-left">Ukuran</th>
              <th class="py-2 px-4 text-left">Jumlah</th>
              <th class="py-2 px-4 text-left">Harga</th>
              <th class="py-2 px-4 text-left">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr class="border-t">
                <td class="py-3 px-4 flex items-center gap-3">
                  <img src="<?php echo e(asset('uploads/' . $item->product->gambar)); ?>" class="w-16 h-16 object-cover rounded" alt="gambar produk">
                  <div>
                    <p class="font-semibold"><?php echo e($item->product->nama_produk); ?></p>
                    <p class="text-sm text-gray-500">Jenis: <?php echo e($item->product->jenis_gorden); ?></p>
                  </div>
                </td>
                <td class="py-3 px-4"><?php echo e($item->ukuran ?? '-'); ?></td>
                <td class="py-3 px-4"><?php echo e($item->quantity); ?></td>
                <td class="py-3 px-4">Rp<?php echo e(number_format($item->harga ?? $item->product->harga, 0, ',', '.')); ?></td>
                <td class="py-3 px-4 font-semibold text-green-600">
                  Rp<?php echo e(number_format(($item->harga ?? $item->product->harga) * $item->quantity, 0, ',', '.')); ?>

                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>

      <!-- Total -->
      <div class="mt-6 flex justify-between items-center border-t pt-4">
        <h3 class="text-lg font-semibold">Total Bayar:</h3>
        <h3 class="text-xl font-bold text-green-700">Rp<?php echo e(number_format($total, 0, ',', '.')); ?></h3>
      </div>

      <!-- Tombol Bayar & Batal -->
      <div class="mt-6 flex justify-end gap-4">
        <a href="<?php echo e(route('keranjang.tampil')); ?>" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded shadow">
          Batal
        </a>
        <button
          id="pay-button"
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded shadow"
          data-alamat="<?php echo e(Auth::user()->address); ?>"
        >
          Bayar Sekarang
        </button>
      </div>
    </div>
  </div>

  <!-- Data total -->
  <div id="transaksi-data" data-amount="<?php echo e($total); ?>"></div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const csrfToken = "<?php echo e(csrf_token()); ?>";
      const amount = parseInt(document.getElementById("transaksi-data").dataset.amount);
      const payBtn = document.getElementById("pay-button");

      payBtn.addEventListener("click", function () {
        const alamat = payBtn.dataset.alamat?.trim();

        if (!alamat) {
          alert("Alamat pengiriman belum diisi. Silakan lengkapi profil Anda terlebih dahulu.");
          window.location.href = "<?php echo e(route('user.profile.edit')); ?>";
          return;
        }

        fetch("<?php echo e(url('/midtrans/token')); ?>", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken
          },
          body: JSON.stringify({
            amount: amount
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.token) {
            snap.pay(data.token, {
              onSuccess: function (result) {
                alert("Pembayaran berhasil!");
                console.log(result);
              },
              onPending: function (result) {
                alert("Menunggu pembayaran.");
                console.log(result);
              },
              onError: function (result) {
                alert("Terjadi kesalahan saat pembayaran.");
                console.error(result);
              },
              onClose: function () {
                alert("Transaksi dibatalkan.");
              }
            });
          } else {
            alert("Gagal mendapatkan token pembayaran.");
            console.error(data);
          }
        })
        .catch(error => {
          alert("Terjadi kesalahan saat koneksi ke server.");
          console.error(error);
        });
      });
    });
  </script>
</body>
</html>
<?php /**PATH D:\Website\website-gorden\resources\views/Produk/Transaksi.blade.php ENDPATH**/ ?>