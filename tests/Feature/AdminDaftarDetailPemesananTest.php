<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AdminDaftarDetailPemesananTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat admin
        $this->admin = Admin::factory()->create([
            'username' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        // Buat user
        $this->user = User::factory()->create();
    }

    #[Test]
    public function TCP_A01_buka_menu_daftar_pesanan()
    {
        $this->actingAs($this->admin, 'admin');

        // Buat beberapa pesanan
        Order::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'status' => 'pending'
        ]);

        $response = $this->get(route('pesananAdmin'));
        $response->assertStatus(200)
                 ->assertSee('Daftar Pesanan');
    }

    #[Test]
    public function TCP_A02_detail_pesanan_tertampil()
    {
        $this->actingAs($this->admin, 'admin');

        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending',
            'alamat' => 'Jl. Contoh No.1',
            'metode_bayar' => 'Transfer'
        ]);

        OrderItem::factory()->count(2)->create([
            'order_id' => $order->id
        ]);

        $response = $this->get(route('admin.detailPesanan', $order->id));
        $response->assertStatus(200)
                 ->assertSee($this->user->name)
                 ->assertSee('Jl. Contoh No.1')
                 ->assertSee('Transfer')
                 ->assertSee('pending');
    }

    #[Test]
    public function TCP_A03_ubah_status_pesanan_selesai()
    {
        $this->actingAs($this->admin, 'admin');

        $order = Order::factory()->create([
            'status' => 'pending'
        ]);

        $response = $this->post(route('admin.updateStatus', $order->id), [
            'status' => 'Selesai'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'Selesai'
        ]);
    }

    #[Test]
    public function TCP_A04_ubah_status_selesai_ke_pending_ditolak()
    {
        $this->actingAs($this->admin, 'admin');

        $order = Order::factory()->create([
            'status' => 'Selesai'
        ]);

        $response = $this->post(route('admin.updateStatus', $order->id), [
            'status' => 'Pending'
        ]);

        $response->assertRedirect();
        // Status tidak berubah
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'Selesai'
        ]);
    }

    #[Test]
    public function TCP_A07_buka_pesanan_tanpa_item()
    {
        $this->actingAs($this->admin, 'admin');

        $order = Order::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->get(route('admin.detailPesanan', $order->id));
        $response->assertStatus(200)
                 ->assertSee('Tidak ada item dalam pesanan');
    }

    #[Test]
    public function TCP_A08_pesanan_belum_dibayar()
    {
        $this->actingAs($this->admin, 'admin');

        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'Belum Dibayar'
        ]);

        $response = $this->get(route('admin.detailPesanan', $order->id));
        $response->assertStatus(200)
                 ->assertSee('Belum Dibayar');
    }

    #[Test]
    public function TCP_A06_cek_detail_item_pesanan()
    {
        $this->actingAs($this->admin, 'admin');

        $order = Order::factory()->create([
            'user_id' => $this->user->id
        ]);

        $product = Product::factory()->create();
        $item = OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 50000
        ]);

        $response = $this->get(route('admin.detailPesanan', $order->id));
        $response->assertSee($product->name)
                 ->assertSee('2')
                 ->assertSee('50000');
    }
}
