<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Keranjang;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KeranjangTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $product;
    protected $size;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user
        $this->user = User::factory()->create();

        // Buat produk
        $this->product = Product::factory()->create([
            'nama_produk' => 'GORDEN BASIC',
            'deskripsi' => 'Deskripsi gorden contoh',
            'harga' => 100000,
            'stok' => 10,
            'gambar' => 'default.jpg',
            'jenis_gorden' => 'Vitras',
        ]);

        // Buat ukuran produk
        $this->size = ProductSize::create([
            'product_id' => $this->product->id,
            'ukuran' => 'M',
            'harga' => 100000,
            'stok' => 10,
        ]);
    }

    #[Test]
    public function user_dapat_menambah_produk_ke_keranjang()
    {
        $response = $this->actingAs($this->user)->post('/keranjang/tambah/' . $this->product->id, [
            'ukuran' => 'M',
            'jumlah' => 2,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('keranjang_items', [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);
    }

    #[Test]
    public function menambah_produk_yang_sama_meningkatkan_quantity()
    {
        $this->actingAs($this->user)->post('/keranjang/tambah/' . $this->product->id, [
            'ukuran' => 'M',
            'jumlah' => 2,
        ]);

        $this->actingAs($this->user)->post('/keranjang/tambah/' . $this->product->id, [
            'ukuran' => 'M',
            'jumlah' => 3,
        ]);

        $this->assertDatabaseHas('keranjang_items', [
            'product_id' => $this->product->id,
            'quantity' => 5,
        ]);
    }

    #[Test]
    public function user_dapat_melihat_isi_keranjang()
    {
        $this->actingAs($this->user)->post('/keranjang/tambah/' . $this->product->id, [
            'ukuran' => 'M',
            'jumlah' => 2,
        ]);

        $response = $this->actingAs($this->user)->get('/keranjang');
        $response->assertStatus(200);
        $response->assertViewHas('keranjangItems');
    }

    #[Test]
    public function user_dapat_menghapus_item_keranjang()
    {
        $this->actingAs($this->user)->post('/keranjang/tambah/' . $this->product->id, [
            'ukuran' => 'M',
            'jumlah' => 2,
        ]);

        $item = Keranjang::first()->items()->first();

        $response = $this->actingAs($this->user)->delete('/keranjang/hapus/' . $item->id);
        $response->assertRedirect();

        $this->assertDatabaseMissing('keranjang_items', [
            'id' => $item->id,
        ]);
    }

    #[Test]
    public function user_tanpa_login_tidak_dapat_menambah_keranjang()
    {
        $response = $this->post('/keranjang/tambah/' . $this->product->id, [
            'ukuran' => 'M',
            'jumlah' => 2,
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('keranjang_items', [
            'product_id' => $this->product->id,
        ]);
    }
}
