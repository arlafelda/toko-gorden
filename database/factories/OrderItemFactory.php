<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $product = Product::factory()->create();

        return [
            'order_id'   => Order::factory(),
            'produk_id'  => $product->id,
            'ukuran'     => $this->faker->randomElement(['L100xT200', 'L150xT250']),
            'jumlah'     => $this->faker->numberBetween(1, 5),
            'harga'      => $product->harga ?? $this->faker->numberBetween(50000, 500000),
            'quantity'   => $this->faker->numberBetween(1, 5),
            'price'      => $product->harga ?? $this->faker->randomFloat(2, 50000, 500000),
        ];
    }
}
