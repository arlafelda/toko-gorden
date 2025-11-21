<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Hapus dulu admin dengan email yang sama supaya gak error duplicate
        Admin::where('email', 'admin@example.com')->delete();

        // Buat admin baru
        Admin::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
 // otomatis di-hash oleh mutator di model
        ]);
    }
}
