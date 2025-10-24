<?php $__env->startSection('title', 'Beranda - Toko Gorden'); ?>

<?php $__env->startSection('content'); ?>
      <!-- Konten utama halaman -->
      <div class="px-40 flex flex-1 justify-center py-5">
        <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
          <!-- Container carousel -->
          <div
            id="carousel"
            class="relative left-1/2 right-1/2 -mx-[50vw] h-[480px] overflow-hidden rounded-none">
            <!-- Slides -->
            <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-100" data-carousel-item>
              <img
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuC-OdYAZ5UCG2ijQtzBUEivgFF8aUxfZEWpW0u_4MJZ6w9cU5VdfNzm6ZC7pKIYxv4yPJChctIPmhyX_L3Eco3rZ_30OYmXWyVtSfbzpMUtmQPtJBXagJg9Kl03Fpdj_0pZYUy1CtZW_3oTa7SFkTY1W-HJHj_abJZW7nG38zWQkqDjgMO2WEQMoWpRXwBQFabxl1w9ViCG-gzDKzPYht2fgYYZk8tzMwR0HmZ5I4yHi2cHtEFZPn9xth4DIglhz2Hsz-I8OIdZguc"
                alt="Slide 1"
                class="object-cover w-screen h-full" />
            </div>
            <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0" data-carousel-item>
              <img
                src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1470&q=80"
                alt="Slide 2"
                class="object-cover w-screen h-full" />
            </div>
            <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0" data-carousel-item>
              <img
                src="https://images.unsplash.com/photo-1522199710521-72d69614c702?auto=format&fit=crop&w=1471&q=80"
                alt="Slide 3"
                class="object-cover w-screen h-full" />
            </div>

            <!-- Overlay teks dan tombol kiri bawah -->
            <div class="absolute bottom-10 left-10 z-20 text-white max-w-4xl flex flex-col gap-2">
              <h1 class="text-4xl font-black leading-tight tracking-[-0.033em] md:text-5xl">
                Tirai Chic untuk Rumah Anda
              </h1>
              <p class="text-sm font-normal leading-normal md:text-base">
                Temukan koleksi tirai berkualitas tinggi yang dikurasi yang akan mengubah ruang hidup Anda.
              </p>
              <button
                class="mt-4 min-w-[84px] max-w-[480px] cursor-pointer rounded-full bg-[#667699] px-4 py-2 text-sm font-bold tracking-[0.015em] md:text-base hover:bg-[#556688]">
                Belanja Sekarang
              </button>
            </div>
          </div>
          <!-- Produk terbaru (SESUDAH - DINAMIS) -->
          <h2 class="text-[#141415] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Produk Terbaru</h2>

          <div class="flex overflow-y-auto [-ms-scrollbar-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
            <div class="flex items-stretch p-4 gap-3">
              <?php $__currentLoopData = $produkTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-60">
                <img src="<?php echo e(asset('uploads/' . $produk->gambar)); ?>" alt="<?php echo e($produk->nama_produk); ?>"
                  class="w-full h-40 object-cover rounded-md border transition-transform duration-200 hover:scale-105" />

                <p class="text-[#141415] text-base font-medium leading-normal"><?php echo e($produk->nama_produk); ?></p>
              </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          </div>

          <!-- Bagian penawaran khusus
        <h2 class="text-[#141415] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5"></h2>
        <div class="flex overflow-y-auto [-ms-scrollbar-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
          <div class="flex items-stretch p-4 gap-3">
            <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-60">
              <div
                class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl flex flex-col"
                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBu8phDAkaNCRjdEi09do23xfuRCwqZl6VtuOaCIjoRqAEzgt0W68t6EpddjoaPFIUbtxYHkKfOk4zpjExcnKnduDht8dLm5WsldjdsglHUSYgEdVslMx2AtcOhj5-x810d0NQjlTuJi0K96HgkeUbdVRoejy9cg7qPI5lZCEOaHl8CvUzVDKHiZiI3MJ83Tc_78l-t4Oby0ED8Dc3joQdE070BM-d3ay5BIPc1WVhv5hKLQ7J_cTF-EegC3qXhrM1hBQOPL_1dI5A");'></div>
              <p class="text-[#141415] text-base font-medium leading-normal">Diskon 20% untuk Semua Tirai</p>
            </div>
            <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-60">
              <div
                class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl flex flex-col"
                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuB1nj4V9jiFko7U8f6fqnperdQym8I0ZyE_7ANpk_bmMmYzfBV67rqG8rq16hp-qN2_5XlyvNFl4cv4IzX3Wq-MN2UsKXjYhJX20bEPFscvA4j2uU2mm0kZatfoegfomGk-0hB9vBpba-9cG-rgLAfVjmjSH_LxxXO36KfS7iC3_p_I83cT0ARx9gdGbrDer7dD-oqJ4a-iFCVwtLcBtsihUcbCZYCX9g7z7fe-LxLTf9uRZLC1vKAXangAK99Nkzsfnna1Y9YzZRg");'></div>
              <p class="text-[#141415] text-base font-medium leading-normal">Penawaran Terbatas: Beli 2 Gratis 1</p>
            </div>
            <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-60">
              <div
                class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-xl flex flex-col"
                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDKZ5l2vTlN_JL-jtUEUA-74oIvNRAo9vRG7KrudXPxWGjXLDDjF7HqNkuB2oPfoEm6qShL0IQMGMm4Z1OLwHuN7OBFUMZKt1E0LBGKcxw-ghJL9NVKiBeCSZ0LJLj9d9grZHrBRkzm6jqTvvTr_jiyukhQtmVY6ObzgmxBG5YksFEIJeWUvLuEOyJ-3DZGs7bYdCrk_d2tYANo96lIkV0CYbI3rRAzCiiuZlERXDE46li_DrU1Jx1DkW_NXYDpde8GuFeYm4MGREQ");'></div>
              <p class="text-[#141415] text-base font-medium leading-normal">Koleksi Baru: Tirai Bermotif</p>
            </div>
          </div>
        </div> -->
          <!-- Bagian galeri produk populer -->
          <h2 class="text-[#141415] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Kategori Populer</h2>
          <div class="flex overflow-y-auto [-ms-scrollbar-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
            <div class="flex items-stretch p-4 gap-3">
              <?php $__currentLoopData = $produkPopuler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-60">
                <img src="<?php echo e(asset('uploads/' . $produk->gambar)); ?>" alt="<?php echo e($produk->nama_produk); ?>"
                  class="w-full h-40 object-cover rounded-md border transition-transform duration-200 hover:scale-105" />

                <p class="text-[#141415] text-base font-medium leading-normal"><?php echo e($produk->nama_produk); ?></p>

                <!-- Rating Bintang -->
                <?php
                $rating = round($produk->reviews_avg_rating ?? 0, 1);
                $fullStars = floor($rating);
                $halfStar = ($rating - $fullStars) >= 0.5 ? true : false;
                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                ?>
                <div class="flex items-center space-x-1">
                  <?php for($i = 0; $i < $fullStars; $i++): ?>
                    <svg class="w-4 h-4 text-yellow-400 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M10 15l-5.878 3.09L5.5 11.545.618 7.91l6.682-.971L10 1l2.7 5.939 6.682.971-4.882 3.636 1.378 6.545z" />
                    </svg>
                    <?php endfor; ?>
                    <?php if($halfStar): ?>
                    <svg class="w-4 h-4 text-yellow-400 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                      <path d="M10 15l-5.878 3.09L5.5 11.545.618 7.91l6.682-.971L10 1v14z" />
                    </svg>
                    <?php endif; ?>
                    <?php for($i = 0; $i < $emptyStars; $i++): ?>
                      <svg class="w-4 h-4 text-gray-300 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                      <path d="M10 15l-5.878 3.09L5.5 11.545.618 7.91l6.682-.971L10 1l2.7 5.939 6.682.971-4.882 3.636 1.378 6.545z" />
                      </svg>
                      <?php endfor; ?>
                      <span class="text-sm text-gray-500 ml-1"><?php echo e($rating); ?>/5</span>
                </div>
              </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          </div>


          <section class="py-12 px-4 bg-white">
            <h2 class="text-lg font-semibold text-gray-800 text-center mb-2">Mengapa Memilih Kami?</h2>
            <h3 class="text-2xl font-bold text-gray-900 text-center mb-6">Keunggulan Kami</h3>
            <p class="text-center text-gray-600 max-w-xl mx-auto mb-10">
              Kami berkomitmen untuk memberikan pengalaman berbelanja gorden terbaik dengan produk berkualitas dan layanan memuaskan.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-5xl mx-auto">
              <!-- Pengiriman Cepat -->
              <div class="flex flex-col items-start p-5 border rounded-lg">
                <div class="text-2xl mb-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h11v4H3v-4zM16 6h3l2 3v5h-5V6zM6 18a2 2 0 100-4 2 2 0 000 4zm12-2a2 2 0 110 4 2 2 0 010-4z" />
                  </svg>
                </div>
                <h4 class="text-lg font-semibold text-gray-800 mb-1">Pengiriman Cepat</h4>
                <p class="text-gray-600 text-sm">Kami menjamin pengiriman cepat dan aman ke seluruh wilayah.</p>
              </div>

              <!-- Kualitas Terbaik -->
              <div class="flex flex-col items-start p-5 border rounded-lg">
                <div class="text-2xl mb-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 17.27L18.18 21 16.54 13.97 22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                  </svg>
                </div>
                <h4 class="text-lg font-semibold text-gray-800 mb-1">Kualitas Terbaik</h4>
                <p class="text-gray-600 text-sm">Produk kami terbuat dari bahan berkualitas tinggi dan tahan lama.</p>
              </div>

              <!-- Garansi Produk -->
              <div class="flex flex-col items-start p-5 border rounded-lg">
                <div class="text-2xl mb-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2L4 5v6c0 5.25 3.5 9.74 8 11 4.5-1.26 8-5.75 8-11V5l-8-3z" />
                  </svg>
                </div>
                <h4 class="text-lg font-semibold text-gray-800 mb-1">Garansi Produk</h4>
                <p class="text-gray-600 text-sm">Kami memberikan garansi untuk setiap produk yang Anda beli.</p>
              </div>
            </div>
          </section>
          
          <script>
            const slides = document.querySelectorAll('[data-carousel-item]');
            let current = 0;

            function showSlide(index) {
              slides.forEach((slide, i) => {
                slide.style.opacity = i === index ? '1' : '0';
                slide.style.zIndex = i === index ? '10' : '0';
              });
            }

            function nextSlide() {
              current = (current + 1) % slides.length;
              showSlide(current);
            }

            setInterval(nextSlide, 4000);
            showSlide(current);
          </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.Chat', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('layouts.Navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Website\website-gorden\resources\views/User/LandingPage.blade.php ENDPATH**/ ?>