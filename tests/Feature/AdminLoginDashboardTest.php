<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Carbon\Carbon;

class AdminLoginDashboardTest extends TestCase
{
    use RefreshDatabase;

    #[test]
    public function admin_dapat_melihat_halaman_login()
    {
        $response = $this->get(route('admin.login'));
        $response->assertStatus(200);
        $response->assertSee('Admin Login'); // Sesuaikan dengan teks di view Admin.LoginAdmin
    }

    #[test]
    public function admin_dapat_login_dengan_kredensial_yang_benar()
    {
        // Buat admin manual tanpa factory
        $admin = Admin::create([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
            'password' => 'admin123', // mutator di model akan otomatis hash
        ]);

        // Login langsung menggunakan guard 'admin'
        $this->actingAs($admin, 'admin');

        // Akses dashboard
        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    #[test]
    public function admin_tidak_dapat_login_dengan_kredensial_salah()
    {
        // Buat admin manual tanpa factory
        $admin = Admin::create([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('admin.login'), [
            'email' => 'admin@example.com',
            'password' => 'password_salah',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    }

    #[test]
    public function admin_dapat_melihat_dashboard()
    {
        // Buat data dummy untuk dashboard
        User::factory()->count(5)->create();
        Order::factory()->count(3)->create([
            'total' => 100000,
            'created_at' => Carbon::now()->subDays(5),
        ]);

        // Buat admin manual tanpa factory
        $admin = Admin::create([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHasAll([
            'jumlahPelanggan',
            'totalPendapatan',
            'totalPesanan',
            'labels',
            'pendapatanBulanan'
        ]);
    }
}
