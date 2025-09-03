<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ $product->nama_produk }} - Detail Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
</head>

<body class="bg-gray-100 font-sans">
    <div class="container mx-auto p-4">
        <!-- Notifikasi Berhasil -->
        @if(session('success'))
        <div class="fixed top-5 right-5 z-50 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow" role="alert" id="notifSuccess">
            <strong class="font-bold">Berhasil!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>

        <script>
            setTimeout(() => {
                const notif = document.getElementById('notifSuccess');
                if (notif) notif.style.display = 'none';
            }, 3000);
        </script>
        @endif

        <header class="flex items-center mb-6">
            <a href="{{ route('produk.user') }}" class="text-gray-600 hover:text-gray-800 flex items-center">
                <span class="material-icons">arrow_back</span>
                <span class="ml-1">Kembali</span>
            </a>
        </header>

        <div class="flex flex-col md:flex-row bg-white rounded-lg shadow-md p-6">
            <div class="w-full md:w-1/2">
                <img alt="{{ $product->nama_produk }}" class="w-full h-auto rounded-lg"
                    src="{{ asset('uploads/' . $product->gambar) }}" />
            </div>
            <div class="w-full md:w-1/2 md:pl-8 mt-6 md:mt-0">
                <h1 class="text-3xl font-bold text-gray-800">{{ $product->nama_produk }}</h1>
                @php
                $rating = round($product->averageRating(), 1);
                @endphp
                <div class="flex items-center mt-1">
                    @for ($i = 1; $i <= 5; $i++)    
                        @if($rating>= $i)
                        <!-- Bintang Penuh -->
                        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                            <path d="M12 .587l3.668 7.431L24 9.168l-6 5.847L19.335 24 12 19.897 4.665 24 6 15.015 0 9.168l8.332-1.15z" />
                        </svg>
                        @elseif($rating >= ($i - 0.5))
                        <!-- Bintang Setengah -->
                        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                            <path d="M12 .587l3.668 7.431L24 9.168l-6 5.847L19.335 24 12 19.897V.587z" />
                        </svg>
                        @else
                        <!-- Bintang Kosong -->
                        <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 24 24">
                            <path d="M12 .587l3.668 7.431L24 9.168l-6 5.847L19.335 24 12 19.897 4.665 24 6 15.015 0 9.168l8.332-1.15z" />
                        </svg>
                        @endif
                        @endfor
                        <span class="text-sm text-gray-600 ml-2">{{ $rating }}/5</span>
                </div>

                <p id="hargaUtama" class="text-4xl font-bold text-green-600 my-4">
                    Rp. {{ number_format($product->harga, 0, ',', '.') }}
                </p>
                <p class="text-gray-600 mb-4">{!! nl2br(e($product->deskripsi)) !!}</p>

                <div class="flex space-x-4">
                    <button onclick="openPopup()"
                        class="flex-1 bg-indigo-600 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-indigo-700 flex items-center justify-center">
                        <span class="material-icons mr-2">add_shopping_cart</span>
                        KERANJANG
                    </button>
                    <button onclick="openPopup()"
                        class="flex-1 bg-gray-800 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-gray-900">
                        ORDER NOW
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Popup Pilih Ukuran -->
    <div id="popupUkuran" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg w-full max-w-md overflow-hidden shadow-lg">
            <div class="p-4 border-b flex justify-between items-start">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('uploads/' . $product->gambar) }}" class="w-20 h-20 rounded" alt="Produk" />
                    <div>
                        <p id="hargaProdukPopup" class="text-lg font-semibold text-gray-800">
                            Rp{{ number_format($product->harga, 0, ',', '.') }}
                        </p>
                        <p class="line-through text-sm text-gray-400">
                            <span id="hargaDiskonPopup">Rp{{ number_format($product->harga * 2, 0, ',', '.') }}</span>
                        </p>
                        <p class="text-sm text-red-500 font-bold">-50%</p>
                        <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded-full mt-1 inline-block">🚚 Gratis ongkir</span>
                    </div>
                </div>
                <button onclick="closePopup()" class="text-gray-500 hover:text-red-500 text-xl">&times;</button>
            </div>

            <!-- Form Tambah ke Keranjang -->
            <form method="POST" action="{{ route('keranjang.tambah', $product->id) }}">
                @csrf
                <input type="hidden" name="ukuran" id="inputUkuran">
                <input type="hidden" name="jumlah" id="inputJumlah">

                <div class="p-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">Pilih Ukuran</p>
                    <div class="grid grid-cols-4 gap-3">
                        @foreach($product->sizes as $size)
                        @php
                        $lebar = '-'; $tinggi = '-';
                        if (preg_match('/L?(\d+)[xX]T?(\d+)/', $size->ukuran, $match)) {
                        $lebar = 'L' . $match[1];
                        $tinggi = 'T' . $match[2];
                        } elseif (preg_match('/(\d+)[xX](\d+)/', $size->ukuran, $match)) {
                        $lebar = 'L' . $match[1];
                        $tinggi = 'T' . $match[2];
                        }
                        $ukuranFormatted = $lebar . ' x ' . $tinggi;
                        @endphp
                        <button type="button"
                            class="border border-gray-300 rounded p-2 text-sm text-center hover:border-blue-500 focus:outline-none"
                            onclick="selectUkuran(this)"
                            data-ukuran="{{ $size->ukuran }}"
                            data-harga="{{ $size->harga }}"
                            data-stok="{{ $size->stok }}">
                            {{ $ukuranFormatted }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <!-- Pilih Jumlah -->
                <div class="p-4 border-t">
                    <p class="text-sm font-medium text-gray-700 mb-1">Jumlah</p>
                    <div class="flex items-center gap-4">
                        <button type="button" id="btnDecrease" class="bg-gray-200 w-8 h-8 rounded" onclick="ubahJumlah(-1)">−</button>
                        <input id="jumlahInput" type="number" value="1" min="1" class="w-12 text-center border border-gray-300 rounded" readonly />
                        <button type="button" id="btnIncrease" class="bg-gray-200 w-8 h-8 rounded" onclick="ubahJumlah(1)">+</button>
                    </div>
                    <p id="stokInfo" class="text-xs text-gray-500 mt-1"></p>
                </div>

                <!-- Tombol Tambah -->
                <div class="p-4 border-t">
                    <button type="button" onclick="submitFormKeranjang()"
                        class="w-full bg-green-600 text-white py-2 rounded font-semibold hover:bg-green-700">
                        + Keranjang
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="bg-white mt-6 rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Ulasan Produk</h2>
        @forelse($product->reviews as $review)
        <div class="border-b border-gray-200 pb-4 mb-4">
            <div class="flex items-center mb-1">
                <strong class="text-gray-800">{{ $review->user->name }}</strong>
                <span class="ml-2 text-yellow-500">
                    @for ($i = 1; $i <= 5; $i++)
                        {!! $i <=$review->rating ? '★' : '☆' !!}
                        @endfor
                </span>
            </div>
            <p class="text-gray-600">{{ $review->ulasan }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $review->created_at->diffForHumans() }}</p>
        </div>
        @empty
        <p class="text-gray-500">Belum ada ulasan untuk produk ini.</p>
        @endforelse

        @auth
        <form action="{{ route('ulasan.store') }}" method="POST" class="mt-6">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <label class="block mb-2 text-sm font-medium text-gray-700">Rating</label>
            <div class="flex items-center space-x-1 mb-4">
                @for ($i = 1; $i <= 5; $i++)
                    <button type="button" class="star text-2xl text-gray-400 hover:text-yellow-400 focus:outline-none" data-value="{{ $i }}">★</button>
                    @endfor
            </div>
            <input type="hidden" name="rating" id="ratingInput">

            <label class="block mb-2 text-sm font-medium text-gray-700">Ulasan</label>
            <textarea name="ulasan" rows="3" class="w-full border border-gray-300 rounded mb-4" placeholder="Tulis ulasan Anda..."></textarea>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Kirim Ulasan</button>
        </form>
        @else
        <p class="text-sm text-gray-600 mt-6">Silakan <a href="{{ route('login') }}" class="text-blue-500 hover:underline">login</a> untuk memberikan ulasan.</p>
        @endauth
    </div>
    </div>
    @extends('layouts.Chat')

    <!-- Script -->
    <script>
        let selectedUkuran = null;
        let selectedStok = 0;

        const jumlahInput = document.getElementById('jumlahInput');
        const stokInfo = document.getElementById('stokInfo');

        function openPopup() {
            document.getElementById('popupUkuran').classList.remove('hidden');
        }

        function closePopup() {
            document.getElementById('popupUkuran').classList.add('hidden');
        }

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }

        function selectUkuran(el) {
            document.querySelectorAll('[data-ukuran]').forEach(el => el.classList.remove('border-green-500'));
            el.classList.add('border-green-500');

            selectedUkuran = el.getAttribute('data-ukuran');
            selectedStok = parseInt(el.getAttribute('data-stok'));
            let harga = parseInt(el.getAttribute('data-harga'));

            document.getElementById('hargaProdukPopup').innerText = formatRupiah(harga);
            document.getElementById('hargaDiskonPopup').innerText = formatRupiah(harga * 2);
            document.getElementById('hargaUtama').innerText = formatRupiah(harga);

            jumlahInput.value = 1;
            jumlahInput.max = selectedStok;
            stokInfo.innerText = `Stok tersedia: ${selectedStok}`;
        }

        function ubahJumlah(amount) {
            let current = parseInt(jumlahInput.value);
            if (isNaN(current)) current = 1;

            current += amount;
            if (current < 1) current = 1;
            if (selectedStok > 0 && current > selectedStok) current = selectedStok;

            jumlahInput.value = current;
        }

        function submitFormKeranjang() {
            if (!selectedUkuran) {
                alert('Silakan pilih ukuran terlebih dahulu.');
                return;
            }

            document.getElementById('inputUkuran').value = selectedUkuran;
            document.getElementById('inputJumlah').value = jumlahInput.value;

            document.querySelector('form').submit();
        }
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('ratingInput');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const value = parseInt(star.getAttribute('data-value'));
                ratingInput.value = value;
                stars.forEach(s => {
                    const starVal = parseInt(s.getAttribute('data-value'));
                    s.classList.toggle('text-yellow-400', starVal <= value);
                    s.classList.toggle('text-gray-400', starVal > value);
                });
            });
        });
    </script>
</body>

</html>