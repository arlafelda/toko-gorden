<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Broadcast;

class UserChatWithAdminTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat akun user dan admin dummy
        $this->user = User::factory()->create([
            'name' => 'User Test',
            'email' => 'user@example.com',
        ]);

        $this->admin = Admin::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
        ]);
    }

    #[Test]
    public function test_user_can_open_chat_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('chat.user'));

        $response->assertStatus(200);
        $response->assertSee('Obrolan'); // pastikan halaman chat tampil
    }

    #[Test]
    public function test_user_can_send_message_to_admin()
    {
        $payload = [
            'receiver_id' => $this->admin->id,
            'receiver_type' => 'admin',
            'message' => 'Halo',
        ];

        $response = $this->actingAs($this->user)
            ->post(route('chat.send'), $payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('messages', [
            'sender_id' => $this->user->id,
            'receiver_id' => $this->admin->id,
            'message' => 'Halo',
        ]);
    }

    #[Test]
    public function test_user_receives_message_from_admin()
    {
        // Simulasikan pesan masuk dari admin
        $message = Message::factory()->create([
            'sender_id' => $this->admin->id,
            'receiver_id' => $this->user->id,
            'receiver_type' => 'user',
            'message' => 'Selamat datang',
        ]);

        // Ambil pesan melalui endpoint getMessages
        $response = $this->actingAs($this->user)
            ->get(route('chat.messages', [
                'receiver_id' => $this->admin->id,
                'receiver_type' => 'admin',
            ]));

        $response->assertStatus(200);
        $response->assertSee('Selamat datang');
    }

    #[Test]
    public function test_chat_fails_without_internet_connection()
    {
        // Simulasi error dengan mematikan koneksi broadcast (socket)
        Broadcast::shouldReceive('socket')
            ->andThrow(new \Exception('Network Error'));

        $payload = [
            'receiver_id' => $this->admin->id,
            'receiver_type' => 'admin',
            'message' => 'Test tanpa koneksi',
        ];

        $this->actingAs($this->user);

        try {
            $this->post(route('chat.send'), $payload);
            $this->fail('Koneksi seharusnya gagal');
        } catch (\Exception $e) {
            $this->assertEquals('Network Error', $e->getMessage());
        }
    }
}
