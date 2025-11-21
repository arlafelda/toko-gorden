<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test; // Impor untuk menghilangkan Warning

class ProductSearchTest extends TestCase
{
    use RefreshDatabase; // Memastikan basis data di-reset untuk setiap tes

    // Setup: Membuat data produk yang akan digunakan untuk pengujian
    protected function setUp(): void
    {
        parent::setUp();

        // Produk untuk tes positif (ditambahkan kolom 'harga' untuk mengatasi QueryException)
        Product::factory()->create([
            'nama_produk' => 'Gorden Minimalis',
            'deskripsi' => 'Gorden polos modern.',
            'harga' => 150000, // <--- Perbaikan: Menambahkan nilai 'harga'
        ]);

        // Produk untuk tes pencocokan sebagian (ditambahkan kolom 'harga')
        Product::factory()->create([
            'nama_produk' => 'Gorden Mewah',
            'deskripsi' => 'Gorden dengan bahan sutra.',
            'harga' => 300000, // <--- Perbaikan: Menambahkan nilai 'harga'
        ]);
    }

    // TC-U21: Mencari produk yang sudah ada dengan kata kunci lengkap
    #[Test] // Perbaikan: Mengganti /** @test */
    public function dapat_menemukan_produk_yang_ada_berdasarkan_kata_kunci_lengkap()
    {
        // 1. Lakukan permintaan GET ke rute pencarian dengan kata kunci lengkap
        $response = $this->get(route('produk.user', ['q' => 'Gorden Minimalis']));

        // 2. Ekspektasi: Kode status 200 OK dan melihat nama produk yang sesuai
        $response->assertStatus(200);
        $response->assertSee('Gorden Minimalis');
        $response->assertDontSee('Gorden Mewah'); 
    }

    // TC-U22: Menampilkan pesan "Produk tidak ditemukan" ketika hasil pencarian kosong
    #[Test] // Perbaikan: Mengganti /** @test */
    public function menampilkan_pesan_tidak_ada_hasil_ketika_produk_tidak_ditemukan()
    {
        // 1. Lakukan permintaan GET dengan kata kunci yang tidak ada
        $response = $this->get(route('produk.user', ['q' => 'Gorden Korea Pink Glitter XXL']));

        // 2. Ekspektasi: Kode status 200 OK dan melihat pesan "Produk tidak ditemukan"
        $response->assertStatus(200);
        $response->assertSee('Pencarian Tidak Ditemukan'); 
        $response->assertSee('Maaf, tidak ada produk gorden yang cocok dengan kata kunci'); 
        $response->assertDontSee('Gorden Minimalis'); 
    }

    // TC-U23: Dapat menemukan produk dengan kata kunci sebagian
    #[Test] // Perbaikan: Mengganti /** @test */
    public function dapat_menemukan_produk_berdasarkan_kata_kunci_sebagian()
    {
        // 1. Lakukan permintaan GET dengan kata kunci sebagian
        $response = $this->get(route('produk.user', ['q' => 'Gorden']));

        // 2. Ekspektasi: Kode status 200 OK dan melihat kedua produk
        $response->assertStatus(200);
        $response->assertSee('Gorden Minimalis');
        $response->assertSee('Gorden Mewah');
    }

    // TC-U24: Pencarian tidak peka huruf besar/kecil (Case Insensitive)
    #[Test] // Perbaikan: Mengganti /** @test */
    public function pencarian_tidak_peka_huruf_besar_kecil()
    {
        // 1. Lakukan permintaan GET dengan huruf kapital
        $response = $this->get(route('produk.user', ['q' => 'GORDEN MINIMALIS']));

        // 2. Ekspektasi: Hasil tetap muncul
        $response->assertStatus(200);
        $response->assertSee('Gorden Minimalis');
    }

    // TC-U25: Pencarian menghilangkan spasi di awal dan akhir (Trim Whitespace)
    #[Test] // Perbaikan: Mengganti /** @test */
    public function pencarian_menghilangkan_spasi_di_awal_dan_akhir()
    {
        // 1. Lakukan permintaan GET dengan spasi ekstra
        $response = $this->get(route('produk.user', ['q' => ' Gorden Minimalis ']));

        // 2. Ekspektasi: Hasil tetap muncul
        $response->assertStatus(200);
        $response->assertSee('Gorden Minimalis');
    }

    // TC-U27: Pencarian kosong menampilkan semua produk
    #[Test] // Perbaikan: Mengganti /** @test */
    public function pencarian_kosong_menampilkan_semua_produk()
    {
        // 1. Lakukan permintaan GET tanpa kata kunci (q='')
        $response = $this->get(route('produk.user', ['q' => '']));

        // 2. Ekspektasi: Kode status 200 OK dan menampilkan semua produk yang ada
        $response->assertStatus(200);
        $response->assertSee('Gorden Minimalis');
        $response->assertSee('Gorden Mewah');
        $response->assertDontSee('Pencarian Tidak Ditemukan'); 
    }
}