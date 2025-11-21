<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_produk' => $this->faker->words(3, true),
            'deskripsi' => $this->faker->sentence(),
            'harga' => $this->faker->randomFloat(2, 50000, 500000),
            'stok' => $this->faker->numberBetween(1, 100),
            'gambar' => null,
            'jenis_gorden' => $this->faker->randomElement(['Minimalis', 'Blackout', 'Vitras']),
        ];
    }
}
