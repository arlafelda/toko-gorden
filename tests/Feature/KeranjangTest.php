<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Auth;

class KeranjangTest extends TestCase
{
    use RefreshDatabase;

    // ✅ Deklarasi properti kelas
    protected User $user;
    protected Product $product;
    protected Product $product2;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var \App\Models\User $this->user */
        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        /** @var \App\Models\Product $this->product */
        $this->product = Product::factory()->create([
            'nama_produk' => 'GORDEN BASIC',
            'harga' => 100000,
            'stok' => 20
        ]);

        /** @var \App\Models\Product $this->product2 */
        $this->product2 = Product::factory()->create([
            'nama_produk' => 'GORDEN PREMIUM',
            'harga' => 200000,
            'stok' => 10
        ]);
    }

    #[Test]
    public function tambah_produk_ke_keranjang_CRJ_T01()
    {
        $response = $this->post(route('keranjang.tambah', $this->product->id));
        $response->assertStatus(200)
            ->assertJsonFragment(['nama_produk' => 'GORDEN BASIC', 'qty' => 1]);
    }

    #[Test]
    public function tambah_produk_yang_sama_CRJ_T02()
    {
        $this->post(route('keranjang.tambah', $this->product->id));
        $response = $this->post(route('keranjang.tambah', $this->product->id));
        $response->assertStatus(200)
            ->assertJsonFragment(['qty' => 2]);
    }

    #[Test]
    public function ubah_qty_produk_CRJ_T03()
    {
        $this->post(route('keranjang.tambah', $this->product->id));
        $response = $this->patch(route('keranjang.ubahQty', $this->product->id), ['qty' => 2]);
        $response->assertStatus(200)
            ->assertJsonFragment(['qty' => 2]);
    }

    #[Test]
    public function ubah_qty_melebihi_stok_CRJ_T04()
    {
        $this->post(route('keranjang.tambah', $this->product->id));
        $response = $this->patch(route('keranjang.ubahQty', $this->product->id), ['qty' => 21]);
        $response->assertStatus(400)
            ->assertJsonFragment(['error' => 'Jumlah melebihi stok']);
    }

    #[Test]
    public function kurangi_qty_ke_0_CRJ_T05()
    {
        $this->post(route('keranjang.tambah', $this->product->id));
        $response = $this->patch(route('keranjang.ubahQty', $this->product->id), ['qty' => 0]);
        $response->assertStatus(200);
        $this->assertEmpty(session('keranjang'));
    }

    #[Test]
    public function hapus_produk_CRJ_T06()
    {
        $this->post(route('keranjang.tambah', $this->product->id));
        $response = $this->delete(route('keranjang.hapus', $this->product->id));
        $response->assertStatus(200);
        $this->assertEmpty(session('keranjang'));
    }

    #[Test]
    public function total_bayar_CRJ_T07()
    {
        $this->post(route('keranjang.tambah', $this->product->id));
        $this->post(route('keranjang.tambah', $this->product2->id));

        $keranjang = session('keranjang');
        $total = $keranjang[$this->product->id]['qty'] * $this->product->harga +
            $keranjang[$this->product2->id]['qty'] * $this->product2->harga;

        $this->assertEquals(100000 + 200000, $total);
    }

    #[Test]
    public function checkout_tanpa_produk_CRJ_T08()
    {
        $response = $this->post(route('keranjang.checkout'));
        $response->assertStatus(400)
            ->assertJsonFragment(['error' => 'Pilih produk terlebih dahulu']);
    }

    #[Test]
    public function checkout_dengan_produk_CRJ_T09()
    {
        $this->post(route('keranjang.tambah', $this->product->id));
        $response = $this->post(route('keranjang.checkout'));
        $response->assertStatus(200)
            ->assertJsonFragment(['success' => 'Checkout berhasil']);
        $this->assertEmpty(session('keranjang'));
    }

    #[Test]
    public function reload_halaman_setelah_isi_keranjang_CRJ_T12()
    {
        $this->post(route('keranjang.tambah', $this->product->id));
        $response = $this->get(route('keranjang.tampil'));
        $response->assertStatus(200)
            ->assertSee('GORDEN BASIC');
    }

    #[Test]
    public function login_user_baru_tanpa_keranjang_CRJ_T13()
    {
        /** @var \App\Models\User $userBaru */
        $userBaru = User::factory()->create();
        $this->actingAs($userBaru);


        $response = $this->get(route('keranjang.tampil'));
        $response->assertStatus(200);

        $this->assertEmpty(session('keranjang'));
    }

    #[Test]
    public function login_user_lama_dengan_keranjang_CRJ_T14()
    {
        $this->post(route('keranjang.tambah', $this->product->id));
        $keranjangSebelumnya = session('keranjang');

        $this->actingAs($this->user);
        session(['keranjang' => $keranjangSebelumnya]);

        $response = $this->get(route('keranjang.tampil'));
        $response->assertSee('GORDEN BASIC');
    }

    #[Test]
    public function tambah_produk_dari_halaman_detail_CRJ_T16()
    {
        $response = $this->post(route('keranjang.tambah', $this->product->id));
        $response->assertStatus(200)
            ->assertJsonFragment(['nama_produk' => 'GORDEN BASIC']);
    }

    #[Test]
    public function user_tanpa_login_CRJ_T18()
    {
        Auth::logout();
        $response = $this->post(route('keranjang.tambah', $this->product->id));
        $response->assertRedirect('/login');
    }
}
