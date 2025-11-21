<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Broadcast;

class AdminChatWithUserTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat akun admin dan user dummy
        $this->admin = Admin::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
        ]);

        $this->user = User::factory()->create([
            'name' => 'User Test',
            'email' => 'user@example.com',
        ]);
    }

    #[Test]
    public function test_admin_can_see_user_list_in_chat_menu()
    {
        // Buat pesan dummy agar user muncul di daftar chat
        Message::factory()->create([
            'sender_id' => $this->user->id,
            'receiver_id' => $this->admin->id,
            'receiver_type' => 'admin',
            'message' => 'Halo admin!',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.chat.index'));

        $response->assertStatus(200);
        $response->assertSee($this->user->name); // Daftar pelanggan tampil
    }

    #[Test]
    public function test_admin_can_search_user_by_name()
    {
        $keyword = 'User';

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.chat.search', ['q' => $keyword]));

        $response->assertStatus(200);
        $response->assertSee($this->user->name); // hasil filter sesuai nama
    }

    #[Test]
    public function test_admin_can_open_chat_history_with_user()
    {
        Message::factory()->create([
            'sender_id' => $this->user->id,
            'receiver_id' => $this->admin->id,
            'receiver_type' => 'admin',
            'message' => 'Halo lagi!',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.chat.messages', ['user_id' => $this->user->id]));

        $response->assertStatus(200);
        $response->assertSee('Halo lagi!');
    }

    #[Test]
    public function test_admin_can_send_message_to_user()
    {
        $payload = [
            'receiver_id' => $this->user->id,
            'receiver_type' => 'user',
            'message' => 'Selamat datang!',
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.chat.send'), $payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('messages', [
            'sender_id' => $this->admin->id,
            'receiver_id' => $this->user->id,
            'message' => 'Selamat datang!',
        ]);
    }

    #[Test] 
    public function test_admin_cannot_send_empty_message()
    {
        $payload = [
            'receiver_id' => $this->user->id,
            'receiver_type' => 'user',
            'message' => '',
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.chat.send'), $payload);

        $response->assertSessionHasErrors(['message']);
    }

    #[Test]
    public function test_chat_data_persists_after_reload()
    {
        Message::factory()->create([
            'sender_id' => $this->user->id,
            'receiver_id' => $this->admin->id,
            'receiver_type' => 'admin',
            'message' => 'Pesan reload',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.chat.index'));

        $response->assertStatus(200);
        $response->assertSee('Pesan reload');
    }

    #[Test]
    public function test_admin_chat_fails_without_internet_connection()
    {
        Broadcast::shouldReceive('socket')
            ->andThrow(new \Exception('Gagal memuat data pengguna.'));

        try {
            $this->actingAs($this->admin, 'admin')
                ->get(route('admin.chat.index'));
            $this->fail('Seharusnya koneksi gagal.');
        } catch (\Exception $e) {
            $this->assertEquals('Gagal memuat data pengguna.', $e->getMessage());
        }
    }

    #[Test]
    public function test_default_chat_view_before_selecting_user()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.chat.index'));

        $response->assertStatus(200);
        $response->assertSee('Pilih pengguna untuk memulai obrolan');
    }
}
