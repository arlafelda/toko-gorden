<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class UserLogoutTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
 
    public function test_user_can_logout_and_redirected_to_login_page(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        // Login user
        $this->actingAs($user);

        // Panggil route logout
        $response = $this->post('/logout');

        // Pastikan user diarahkan ke halaman login
        $response->assertRedirect('/login');

        // Pastikan user sudah tidak terautentikasi
        $this->assertGuest();
    }

    #[Test]
    public function test_user_cannot_access_profile_after_logout(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        // Login user
        $this->actingAs($user);

        // Logout
        $this->post('/logout');

        // Akses halaman profil setelah logout
        $response = $this->get('/profile');

        // Pastikan diarahkan ke halaman login
        $response->assertRedirect('/login');
    }
}
