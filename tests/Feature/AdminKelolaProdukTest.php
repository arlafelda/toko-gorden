<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AdminKelolaProdukTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat admin untuk login
        $this->admin = Admin::factory()->create([
            'username' => 'admin',
            'password' => bcrypt('admin123'),
        ]);
    }

    #[Test]
    public function TCK_A01_admin_buka_menu_produk()
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->get(route('admin.products.index'));
        $response->assertStatus(200)
            ->assertSee('Daftar Produk');
    }

    #[Test]
    public function TCK_A02_klick_tambah_produk()
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->get(route('admin.products.create'));
        $response->assertStatus(200)
            ->assertSee('Tambah Produk');
    }

    #[Test]
    public function TCK_A03_submit_produk_baru_tanpa_isi_field()
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->post(route('admin.products.store'), []);
        $response->assertSessionHasErrors(['name', 'price', 'description', 'jenis', 'stok']);
    }

    #[Test]
    public function TCK_A04_tambah_produk_lengkap_dan_upload_gambar()
    {
        $this->actingAs($this->admin, 'admin');

        Storage::fake('public');

        $file = UploadedFile::fake()->image('produk.jpg');

        $data = [
            'name' => 'Gorden Basic',
            'description' => 'Gorden minimalis',
            'price' => 100000,
            'jenis' => 'Gorden',
            'stok' => 10,
            'image' => $file,
        ];

        $response = $this->post(route('admin.products.store'), $data);
        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'Gorden Basic',
            'price' => 100000,
        ]);

        // Ganti assertExists agar Intelephense tidak eror
        $this->assertTrue(Storage::disk('public')->exists('produk/' . $file->hashName()));
    }


    #[Test]
    public function TCK_A05_tambah_ukuran_baru_lebih_dari_1_baris()
    {
        $this->actingAs($this->admin, 'admin');

        $product = Product::factory()->create();

        $data = [
            'sizes' => [
                ['width' => 100, 'height' => 200, 'price' => 50000, 'stok' => 5],
                ['width' => 150, 'height' => 250, 'price' => 75000, 'stok' => 3],
            ]
        ];

        $response = $this->post(route('admin.products.sizes.store', $product->id), $data);
        $response->assertRedirect(route('admin.products.edit', $product->id));

        $this->assertDatabaseHas('product_sizes', [
            'product_id' => $product->id,
            'width' => 100,
            'height' => 200,
        ]);

        $this->assertDatabaseHas('product_sizes', [
            'product_id' => $product->id,
            'width' => 150,
            'height' => 250,
        ]);
    }

    #[Test]
    public function TCK_A06_ubah_data_produk()
    {
        $this->actingAs($this->admin, 'admin');

        $product = Product::factory()->create([
            'name' => 'Gorden Lama',
            'price' => 90000,
        ]);

        $data = [
            'name' => 'Gorden Baru',
            'price' => 120000,
            'description' => $product->description,
            'jenis' => $product->jenis,
            'stok' => $product->stok,
        ];

        $response = $this->put(route('admin.products.update', $product->id), $data);
        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Gorden Baru', 'price' => 120000]);
    }

    #[Test]
    public function TCK_A07_edit_produk_tanpa_gambar_baru()
    {
        $this->actingAs($this->admin, 'admin');

        Storage::fake('public');
        $file = UploadedFile::fake()->image('old.jpg');

        $product = Product::factory()->create([
            'image' => 'produk/' . $file->hashName(),
        ]);

        $data = [
            'name' => 'Gorden Update',
            'price' => 110000,
            'description' => $product->description,
            'jenis' => $product->jenis,
            'stok' => $product->stok,
        ];

        $response = $this->put(route('admin.products.update', $product->id), $data);
        $response->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Gorden Update']);
        $this->assertEquals($product->image, Product::find($product->id)->image);
    }

    #[Test]
    public function TCK_A08_tambah_ukuran_baru_saat_edit_produk()
    {
        $this->actingAs($this->admin, 'admin');

        $product = Product::factory()->create();

        $data = [
            'sizes' => [
                ['width' => 200, 'height' => 300, 'price' => 80000, 'stok' => 4]
            ]
        ];

        $response = $this->post(route('admin.products.sizes.store', $product->id), $data);
        $response->assertRedirect(route('admin.products.edit', $product->id));

        $this->assertDatabaseHas('product_sizes', [
            'product_id' => $product->id,
            'width' => 200,
            'height' => 300,
        ]);
    }

    #[Test]
    public function TCK_A09_hapus_ukuran_salah_satu_ukuran_produk()
    {
        $this->actingAs($this->admin, 'admin');

        $product = Product::factory()->create();
        $size = ProductSize::factory()->create(['product_id' => $product->id]);

        $response = $this->delete(route('admin.products.sizes.destroy', [$product->id, $size->id]));
        $response->assertRedirect(route('admin.products.edit', $product->id));

        $this->assertDatabaseMissing('product_sizes', ['id' => $size->id]);
    }

    #[Test]
    public function TCK_A10_hapus_produk()
    {
        $this->actingAs($this->admin, 'admin');

        $product = Product::factory()->create();

        $response = $this->delete(route('admin.products.destroy', $product->id));
        $response->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    #[Test]
    public function TCK_A11_upload_gambar_invalid()
    {
        $this->actingAs($this->admin, 'admin');

        Storage::fake('public');
        $file = UploadedFile::fake()->create('document.pdf', 3000, 'application/pdf');

        $data = [
            'name' => 'Gorden Invalid',
            'description' => 'Test',
            'price' => 100000,
            'jenis' => 'Gorden',
            'stok' => 10,
            'image' => $file,
        ];

        $response = $this->post(route('admin.products.store'), $data);
        $response->assertSessionHasErrors(['image']);
    }
}
