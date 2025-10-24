<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Lupa Kata Sandi</title>
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
            <!-- Bagian Form -->
            <div class="md:w-1/2 p-8 sm:p-12 flex flex-col justify-center">
                <a href="<?php echo e(route('login')); ?>" class="mb-6 flex items-center text-gray-600 hover:text-indigo-600 text-sm font-medium">
                    <span class="material-icons text-lg mr-1">arrow_back</span>
                    Kembali
                </a>
                <h1 class="text-3xl font-bold text-gray-800 mb-3">Lupa Kata Sandi? 🤔</h1>
                <p class="text-gray-600 mb-8 text-base">
                    Masukkan email kamu dan kami akan kirim instruksi untuk reset password.
                </p>

                <form method="POST" action="<?php echo e(route('password.email')); ?>">
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

                    <button
                        type="submit"
                        class="w-full py-3 rounded-lg font-semibold text-base bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300 ease-in-out">
                        Kirim Instruksi Reset
                    </button>

                    <?php if(session('status')): ?>
                    <div class="text-green-600 mt-4 text-sm">
                        <?php echo e(session('status')); ?>

                    </div>
                    <?php endif; ?>
                </form>


                <p class="text-center text-sm text-gray-600 mt-10">
                    Sudah ingat kata sandi?
                    <a href="/login" class="font-semibold text-indigo-600 hover:text-indigo-500 hover:underline">Masuk di sini</a>
                </p>

                <p class="text-center text-xs text-gray-400 mt-auto pt-10">
                    © 2025 TokoKita. Hak Cipta Dilindungi.
                </p>
            </div>

            <!-- Bagian Gambar -->
            <div class="md:w-1/2 hidden md:block relative">
                <img
                    alt="Tirai elegan"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuCKzAmWTwgQ-1aB2xD9zvI8naYghsLluLWE3TuOMcrLGBqxonIdumLvM63NIh0RJ1Thgg8UdrhWDcRL4ZyVeTpJdzNNOslhC8Kc4QD5QhHmMXt0GM3NJLTUe4xTdv_EWMAoVPdU29a8h9iww_7glQqzHQLcXEiJvXqMVhQg_yG0y5FrVqyp3cx6dyB4iTKztDUcVSFvZ2KHMZZ3tad-2pSdw1igFbI-H2UaMqaC5kNiF_zKfBLgAoqKiI36qefrSW7ZD8uGb8mF1UA"
                    class="object-cover w-full h-full" />
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-10 text-white">
                    <h2 class="text-3xl font-bold mb-3">Reset Password Lebih Mudah</h2>
                    <p class="text-base opacity-80">Kami siap bantu kamu kembali mengakses akunmu.</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html><?php /**PATH D:\Website\website-gorden\resources\views/User/LupaPassword.blade.php ENDPATH**/ ?>