<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Carbon\Carbon;

class AdminLaporanBulananTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat admin
        $this->admin = Admin::factory()->create([
            'username' => 'admin',
            'password' => bcrypt('admin123'),
        ]);
    }

    #[Test]
    public function TCL_A01_buka_menu_laporan_bulanan()
    {
        $this->actingAs($this->admin, 'admin');

        // Buat beberapa order di bulan ini
        Order::factory()->count(5)->create([
            'status' => 'Selesai',
            'created_at' => now(),
        ]);

        $response = $this->get(route('admin.laporan'));
        $response->assertStatus(200)
                 ->assertSee('Laporan Bulanan');
    }

    #[Test]
    public function TCL_A02_unduh_laporan_bulanan()
    {
        $this->actingAs($this->admin, 'admin');

        // Request untuk export PDF/XLSX
        $responsePdf = $this->get(route('admin.laporan.generate', ['format' => 'pdf']));
        $responsePdf->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $responsePdf->headers->get('content-type'));

        $responseXlsx = $this->get(route('admin.laporan.generate', ['format' => 'xlsx']));
        $responseXlsx->assertStatus(200);
        $this->assertStringContainsString('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $responseXlsx->headers->get('content-type'));
    }

    #[Test]
    public function TCL_A03_buka_menu_laporan_pendapatan_harian()
    {
        $this->actingAs($this->admin, 'admin');

        $date = Carbon::parse('2025-10-17');

        Order::factory()->count(7)->create([
            'status' => 'Selesai',
            'created_at' => $date,
        ]);

        $response = $this->get(route('admin.laporan', ['date' => $date->toDateString()]));
        $response->assertStatus(200)
                 ->assertSee('Pendapatan Harian');
    }

    #[Test]
    public function TCL_A04_ubah_tanggal_laporan_harian()
    {
        $this->actingAs($this->admin, 'admin');

        $newDate = Carbon::parse('2025-10-25');

        $response = $this->get(route('admin.laporan', ['date' => $newDate->toDateString()]));
        $response->assertStatus(200)
                 ->assertSee($newDate->format('d-m-Y'));
    }

    #[Test]
    public function TCL_A05_periksa_total_pendapatan()
    {
        $this->actingAs($this->admin, 'admin');

        Order::factory()->create([
            'status' => 'Selesai',
            'total' => 100000,
            'created_at' => now()->subDays(5),
        ]);

        Order::factory()->create([
            'status' => 'Selesai',
            'total' => 50000,
            'created_at' => now()->subDays(3),
        ]);

        $response = $this->get(route('admin.laporan'));
        $response->assertStatus(200)
                 ->assertSee('150000'); // total pendapatan
    }

    #[Test]
    public function TCL_A06_periksa_jumlah_order()
    {
        $this->actingAs($this->admin, 'admin');

        Order::factory()->count(10)->create([
            'status' => 'Selesai'
        ]);

        $response = $this->get(route('admin.laporan'));
        $response->assertStatus(200)
                 ->assertSee('10'); // jumlah order
    }

    #[Test]
    public function TCL_A07_periksa_jumlah_pelanggan_terdaftar()
    {
        $this->actingAs($this->admin, 'admin');

        User::factory()->count(8)->create();

        $response = $this->get(route('admin.laporan'));
        $response->assertStatus(200)
                 ->assertSee('8'); // jumlah pelanggan
    }

    #[Test]
    public function TCL_A08_tampilan_laporan_tanpa_data_transaksi()
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->get(route('admin.laporan', ['date' => now()->subMonth()->toDateString()]));
        $response->assertStatus(200)
                 ->assertSee('Tidak ada data');
    }
}
