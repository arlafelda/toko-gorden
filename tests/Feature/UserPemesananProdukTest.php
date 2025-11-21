<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPemesananProdukTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_dapat_melihat_daftar_produk()
    {
        $response = $this->get('/produk-user');
        $response->assertStatus(200);
    }

    public function test_user_dapat_melihat_detail_produk()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $response = $this->get('/produk/' . $product->id);

        $response->assertStatus(200);
        $response->assertSee($product->nama_produk);
    }

    public function test_user_dapat_melakukan_pesan_sekarang_beli_langsung()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Product $product */
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->post("/produk/{$product->id}/beli-langsung", [
            'ukuran' => 'L100xT200', // contoh ukuran valid
            'jumlah' => 1
        ]);


        $response->assertRedirect('/checkout/beli-langsung');
    }

    public function test_user_dapat_melakukan_checkout()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/bayar-langsung', [
            'product_id' => 1,
            'qty' => 1
        ]);

        $response->assertStatus(302);
    }

    public function test_user_masuk_ke_halaman_login_jika_belum_login()
    {
        $response = $this->get('/checkout/beli-langsung');

        $response->assertRedirect('/login');
    }

    public function test_user_dapat_melihat_pesanan_mereka()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Product $product */
        $product = Product::factory()->create();

        /** @var Order $order */
        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        // Buat item order di order_items
        \App\Models\OrderItem::factory()->create([
            'order_id'  => $order->id,
            'produk_id' => $product->id,
            'ukuran'    => 'L100xT200',
            'quantity'    => 1,
            'price'     => $product->harga,
        ]);

        $response = $this->actingAs($user)->get('/pesanan');

        $response->assertStatus(200);
        $response->assertSee($product->nama_produk);
    }
}
