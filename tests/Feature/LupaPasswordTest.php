<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Notification;

class LupaPasswordTest extends TestCase
{
    #[Test]
    public function halaman_lupa_password_bisa_diakses()
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
        // Cocok dengan judul kamu
        $response->assertSee('Lupa Kata Sandi');
    }

    #[Test]
    public function user_bisa_request_reset_password()
    {
        Mail::fake();
        Notification::fake();

        $user = User::factory()->create();

        $response = $this->post('/forgot-password', [
            'email' => $user->email
        ]);

        $response->assertStatus(302);
        // Sesuai Laravel: "Kami telah mengirimkan link reset kata sandi ke email Anda!"
        $response->assertSessionHas('status', 'Link reset password telah dikirim ke email Anda.');

    }

    #[Test]
    public function user_bisa_reset_password_dengan_token_valid()
    {
        $user = User::factory()->create();

        $token = Password::createToken($user);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'passwordbaru123',
            'password_confirmation' => 'passwordbaru123',
        ]);

        $response->assertStatus(302);

        // Cocokkan dengan teks session di login setelah reset berhasil
        $response->assertSessionHas('status', 'Kata sandi berhasil diubah. Silakan login kembali.');
    }
}
