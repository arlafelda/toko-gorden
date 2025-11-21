<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_dapat_login_dengan_credential_valid()
    {
        // Buat user
        $user = User::factory()->create([
            'email' => 'arla@example.com',
            'password' => Hash::make('password123')
        ]);

        // Kirim request login
        $response = $this->post('/login', [
            'email' => 'arla@example.com',
            'password' => 'password123',
        ]);

        // Pastikan login berhasil dan diarahkan ke route landing
        $response->assertRedirect(route('landing'));
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function login_gagal_jika_email_tidak_terdaftar()
    {
        $response = $this->post('/login', [
            'email' => 'unknown@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest(); // Tidak login
    }

    #[Test]
    public function login_gagal_jika_password_salah()
    {
        // Buat user
        User::factory()->create([
            'email' => 'arla@example.com',
            'password' => Hash::make('correctpassword')
        ]);

        // Salah password
        $response = $this->post('/login', [
            'email' => 'arla@example.com',
            'password' => 'wrongpassword',
        ]);

        // Pesan error sesuai controller
        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    #[Test]
    public function validasi_gagal_jika_email_atau_password_kosong()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }
}
