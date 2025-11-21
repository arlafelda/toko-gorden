<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Carbon\Carbon;

class AdminTrafikTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat admin untuk login
        $this->admin = Admin::create([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
            'password' => 'admin123', // mutator di model otomatis hash
        ]);
    }

    #[Test]
    public function admin_dapat_melihat_halaman_trafik()
    {
        // Login sebagai admin dulu
        $this->actingAs($this->admin, 'admin');

        $response = $this->get(route('admin.trafik'));
        $response->assertStatus(200);
        $response->assertViewIs('Admin.Trafik');
    }

    #[Test]
    public function admin_dapat_mendapatkan_data_trafik_json()
    {
        $this->actingAs($this->admin, 'admin');

        // Buat data user
        $user1 = User::create([
            'name' => 'User 1',
            'email' => 'user1@test.com',
            'password' => bcrypt('password'),
        ]);
        $user2 = User::create([
            'name' => 'User 2',
            'email' => 'user2@test.com',
            'password' => bcrypt('password'),
        ]);
        $user3 = User::create([
            'name' => 'User 3',
            'email' => 'user3@test.com',
            'password' => bcrypt('password'),
        ]);

        // Buat data order dengan user_id dan order_id wajib
        Order::create([
            'user_id' => $user1->id,
            'order_id' => 'TEST-001',
            'order_date' => Carbon::today(),
            'total' => 10000,
            'status' => 'settlement',
            'payment_type' => 'bank_transfer',
            'created_at' => Carbon::today(),
            'updated_at' => Carbon::today(),
        ]);
        Order::create([
            'user_id' => $user2->id,
            'order_id' => 'TEST-002',
            'order_date' => Carbon::today(),
            'total' => 20000,
            'status' => 'capture',
            'payment_type' => 'gopay',
            'created_at' => Carbon::today(),
            'updated_at' => Carbon::today(),
        ]);

        // Panggil endpoint JSON trafik
        $response = $this->getJson(route('admin.trafik.data'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'pengunjung' => [
                '*' => ['date', 'count']
            ],
            'penjualan' => [
                '*' => ['date', 'count']
            ],
        ]);

        // Ambil data hari ini dari JSON
        $today = Carbon::today()->toDateString();
        $pengunjungHariIni = collect($response->json('pengunjung'))->firstWhere('date', $today)['count'];
        $penjualanHariIni = collect($response->json('penjualan'))->firstWhere('date', $today)['count'];

        // Assert sesuai data yang kita buat
        $this->assertEquals(3, $pengunjungHariIni); // ada 3 user
        $this->assertEquals(2, $penjualanHariIni);  // ada 2 order
    }
}
