<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Register</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body class="bg-gray-100">
  <div class="min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-lg rounded-lg flex overflow-hidden max-w-4xl w-full">
      <div class="w-full md:w-1/2 p-8 md:p-12">

        <!-- Tombol Kembali ke Login -->
        <a href="{{ route('login') }}" class="mb-6 flex items-center text-gray-600 hover:text-blue-600 text-sm font-medium">
          <span class="material-icons text-lg mr-1">arrow_back</span>
          Kembali
        </a>

        <h1 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang 👋</h1>
        <p class="text-gray-600 mb-8">Buat akun untuk menikmati pengalaman belanja yang lebih mudah.</p>
        
        <form method="POST" action="{{ route('register') }}">
          @csrf
          <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input
              id="name"
              name="name"
              type="text"
              placeholder="Nama Lengkap"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>

          <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input
              id="email"
              name="email"
              type="email"
              placeholder="Example@email.com"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>

          <div class="mb-6">
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
            <input
              id="phone"
              name="phone"
              type="tel"
              placeholder="Nomor Telepon"
              class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>

          <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input
              id="password"
              name="password"
              type="password"
              placeholder="At least 8 characters"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>

          <div class="mb-8">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
            <input
              id="password_confirmation"
              name="password_confirmation"
              type="password"
              placeholder="At least 8 characters"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>

          <button
            type="submit"
            class="w-full bg-gray-900 text-white py-3 rounded-md hover:bg-gray-800 transition duration-300 font-semibold"
          >
            Daftar
          </button>
        </form>

        <p class="text-center text-gray-600 mt-8">
          Sudah punya akun? 
          <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">Masuk</a>
        </p>
      </div>
      <div class="hidden md:block w-1/2">
        <img
          alt="Modern living room with curtains and a sofa"
          class="w-full h-full object-cover"
          src="https://lh3.googleusercontent.com/aida-public/AB6AXuBY-1Kk5acGT0F00cC0LXPq847sE0G4SWzUY0_wyjWlRgFozs1K5iEIt17ytF4D6bOtnMYk5_5uP9JY6aUfh__KUdzYdX2xMpbM1VWHWCMu24ot1jQPsy1YjT3DYXsdfhZVfFjo6Sb7tvEUyDNFqTV-1u9oZD_3vPB_AJVeXU7cujx6t3-bWXpVMS8J3OJaBpMxfbNdvFEZyKaAr5H9PokuMvBy7Mw8iOCa_E9UwV-ntXmFzNn5FcBWXTDiRsUfWWJHvDTmp809TiE"
        />
      </div>
    </div>
  </div>
</body>
</html>
