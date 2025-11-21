<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\User;
use App\Models\Order;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminDaftarPelangganTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_dapat_mengakses_halaman_daftar_pelanggan_dengan_tampilan_yang_benar()
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.pelanggan'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Pelanggan');
        $response->assertSee('Nama Pelanggan');
        $response->assertSee('Nomor Telepon');
        $response->assertSee('Riwayat Pesanan');
    }

    #[Test]
    public function halaman_daftar_pelanggan_tidak_bisa_diakses_tanpa_login()
    {
        $response = $this->get(route('admin.pelanggan'));
        $response->assertRedirect(route('admin.login'));
    }

    #[Test]
    public function halaman_daftar_pelanggan_menampilkan_data_yang_ada_di_database()
    {
        $admin = Admin::factory()->create();
        $user = User::factory()->create([
            'name' => 'Budi Santoso',
            'address' => 'Jl. Merdeka No.1',
            'phone_number' => '08123456789',
        ]);

        Order::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.pelanggan'));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertSee('Jl. Merdeka No.1');
        $response->assertSee('3 pesanan');
    }

    #[Test]
    public function admin_dapat_mencari_pelanggan_berdasarkan_nama_atau_alamat()
    {
        $admin = Admin::factory()->create();
        User::factory()->create(['name' => 'Budi Prasetyo']);
        User::factory()->create(['name' => 'Andi Wijaya']);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.pelanggan', ['q' => 'Budi']));

        $response->assertStatus(200);
        $response->assertSee('Budi Prasetyo');
        $response->assertDontSee('Andi Wijaya');
    }

    #[Test]
    public function admin_mendapatkan_pesan_jika_mencari_pelanggan_yang_tidak_ada()
    {
        $admin = Admin::factory()->create();
        User::factory()->create(['name' => 'Siti Aminah']);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.pelanggan', ['q' => 'XYZ123']));

        $response->assertStatus(200);
        $response->assertSee('Belum ada pelanggan.');
    }

    #[Test]
    public function halaman_daftar_pelanggan_menampilkan_pesan_ketika_database_kosong()
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.pelanggan'));

        $response->assertStatus(200);
        $response->assertSee('Belum ada pelanggan.');
    }

    #[Test]
    public function kolom_riwayat_pesanan_menampilkan_jumlah_pesanan_user()
    {
        $admin = Admin::factory()->create();
        $user = User::factory()->create(['name' => 'Tono']);
        Order::factory()->count(2)->create(['user_id' => $user->id]);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.pelanggan'));

        $response->assertStatus(200);
        $response->assertSee('2 pesanan');
    }

    #[Test]
    public function halaman_daftar_pelanggan_dapat_direload_tanpa_error()
    {
        $admin = Admin::factory()->create();
        $user = User::factory()->create(['name' => 'Rina Dewi']);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.pelanggan'));

        $response->assertStatus(200);
        $response->assertSee('Rina Dewi');

        // Simulasi reload halaman
        $responseReload = $this->actingAs($admin, 'admin')
            ->get(route('admin.pelanggan'));

        $responseReload->assertStatus(200);
        $responseReload->assertSee('Rina Dewi');
    }
}
