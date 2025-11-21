<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class UserProfileAddressTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_profile_page_shows()
    {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/profile');
        $response->assertStatus(200);
        $response->assertSeeText('Profil Pengguna');
    }

    #[Test]
    public function test_kecamatan_dropdown_contains_denpasar_utara_and_selatan()
    {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/profile/edit');
        $response->assertStatus(200);
        $response->assertSee('Denpasar Utara');
        $response->assertSee('Denpasar Selatan');
    }

    #[Test]
    public function test_save_address_denpasar_utara()
    {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();

        $payload = [
            'name' => $user->name,
            'phone_number' => '08123456789',
            'jalan' => 'Jl. Test Utara',
            'desa' => 'Ubung',
            'kecamatan' => 'Denpasar Utara',
            'kota' => 'Denpasar',
        ];

        $response = $this->actingAs($user)->post('/profile/update', $payload);

        $response->assertRedirect(route('user.profile'));

        $fullAddress = $payload['jalan'] . ', Desa ' . $payload['desa'] . ', Kec. ' . $payload['kecamatan'] . ', ' . $payload['kota'];

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'address' => $fullAddress,
        ]);
    }

    #[Test]
    public function test_save_address_denpasar_selatan()
    {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();

        $payload = [
            'name' => $user->name,
            'phone_number' => '08123456780',
            'jalan' => 'Jl. Test Selatan',
            'desa' => 'Panjer',
            'kecamatan' => 'Denpasar Selatan',
            'kota' => 'Denpasar',
        ];

        $response = $this->actingAs($user)->post('/profile/update', $payload);

        $response->assertRedirect(route('user.profile'));

        $fullAddress = $payload['jalan'] . ', Desa ' . $payload['desa'] . ', Kec. ' . $payload['kecamatan'] . ', ' . $payload['kota'];

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'address' => $fullAddress,
        ]);
    }

    #[Test]
    public function test_save_without_address_shows_validation_error()
    {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();

        $payload = [
            'name' => $user->name,
            'phone_number' => '08123456781',
            'jalan' => '',
            'desa' => '',
            'kecamatan' => '',
            'kota' => '',
        ];

        $response = $this->actingAs($user)->from('/profile/edit')->post('/profile/update', $payload);

        $response->assertRedirect('/profile/edit');
        $response->assertSessionHasErrors(['jalan', 'desa', 'kecamatan', 'kota']);
    }
}
