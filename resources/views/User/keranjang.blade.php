<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Keranjang Belanja</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

  <main class="px-6 md:px-40 flex flex-1 justify-center py-5">
    <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
      <h2 class="text-xl font-semibold mb-4">Keranjang Belanja</h2>

      {{-- Alert Success/Error --}}
      @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
      @endif

      {{-- Jika ada item --}}
      @if($keranjangItems->count() > 0)
      <form action="{{ route('transaksi') }}" method="GET" id="checkoutForm">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="grid grid-cols-12 gap-4 p-4 border-b font-medium text-gray-600">
            <div class="col-span-1"><input type="checkbox" id="selectAll" /></div>
            <div class="col-span-4">Produk</div>
            <div class="col-span-2">Harga</div>
            <div class="col-span-1">Ukuran</div>
            <div class="col-span-1">Jumlah</div>
            <div class="col-span-2">Subtotal</div>
            <div class="col-span-1">Hapus</div>
          </div>

          @foreach($keranjangItems as $item)
          @php
            $harga = $item->harga ?? ($item->product->harga ?? 0);
            $subtotal = $harga * $item->quantity;
          @endphp
          <div class="grid grid-cols-12 gap-4 p-4 items-center border-b hover:bg-gray-50">
            <div class="col-span-1">
              <input type="checkbox" class="item-checkbox" value="{{ $item->id }}" data-subtotal="{{ $subtotal }}" />
            </div>

            <div class="col-span-4 flex items-center gap-3">
              @if($item->product)
                <img src="{{ asset('uploads/' . ($item->product->gambar ?? 'default.png')) }}" class="w-20 h-20 object-cover rounded" />
                <div>
                  <p class="font-semibold">{{ $item->product->nama_produk }}</p>
                </div>
              @else
                <p class="text-red-500">Produk tidak ditemukan</p>
              @endif
            </div>

            <div class="col-span-2">Rp {{ number_format($harga, 0, ',', '.') }}</div>
            <div class="col-span-1">{{ $item->ukuran ?? '-' }}</div>
            <div class="col-span-1">
              {{ $item->quantity }}
              <br>
              <span class="text-xs text-gray-500">Stok: {{ $item->stok }}</span>
            </div>
            <div class="col-span-2">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
            <div class="col-span-1">
              @if($item->product)
              <form action="{{ route('keranjang.hapus', $item->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
              </form>
              @endif
            </div>
          </div>
          @endforeach
        </div>

        {{-- Total dan tombol --}}
        <div class="mt-6 flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-4 rounded shadow">
          <div>
            Total bayar: <span id="totalBayar" class="text-green-700 font-semibold">Rp0</span> <br>
            Dipilih: <span id="jumlahDipilih" class="text-blue-600 font-semibold">0</span> barang
          </div>
          <div class="flex gap-4">
            <a href="{{ route('produk.user') }}" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700">
              Batal
            </a>
            <button type="submit" onclick="prepareCheckoutItems(event)" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
              Checkout
            </button>
          </div>
        </div>
      </form>
      @endif

      {{-- Jika keranjang kosong --}}
      @if($keranjangItems->count() === 0)
      <div class="bg-white p-6 rounded shadow text-center">
        <p class="text-gray-600">Keranjang kamu masih kosong.</p>
        <a href="{{ route('produk.user') }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Lihat Produk
        </a>
      </div>
      @endif
    </div>
  </main>

  <footer class="bg-white shadow mt-auto p-4 text-center text-sm text-gray-500">
    &copy; {{ date('Y') }} Toko Gorden. All rights reserved.
  </footer>

  {{-- Script --}}
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      function formatRupiah(angka) {
        return new Intl.NumberFormat("id-ID", {
          style: "currency",
          currency: "IDR",
          minimumFractionDigits: 0
        }).format(angka);
      }

      function updateTotal() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        let total = 0;
        let jumlahDipilih = 0;
        checkboxes.forEach(cb => {
          if (cb.checked) {
            total += parseFloat(cb.dataset.subtotal);
            jumlahDipilih++;
          }
        });
        document.getElementById('totalBayar').innerText = formatRupiah(total);
        document.getElementById('jumlahDipilih').innerText = jumlahDipilih;
      }

      document.querySelectorAll('.item-checkbox').forEach(cb => {
        cb.addEventListener('change', updateTotal);
      });

      const selectAll = document.getElementById('selectAll');
      if (selectAll) {
        selectAll.addEventListener('change', function () {
          document.querySelectorAll('.item-checkbox').forEach(cb => {
            cb.checked = this.checked;
          });
          updateTotal();
        });
      }

      window.prepareCheckoutItems = function (event) {
        event.preventDefault();
        const checkboxes = document.querySelectorAll('.item-checkbox:checked');
        const form = document.getElementById('checkoutForm');

        if (checkboxes.length === 0) {
          alert('Silakan pilih produk terlebih dahulu.');
          return;
        }

        document.querySelectorAll('input[name="items[]"]').forEach(input => input.remove());

        checkboxes.forEach(cb => {
          const input = document.createElement('input');
          input.type = 'hidden';
          input.name = 'items[]';
          input.value = cb.value;
          form.appendChild(input);
        });

        form.submit();
      };
    });
  </script>

</body>
</html>
