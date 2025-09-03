<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Ubah Kata Sandi</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Poppins', 'sans-serif']
          },
          keyframes: {
            'fade-in': {
              '0%': { opacity: '0', transform: 'translateY(-10px)' },
              '100%': { opacity: '1', transform: 'translateY(0)' }
            },
            'fade-out': {
              '0%': { opacity: '1' },
              '100%': { opacity: '0' }
            }
          },
          animation: {
            'fade-in': 'fade-in 0.5s ease-out forwards',
            'fade-out': 'fade-out 0.5s ease-in forwards'
          }
        }
      }
    }
  </script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen px-4 font-sans">
  <div class="bg-white shadow-xl rounded-2xl flex flex-col md:flex-row w-full max-w-4xl overflow-hidden">
    <!-- Form Section -->
    <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
      <h1 class="text-2xl font-bold mb-2">Ubah Kata Sandi 🔒</h1>
      <p class="text-gray-600 mb-6">Masukkan kata sandi baru untuk akun kamu</p>

      <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi Baru</label>
          <input type="password" name="password" required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="••••••••" />
        </div>

        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Kata Sandi</label>
          <input type="password" name="password_confirmation" required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="••••••••" />
        </div>

        <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-300">
          Ubah Kata Sandi
        </button>
      </form>

      <p class="text-center mt-6 text-sm text-gray-600">
        Kembali ke <a href="/login" class="text-blue-600 hover:underline">Login</a>
      </p>
    </div>

    <!-- Image Section -->
    <div class="hidden md:block w-full md:w-1/2">
      <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDJq4EB4tlSSAUQVZfH_nXEMJcNqaGLNTKbArss-adxHQeWLXRFXOtE6su5XbnQ2etUgmHae78UA4AMd91US15S845MhreIT9DAX6R7UvS1PT47X0JlQhDrOWQ6WjPexCy5raz_Aw_yvVZOatcnDT0YVTDJGjMTtjq0N77GPG3wwcB6-CX0IluyyqaseDLsNHIJqsPsMolmR5eE-oRHxhYpyKKViB74VSLb1tgJCoXZr0iEoXMF4JrRy52dtF4-RlLZNUBtddGofiM"
        alt="Ilustrasi ruangan modern" class="object-cover w-full h-full" />
    </div>
  </div>

  <!-- Flash Notification -->
  @if (session('status'))
    <div id="popup-success"
      class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white text-sm font-medium px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in">
      {{ session('status') }}
    </div>

    <script>
      setTimeout(() => {
        const popup = document.getElementById('popup-success');
        if (popup) {
          popup.classList.remove('animate-fade-in');
          popup.classList.add('animate-fade-out');
          setTimeout(() => popup.remove(), 500);
        }
      }, 3000);
    </script>
  @endif
</body>

</html>
