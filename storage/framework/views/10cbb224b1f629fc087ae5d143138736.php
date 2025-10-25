<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Toko Gorden</title>
  <link rel="icon" type="image/x-icon" href="data:image/x-icon;base64," />

  <!-- Memuat Tailwind CSS dengan plugin forms dan container-queries -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <!-- Memuat Alpine.js untuk fungsionalitas interaktif seperti dropdown -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <!-- Memuat font dari Google Fonts: Noto Sans dan Spline Sans -->
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
  <link
    rel="stylesheet"
    as="style"
    onload="this.rel='stylesheet'"
    href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900&amp;family=Spline+Sans%3Awght%40400%3B500%3B700" />
</head>

<body>
  <!-- Kontainer utama untuk seluruh halaman, dengan styling Tailwind dan font kustom -->
  <div class="relative flex size-full min-h-screen flex-col bg-white group/design-root overflow-x-hidden" style='font-family: "Spline Sans", "Noto Sans", sans-serif;'>
    <div class="layout-container flex h-full grow flex-col">
      <!-- Header halaman dengan navigasi dan elemen pencarian/login -->
      <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#f2f2f3] px-10 py-3">
        <div class="flex items-center gap-8">
          <!-- Logo dan nama situs -->
          <div class="flex items-center gap-4 text-[#141415]">
            <div class="size-4">
              <!-- SVG untuk ikon logo -->
              <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M44 11.2727C44 14.0109 39.8386 16.3957 33.69 17.6364C39.8386 18.877 44 21.2618 44 24C44 26.7382 39.8386 29.123 33.69 30.3636C39.8386 31.6043 44 33.9891 44 36.7273C44 40.7439 35.0457 44 24 44C12.9543 44 4 40.7439 4 36.7273C4 33.9891 8.16144 31.6043 14.31 30.3636C8.16144 29.123 4 26.7382 4 24C4 21.2618 8.16144 18.877 14.31 17.6364C8.16144 16.3957 4 14.0109 4 11.2727C4 7.25611 12.9543 4 24 4C35.0457 4 44 7.25611 44 11.2727Z"
                  fill="currentColor"></path>
              </svg>
            </div>
            <h2 class="text-[#141415] text-lg font-bold leading-tight tracking-[-0.015em]">Tirai Indah</h2>
          </div>
          <!-- Tautan navigasi utama -->
          <div class="flex items-center gap-9">
            <a class="text-[#141415] text-sm font-medium leading-normal" href="<?php echo e(route('landing')); ?> ">Beranda</a>
            <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(route('produk.user')); ?>" class="text-[#141415] text-sm font-medium leading-normal">Produk</a>
            <?php else: ?>
            <a href="<?php echo e(route('login')); ?>" onclick="alert('Silakan login terlebih dahulu untuk melihat produk.')" class="text-[#141415] text-sm font-medium leading-normal">Produk</a>
            <?php endif; ?>
            <a href="<?php echo e(route('tentangkami')); ?>" class="text-[#141415] text-sm font-medium leading-normal" href="#">Tentang Kami</a>
            <a href="<?php echo e(route('pesanan.user')); ?>" class="text-[#141415] text-sm font-medium leading-normal" href="#">Pemesanan</a>
          </div>
        </div>
        <div class="flex flex-1 justify-end gap-8">
          <!-- Form pencarian -->
          <?php if(auth()->guard()->check()): ?>
          <form method="GET" action="<?php echo e(route('produk.user')); ?>" class="mr-6">
            <label class="flex flex-col min-w-40 !h-10 max-w-64">
              <div class="flex w-full flex-1 items-stretch rounded-xl h-full">
                <div class="text-[#71747a] flex border-none bg-[#f2f2f3] items-center justify-center pl-4 rounded-l-xl border-r-0">
                  <!-- SVG ikon kaca pembesar -->
                  <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                    <path d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z" />
                  </svg>
                </div>
                <input
                  type="text"
                  name="q"
                  placeholder="Cari produk..."
                  class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#141415] focus:outline-0 focus:ring-0 border-none bg-[#f2f2f3] focus:border-none h-full placeholder:text-[#71747a] px-4 rounded-l-none border-l-0 pl-2 text-base font-normal leading-normal"
                  value="<?php echo e(request('q')); ?>" />
              </div>
            </label>
          </form>
          <?php else: ?>
          <?php
          $redirectToLogin = "event.preventDefault(); window.location.href = '" . route('login') . "';";
          ?>
          <form method="GET" action="<?php echo e(route('produk.user')); ?>" onsubmit="<?php echo e($redirectToLogin); ?>" class="mr-6">
            <label class="flex flex-col min-w-40 !h-10 max-w-64">
              <div class="flex w-full flex-1 items-stretch rounded-xl h-full">
                <div class="text-[#71747a] flex border-none bg-[#f2f2f3] items-center justify-center pl-4 rounded-l-xl border-r-0">
                  <!-- SVG ikon kaca pembesar -->
                  <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                    <path d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z" />
                  </svg>
                </div>
                <input
                  type="text"
                  name="q"
                  placeholder="Cari produk..."
                  class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#141415] focus:outline-0 focus:ring-0 border-none bg-[#f2f2f3] focus:border-none h-full placeholder:text-[#71747a] px-4 rounded-l-none border-l-0 pl-2 text-base font-normal leading-normal" />
              </div>
            </label>
          </form>
          <?php endif; ?>
        </div>



        <div class="flex gap-2">
          <!-- Logika Blade untuk menampilkan tombol login atau dropdown profil pengguna -->
          <?php if(auth()->guard()->check()): ?>
          <!-- Jika pengguna sudah login, tampilkan nama pengguna dan dropdown -->
          <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center gap-2 bg-gray-100 rounded-full px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
              <!-- Icon Pengguna (SVG) -->
              <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 256 256">
                <path d="M230.92,212c-15.23-26.33-38.7-45.21-66.09-54.16a72,72,0,1,0-73.66,0C63.78,166.78,40.31,185.66,25.08,212a8,8,0,1,0,13.85,8c18.84-32.56,52.14-52,89.07-52s70.23,19.44,89.07,52a8,8,0,1,0,13.85-8ZM72,96a56,56,0,1,1,56,56A56.06,56.06,0,0,1,72,96Z"></path>
              </svg>
              <span><?php echo e(auth()->user()->name); ?></span>
            </button>

            <!-- Dropdown menu untuk pengguna yang sudah login -->
            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 py-2">
              <a href="<?php echo e(route('user.profile')); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>

              <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Logout</button>
              </form>
            </div>
          </div>
          <?php else: ?>
          <!-- Jika pengguna belum login, tampilkan tombol login -->
          <a href="<?php echo e(route('login')); ?>" class="flex items-center gap-2 bg-gray-100 rounded-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-200">
            <!-- Icon Pengguna (SVG) -->
            <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 256 256">
              <path d="M230.92,212c-15.23-26.33-38.7-45.21-66.09-54.16a72,72,0,1,0-73.66,0C63.78,166.78,40.31,185.66,25.08,212a8,8,0,1,0,13.85,8c18.84-32.56,52.14-52,89.07-52s70.23,19.44,89.07,52a8,8,0,1,0,13.85-8ZM72,96a56,56,0,1,1,56,56A56.06,56.06,0,0,1,72,96Z"></path>
            </svg>
            <span>Login</span>
          </a>
          <?php endif; ?>
          <!-- Akhir dari logika Blade untuk login/profil -->

          <a href="<?php echo e(route('keranjang.tampil')); ?>" class="relative inline-block">
            <div class="bg-white p-2 rounded-full shadow">
              <svg class="w-6 h-6 text-black" fill="currentColor" viewBox="0 0 256 256">
                <path d="M216 40H40A16 16 0 0 0 24 56v144a16 16 0 0 0 16 16h176a16 16 0 0 0 16-16V56a16 16 0 0 0-16-16Zm0 160H40V56h176ZM176 88a48 48 0 0 1-96 0 8 8 0 0 1 16 0 32 32 0 0 0 64 0 8 8 0 0 1 16 0Z" />
              </svg>

              <?php if($jumlahKeranjang > 0): ?>
              <span class="absolute -top-1 -right-1 bg-black text-white text-xs font-bold rounded-full px-1.5 py-0.5 shadow-md">
                <?php echo e($jumlahKeranjang); ?>

              </span>
              <?php endif; ?>
            </div>
          </a>
      </header>

      <!-- Konten Utama -->
      <main class="flex-grow px-6 py-6 max-w-7xl mx-auto">
        <?php echo $__env->yieldContent('content'); ?>
      </main>

      <!-- Footer -->
      <footer class="border-t text-center py-4 text-sm text-gray-500">
        &copy; <?php echo e(date('Y')); ?> Toko Gorden.
      </footer>

      <?php echo $__env->yieldContent('scripts'); ?>
</body>

</html><?php /**PATH D:\Website\website-gorden\resources\views/layouts/Navbar.blade.php ENDPATH**/ ?>