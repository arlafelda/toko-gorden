@extends('layouts.Navbar')

@section('title', 'Produk - Toko Gorden')

@section('content')
      <div class="px-40 flex flex-1 justify-center py-5">
        <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
          <div class="flex flex-wrap justify-between gap-3 p-4">
            <p class="text-[#111418] tracking-light text-[32px] font-bold leading-tight min-w-72">
              Gorden
            </p>
          </div>
          <div class="px-4 py-3">
            <form method="GET" action="{{ route('produk.user') }}" class="flex w-full h-12">
              <div class="flex w-full flex-1 items-stretch rounded-l-xl h-full">
                <div class="text-[#637588] flex items-center justify-center pl-4 bg-[#f0f2f4] rounded-l-xl">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                    <path d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z" />
                  </svg>
                </div>
                <input
                  type="text"
                  name="q"
                  value="{{ request('q') }}"
                  placeholder="Cari gorden"
                  class="form-input w-full px-4 bg-[#f0f2f4] border-none focus:outline-none placeholder:text-[#637588]" />
              </div>
              <button type="submit" class="bg-indigo-600 text-white px-4 rounded-r-xl hover:bg-indigo-700">Cari</button>
            </form>

            @if(request()->filled('q'))
            <p class="px-4 text-sm text-gray-600">
              Menampilkan hasil pencarian untuk: <strong>{{ request('q') }}</strong>
            </p>
            @endif

          </div>
          <div class="flex gap-3 p-3 flex-wrap pr-4">
            <!-- Tombol Semua Produk -->
            <!-- Tombol Semua Produk -->
            <a href="{{ route('produk.user') }}"
              class="flex h-8 items-center justify-center gap-x-2 rounded-full bg-[#f0f2f4] pl-4 pr-2">
              <p class="text-[#111418] text-sm font-medium">Semua Produk</p>
            </a>


            <!-- Dropdown Jenis Gorden -->
            <div x-data="{ openJenis: false }" class="relative">
              <button @click="openJenis = !openJenis"
                class="flex h-8 items-center justify-center gap-x-2 rounded-full bg-[#f0f2f4] pl-4 pr-2">
                <p class="text-[#111418] text-sm font-medium leading-normal">Jenis Gorden</p>
                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor"
                  viewBox="0 0 256 256">
                  <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                </svg>
              </button>
              <div x-show="openJenis" @click.away="openJenis = false"
                class="absolute mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                <ul class="py-1 text-sm text-gray-700">
                  <li><a href="{{ route('produk.user', ['jenis' => 'gorden-vertikal']) }}" class="block px-4 py-2 hover:bg-gray-100">Gorden Vertikal</a></li>
                  <li><a href="{{ route('produk.user', ['jenis' => 'gorden-natural']) }}" class="block px-4 py-2 hover:bg-gray-100">Gorden Natural</a></li>
                  <li><a href="{{ route('produk.user', ['jenis' => 'gorden-roll-blind']) }}" class="block px-4 py-2 hover:bg-gray-100">Gorden Roll Blind</a></li>
                  <li><a href="{{ route('produk.user', ['jenis' => 'gorden-slimbin']) }}" class="block px-4 py-2 hover:bg-gray-100">Gorden Slimbin</a></li>
                  <li><a href="{{ route('produk.user', ['jenis' => 'gorden-wooden-blind']) }}" class="block px-4 py-2 hover:bg-gray-100">Gorden Wooden Blind</a></li>
                </ul>
              </div>
            </div>
            <!-- Dropdown Aksesoris -->
            <div x-data="{ openAksesoris: false }" class="relative">
              <button @click="openAksesoris = !openAksesoris" class="flex h-8 items-center justify-center gap-x-2 rounded-full bg-[#f0f2f4] pl-4 pr-2">
                <p class="text-sm font-medium">Aksesoris</p>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256">
                  <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z" />
                </svg>
              </button>
              <div x-show="openAksesoris" @click.away="openAksesoris = false" class="absolute mt-2 w-56 bg-white shadow-lg rounded-md z-50">
                <ul class="py-1 text-sm text-gray-700">
                  <li><a href="{{ route('produk.user', ['jenis' => 'aksesoris-rel-gorden']) }}" class="block px-4 py-2 hover:bg-gray-100">Rel Gorden</a></li>
                  <li><a href="{{ route('produk.user', ['jenis' => 'aksesoris-pengait-tali']) }}" class="block px-4 py-2 hover:bg-gray-100">Pengait/Tali</a></li>
                  <li><a href="{{ route('produk.user', ['jenis' => 'aksesoris-bracket']) }}" class="block px-4 py-2 hover:bg-gray-100">Bracket</a></li>
                  <li><a href="{{ route('produk.user', ['jenis' => 'aksesoris-hook']) }}" class="block px-4 py-2 hover:bg-gray-100">Hook</a></li>
                </ul>
              </div>
            </div>
          </div>
          <!-- Bagian list produk (perbaikan) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 p-4">
            @foreach ($products as $product)
            <a href="{{ route('product.detail', $product->id) }}" class="block border rounded-lg overflow-hidden shadow hover:shadow-lg transition duration-300">
              @if($product->gambar)
              <img
                src="{{ asset('uploads/' . $product->gambar) }}"
                alt="{{ $product->nama_produk }}"
                class="w-full h-48 object-cover" />
              @else
              <div
                class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">
                No Image
              </div>
              @endif
              <div class="p-4">
                <h3
                  class="text-lg font-semibold text-gray-900">
                  {{ $product->nama_produk }}
                </h3>
                <p class="text-gray-600 mt-1">
                  {{ \Illuminate\Support\Str::limit($product->deskripsi, 60) }}
                </p>
                <p class="mt-2 text-indigo-600 font-bold">
                  Rp {{ number_format($product->harga, 0, ',', '.') }}
                </p>
                <p class="text-sm text-gray-400 mt-1">
                  Jenis: {{ $product->jenis_gorden ?? '-' }}
                </p>
              </div>
            </a>
            @endforeach
          </div>
          <div class="mt-12 flex justify-center">
          </div>
        </div>
      </div>
    </div>
  </div>
  @extends('layouts.Chat')
@endsection