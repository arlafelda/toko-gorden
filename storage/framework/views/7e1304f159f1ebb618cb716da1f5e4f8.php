<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Login Page</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <link href="https://fonts.googleapis.com" rel="preconnect" />
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>

<body class="bg-white flex items-center justify-center min-h-screen p-4">
  <div class="bg-white rounded-xl shadow-2xl overflow-hidden max-w-4xl w-full">
    <div class="md:flex">
      <div class="md:w-1/2 p-8 sm:p-12 flex flex-col justify-center">
        <!-- 🔙 Tombol Kembali -->
        <a href="/" class="mb-6 flex items-center text-gray-600 hover:text-indigo-600 text-sm font-medium">
          <span class="material-icons text-lg mr-1">arrow_back</span>
          Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-800 mb-3">Selamat Datang Kembali! 👋</h1>
        <p class="text-gray-600 mb-8 text-base">Masuk untuk melanjutkan petualangan belanjamu.</p>

        <form method="POST" action="<?php echo e(route('login')); ?>">
          <?php echo csrf_field(); ?>
          <div class="mb-5">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Email</label>
            <input
              type="email"
              id="email"
              name="email"
              placeholder="cth: pengguna@mail.com"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none bg-gray-50 text-sm placeholder-gray-400 transition-colors duration-300" />
          </div>

          <div class="mb-5">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Kata Sandi</label>
            <input
              type="password"
              id="password"
              name="password"
              placeholder="Minimal 8 karakter"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none bg-gray-50 text-sm placeholder-gray-400 transition-colors duration-300" />
          </div>

          <div class="text-right mb-6">
            <a href="<?php echo e(route('password.request')); ?>" class="text-sm text-indigo-600 hover:text-indigo-500 hover:underline font-medium">Lupa Kata Sandi?</a>
          </div>

          <button
            type="submit"
            class="w-full py-3 rounded-lg font-semibold text-base bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300 ease-in-out">
            Masuk
          </button>
        </form>

        <div class="my-8 flex items-center">
          <hr class="w-full border-gray-300" />
          <span class="px-3 text-gray-500 text-sm font-medium">ATAU</span>
          <hr class="w-full border-gray-300" />
        </div>

        <a href="<?php echo e(route('google.login')); ?>"
          class="w-full py-3 rounded-lg font-semibold flex items-center justify-center text-sm bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 transition-all duration-300">
          <svg class="mr-3 h-5 w-5" viewBox="0 0 24 24">
            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
          </svg>
          Masuk dengan Google
        </a>


        <p class="text-center text-sm text-gray-600 mt-10">
          Belum punya akun?
          <a href="<?php echo e(route('register')); ?>" class="font-semibold text-indigo-600 hover:text-indigo-500 hover:underline">Daftar sekarang</a>
        </p>

        <p class="text-center text-xs text-gray-400 mt-auto pt-10">
          © 2025 TokoKita. Hak Cipta Dilindungi.
        </p>
      </div>

      <div class="md:w-1/2 hidden md:block relative">
        <img
          alt="Modern living room with beige curtains and comfortable furniture"
          src="https://lh3.googleusercontent.com/aida-public/AB6AXuDtjMiJphI5OH3oaKKSizWStoCoLQdi7mDxcz45ffbLYB4ASMIagVE42SF_H04b7s1rCjXMZmu6-VhiJFDM82O1Aq3t9r--PSuuZRXJEWP40gPtMdTfz1XN2VvOoKCzvgO1sADgV7KBuRvUprASOKWVlm-vYS8mEx9u_lVzADv_q1C-iRBIUZtUysmsSy-H2d66gDbzy4eNQsm1QojAF5PId5JnC3EF69D8J84VwWADdebi1WqflmaWYXHQvqNTcy2rE0_sQBDJ9pc"
          class="object-cover w-full h-full" />
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 to-transparent"></div>
        <div class="absolute bottom-0 left-0 p-10 text-white">
          <h2 class="text-3xl font-bold mb-3">Belanja Lebih Mudah, Kapan Saja, Di Mana Saja.</h2>
          <p class="text-base opacity-80">Temukan ribuan produk berkualitas dengan harga terbaik hanya di TokoKita.</p>
        </div>
      </div>
    </div>
  </div>
</body>

</html><?php /**PATH D:\Website\website-gorden\resources\views/User/loginUser.blade.php ENDPATH**/ ?>