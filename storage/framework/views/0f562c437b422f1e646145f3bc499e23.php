<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profil Pengguna</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>

<body class="bg-gray-100 min-h-screen p-8">

  <?php if(auth()->guard()->check()): ?>
  <main class="bg-white p-8 rounded-xl shadow-lg max-w-5xl mx-auto">
    <header class="mb-8">
      <a href="<?php echo e(route('landing')); ?>" class="flex items-center text-gray-600 hover:text-blue-600 text-sm font-medium mb-4">
        <span class="material-icons text-lg mr-1">arrow_back</span>
        Kembali
      </a>
      <h1 class="text-3xl font-bold text-gray-800">Profil Pengguna</h1>
      <p class="text-gray-600">Kelola informasi akun Anda</p>
    </header>

    <?php if(session('success')): ?>
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
      <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <section class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
      <!-- Data User -->
      <div class="md:col-span-2 space-y-6">
        <div>
          <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
          <input type="text" value="<?php echo e($user->name); ?>" readonly class="w-full p-3 border rounded-lg bg-gray-100" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" value="<?php echo e($user->email); ?>" readonly class="w-full p-3 border rounded-lg bg-gray-100" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
          <input type="text" value="<?php echo e($user->phone_number ?? '-'); ?>" readonly class="w-full p-3 border rounded-lg bg-gray-100" />
        </div>
      </div>

      <!-- Foto Profil -->
      <div class="flex flex-col items-center">
        <div class="relative">
          <img
            src="<?php echo e($user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name)); ?>"
            alt="Foto Profil"
            class="w-48 h-48 rounded-full object-cover shadow-md" />
        </div>
      </div>
    </section>

    <!-- Alamat -->
    <section class="border-t pt-6">
      <div class="flex justify-between items-start">
        <div class="flex gap-3">
          <span class="material-icons text-red-500 text-3xl">location_on</span>
          <div>
            <h2 class="text-lg font-semibold text-gray-800">Alamat Pelanggan</h2>
            <p class="text-gray-700">
              <?php echo e($user->name); ?>

              <span class="text-sm text-gray-500">(<?php echo e($user->phone_number ?? '-'); ?>)</span>
            </p>
            <p class="text-gray-600"><?php echo e($user->address ?? 'Belum diisi'); ?></p>
          </div>
        </div>
        <a href="<?php echo e(route('user.profile.edit')); ?>" class="flex items-center text-blue-600 hover:text-blue-700 text-sm font-medium">
          <span class="material-icons mr-1 text-base">edit</span> Edit
        </a>
      </div>
    </section>
  </main>

  <?php else: ?>
  <div class="text-center mt-32 text-gray-600 font-semibold">
    Anda belum login. <a href="<?php echo e(route('login')); ?>" class="text-indigo-600 hover:underline">Login di sini</a>.
  </div>
  <?php endif; ?>

</body>

</html><?php /**PATH D:\Website\website-gorden\resources\views/User/ProfilUser.blade.php ENDPATH**/ ?>