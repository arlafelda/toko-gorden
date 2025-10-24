<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Edit Profil</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <main class="flex-1 p-8 overflow-auto">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-2xl font-bold text-gray-800">Edit Profil</h1>
                <p class="text-gray-600 mt-1">Kelola informasi Anda untuk mengontrol, melindungi, dan mengamankan akun</p>

                
                <?php if(session('success')): ?>
                <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <?php echo e(session('success')); ?>

                </div>
                <?php endif; ?>

                
                <?php if($errors->any()): ?>
                <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <ul class="list-disc list-inside">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form action="<?php echo e(route('user.profile.update')); ?>" method="POST" class="mt-8 bg-white p-6 rounded-lg shadow-md">
                    <?php echo csrf_field(); ?>

                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Kontak</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" name="name" id="name" placeholder="Masukkan Nama Lengkap"
                                value="<?php echo e(old('name', $user->name)); ?>" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-lime-500 focus:border-lime-500 sm:text-sm" />
                        </div>

                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                            <input type="tel" name="phone_number" id="phone_number" placeholder="Masukkan Nomor Telepon"
                                value="<?php echo e(old('phone_number', $user->phone_number)); ?>"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-lime-500 focus:border-lime-500 sm:text-sm" />
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold text-gray-700 mt-8 mb-4">Alamat</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kabupaten/Kota -->
                        <div>
                            <label for="kota" class="block text-sm font-medium text-gray-700">Kabupaten/Kota</label>
                            <select name="kota" id="kota" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-lime-500 focus:border-lime-500 sm:text-sm">
                                <option value="Denpasar" <?php echo e(old('kota', $user->kota) == 'Denpasar' ? 'selected' : ''); ?>>Denpasar</option>
                            </select>
                        </div>

                        <!-- Kecamatan -->
                        <div>
                            <label for="kecamatan" class="block text-sm font-medium text-gray-700">Kecamatan</label>
                            <select name="kecamatan" id="kecamatan" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-lime-500 focus:border-lime-500 sm:text-sm">
                                <?php
                                $kecamatans = ['Denpasar Barat', 'Denpasar Timur', 'Denpasar Utara', 'Denpasar Selatan'];
                                ?>
                                <?php $__currentLoopData = $kecamatans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kecamatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($kecamatan); ?>" <?php echo e(old('kecamatan', $user->kecamatan) == $kecamatan ? 'selected' : ''); ?>>
                                    <?php echo e($kecamatan); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Desa -->
                        <div>
                            <label for="desa" class="block text-sm font-medium text-gray-700">Desa</label>
                            <select name="desa" id="desa" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-lime-500 focus:border-lime-500 sm:text-sm">
                                <option value="">-- Pilih Kecamatan Terlebih Dahulu --</option>
                            </select>
                        </div>

                        <!-- Jalan -->
                        <div>
                            <label for="jalan" class="block text-sm font-medium text-gray-700">Jalan</label>
                            <input type="text" name="jalan" id="jalan" placeholder="Masukkan nama jalan"
                                value="<?php echo e(old('jalan', $user->jalan)); ?>" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-lime-500 focus:border-lime-500 sm:text-sm" />
                        </div>
                    </div>
                    <div class="mt-8 flex flex-col md:flex-row justify-end gap-4">
                        <!-- Tombol Batal -->
                        <a href="<?php echo e(route('user.profile')); ?>"
                            class="w-full md:w-auto bg-gray-300 text-gray-800 py-2.5 px-6 rounded-md text-center shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 font-semibold">
                            Batal
                        </a>

                        <!-- Tombol Simpan -->
                        <button type="submit"
                            class="w-full md:w-auto bg-lime-500 text-white py-2.5 px-6 rounded-md shadow-sm hover:bg-lime-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lime-500 font-semibold">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </main>
    </div>

    <!-- SCRIPT DESA OTOMATIS -->
    <script>
        const desaOptions = {
            "Denpasar Barat": ["Dauh Puri", "Dauh Puri Kauh", "Dauh Puri Kaja", "Pemecutan", "Padangsambian", "Padangsambian Kelod"],
            "Denpasar Timur": ["Sumerta", "Sumerta Kelod", "Sumerta Kaja", "Kesiman", "Kesiman Kertalangu"],
            "Denpasar Selatan": ["Panjer", "Sesetan", "Pedungan", "Sidakarya", "Renon", "Sanur", "Sanur Kauh", "Sanur Kaja"],
            "Denpasar Utara": ["Ubung", "Ubung Kaja", "Peguyangan", "Peguyangan Kangin", "Peguyangan Kaja", "Dangin Puri", "Dangin Puri Kangin", "Dangin Puri Kaja"]
        };

        const kecamatanSelect = document.getElementById("kecamatan");
        const desaSelect = document.getElementById("desa");

        kecamatanSelect.addEventListener("change", function() {
            const selectedKecamatan = this.value;
            const desaList = desaOptions[selectedKecamatan] || [];

            desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';
            desaList.forEach(desa => {
                const option = document.createElement("option");
                option.value = desa;
                option.textContent = desa;
                desaSelect.appendChild(option);
            });
        });

        window.addEventListener("DOMContentLoaded", function() {
            const currentKecamatan = "<?php echo e(old('kecamatan', $user->kecamatan)); ?>";
            const currentDesa = "<?php echo e(old('desa', $user->desa)); ?>";

            if (currentKecamatan && desaOptions[currentKecamatan]) {
                desaOptions[currentKecamatan].forEach(desa => {
                    const option = document.createElement("option");
                    option.value = desa;
                    option.textContent = desa;
                    if (desa === currentDesa) option.selected = true;
                    desaSelect.appendChild(option);
                });
            }
        });
    </script>
</body>

</html><?php /**PATH D:\Website\website-gorden\resources\views/User/EditProfile.blade.php ENDPATH**/ ?>