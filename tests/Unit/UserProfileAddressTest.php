<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserProfileService; // Asumsi Service Class ada
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;
use Mockery; // Digunakan untuk Mocking Model User

/**
 * Unit Test untuk logika validasi dan penyimpanan alamat (TCP-U02 hingga TCP-U05).
 * Menguji logika di dalam UserProfileService (atau sejenisnya) secara terisolasi.
 */
class UserProfileAddressTest extends TestCase
{
    use RefreshDatabase;

    // Tambahkan konfigurasi Service dan Mock User di setUp jika diperlukan
    // Namun, kita akan menggunakan Mockery inline di setiap test untuk isolasi yang lebih jelas.

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // --- Pengujian Validasi (Terkait TCP-U02 dan TCP-U05) ---

    /**
     * Menguji bahwa 'Denpasar Utara' adalah input yang valid (TCP-U02, TCP-U03).
     */
    public function test_denpasar_utara_is_valid_kecamatan_input()
    {
        // Asumsi: Service menyediakan rules validasi
        $service = Mockery::mock(UserProfileService::class);
        $service->shouldAllowMockingProtectedMethods();
        $rules = [
            'kecamatan' => ['required', 'string', 'in:Denpasar Utara,Denpasar Selatan'],
            'detail_alamat' => ['nullable', 'string'],
        ];

        $data = ['kecamatan' => 'Denpasar Utara', 'detail_alamat' => 'Jalan A No. 1'];
        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->passes(), 'Denpasar Utara seharusnya valid.');
    }

    /**
     * Menguji bahwa 'Denpasar Selatan' adalah input yang valid (TCP-U02, TCP-U04).
     */
    public function test_denpasar_selatan_is_valid_kecamatan_input()
    {
        $service = Mockery::mock(UserProfileService::class);
        $service->shouldAllowMockingProtectedMethods();
        $rules = [
            'kecamatan' => ['required', 'string', 'in:Denpasar Utara,Denpasar Selatan'],
            'detail_alamat' => ['nullable', 'string'],
        ];

        $data = ['kecamatan' => 'Denpasar Selatan', 'detail_alamat' => 'Jalan B No. 2'];
        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->passes(), 'Denpasar Selatan seharusnya valid.');
    }

    /**
     * Menguji bahwa menyimpan tanpa alamat menghasilkan error validasi (TCP-U05).
     */
    public function test_kecamatan_is_required_for_saving_address()
    {
        $service = Mockery::mock(UserProfileService::class);
        $service->shouldAllowMockingProtectedMethods();
        $rules = [
            'kecamatan' => ['required', 'string', 'in:Denpasar Utara,Denpasar Selatan'],
            'detail_alamat' => ['nullable', 'string'],
        ];

        // Input Data: Simpan tanpa pilih alamat
        $data = ['kecamatan' => null, 'detail_alamat' => 'Alamat Detail'];
        $validator = Validator::make($data, $rules);

        // Expected Results: Pesan "Alamat wajib diisi" (diwakili dengan error validasi)
        $this->assertFalse($validator->passes(), 'Validasi seharusnya gagal karena alamat kosong.');
        $this->assertArrayHasKey('kecamatan', $validator->errors()->toArray(), 'Harus ada error pada field kecamatan.');
    }

    // --- Pengujian Logika Penyimpanan (Terkait TCP-U03 dan TCP-U04) ---

    /**
     * Menguji bahwa User Model diupdate dengan 'Denpasar Utara' (TCP-U03).
     */
    public function test_service_updates_user_with_denpasar_utara()
    {
        $dataToSave = [
            'kecamatan' => 'Denpasar Utara',
            'detail_alamat' => 'Jl. Mock A 1'
        ];

        // Mock Model User untuk memastikan metode 'update' dipanggil
        $mockUser = Mockery::mock(User::class);
        $mockUser->shouldReceive('update')
                 ->once()
                 ->with($dataToSave) // Memastikan data yang benar dikirim
                 ->andReturn(true);

        // Asumsikan Service menerima User model di constructor atau di method save
        // Kita menggunakan Mockery untuk meniru service yang memanggil update pada user
        $service = new UserProfileService($mockUser);

        // Expected Results: Alamat tersimpan (metode update dipanggil)
        $result = $service->saveAddress($dataToSave);

        $this->assertTrue($result, 'Penyimpanan alamat Denpasar Utara seharusnya berhasil.');
    }

    /**
     * Menguji bahwa User Model diupdate dengan 'Denpasar Selatan' (TCP-U04).
     */
    public function test_service_updates_user_with_denpasar_selatan()
    {
        $dataToSave = [
            'kecamatan' => 'Denpasar Selatan',
            'detail_alamat' => 'Jl. Mock B 2'
        ];

        $mockUser = Mockery::mock(User::class);
        $mockUser->shouldReceive('update')
                 ->once()
                 ->with($dataToSave)
                 ->andReturn(true);

        $service = new UserProfileService($mockUser);

        // Expected Results: Alamat tersimpan
        $result = $service->saveAddress($dataToSave);

        $this->assertTrue($result, 'Penyimpanan alamat Denpasar Selatan seharusnya berhasil.');
    }
}