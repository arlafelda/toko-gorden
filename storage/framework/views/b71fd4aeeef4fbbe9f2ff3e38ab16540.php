<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
  <style type="text/tailwindcss">
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

  <div class="container mx-auto px-4">
    <div class="bg-white shadow-xl rounded-lg overflow-hidden flex flex-col md:flex-row max-w-4xl mx-auto">
      
      <!-- Form login -->
      <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-4" style="font-family: 'Times New Roman', Times, serif;">
          Selamat Datang
        </h1>
        <p class="text-gray-600 mb-8">
          Akses Website Admin pemesanan dan penjualan gorden.
        </p>

        
        <?php if($errors->any()): ?>
          <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc pl-5 text-sm">
              <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
          </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('admin.login')); ?>">
          <?php echo csrf_field(); ?>
          <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input
              type="email"
              id="email"
              name="email"
              placeholder="Masukkan email Anda"
              class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400"
              required
              autofocus
            />
          </div>

          <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input
              type="password"
              id="password"
              name="password"
              placeholder="Masukkan password Anda"
              class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400"
              required
            />
          </div>

          <button
            type="submit"
            class="w-full bg-gray-800 hover:bg-gray-900 text-white font-semibold py-3 px-4 rounded-md transition duration-300 ease-in-out flex items-center justify-center"
          >
            Sign In
          </button>
        </form>
      </div>

      <!-- Gambar samping -->
      <div class="w-full md:w-1/2 h-64 md:h-auto">
        <img
          src="https://lh3.googleusercontent.com/aida-public/AB6AXuC443_z3M2WeOMIUBYx5oZI6g42rvTlKP03aNEBY973US6wJFX_BgIczAHEUQV1wuEyKAGoVinids34rGtBPfBJrGlASrMe2N6LfO7eLKRpB-8XKdIqhtLlSesHmURttZw3y7OJ9m2RwtyZ9viWcp8lmJb6TB4KsJlii9XsydqGQ9-5bYcPXhHcMWqwFgSG0lCQ1MGwEilPMbRUWi4kPIX9af3OiWoOQCcrQJYV6nxGkVPF8Dsree5E2J0CyRoDiYyE-kmFm43J8Odc"
          alt="Gambar tirai"
          class="object-cover w-full h-full"
        />
      </div>
    </div>
  </div>

</body>
</html>
<?php /**PATH D:\Website\website-gorden\resources\views/Admin/LoginAdmin.blade.php ENDPATH**/ ?>