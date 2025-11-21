<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminLogoutTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_dapat_logout_dan_diredirect_ke_halaman_login()
    {
        // Buat admin manual
        $admin = Admin::create([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
            'password' => 'admin123', // mutator akan otomatis hash
        ]);

        // Login sebagai admin menggunakan guard 'admin'
        $this->actingAs($admin, 'admin');

        // Pastikan sudah login
        $this->assertAuthenticatedAs($admin, 'admin');

        // Akses route logout
        $response = $this->post(route('admin.logout'));

        // Pastikan redirect ke halaman login
        $response->assertRedirect(route('admin.login'));

        // Pastikan admin sudah logout
        $this->assertGuest('admin');
    }
}
