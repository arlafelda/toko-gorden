<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Hash;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_dapat_register_dengan_data_valid()
    {
        $response = $this->post('/register', [
            'name' => 'Arla',
            'email' => 'arla@example.com',
            'phone_number' => '08123456789',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Arahkan ke login
        $response->assertRedirect(route('login'));

        // Pastikan user tersimpan di DB
        $this->assertDatabaseHas('users', [
            'email' => 'arla@example.com',
            'name' => 'Arla',
        ]);
    }

    #[Test]
    public function register_gagal_jika_email_sudah_digunakan()
    {
        // Buat user dulu
        User::factory()->create([
            'email' => 'arla@example.com'
        ]);

        // Daftar ulang dengan email yang sama
        $response = $this->post('/register', [
            'name' => 'Arla',
            'email' => 'arla@example.com',
            'phone_number' => '08123456789',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Pastikan error email
        $response->assertSessionHasErrors(['email']);
    }

    #[Test]
    public function register_gagal_jika_input_tidak_valid()
    {
        $response = $this->post('/register', [
            'name' => '', // Kosong
            'email' => 'invalidemail', // Format salah
            'phone_number' => '0812',
            'password' => '123', // Terlalu pendek
            'password_confirmation' => 'abc', // Tidak sama
        ]);

        $response->assertSessionHasErrors([
            'name',
            'email',
            'password',
        ]);
    }
}
