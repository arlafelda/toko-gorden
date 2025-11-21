<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_id' => 'ORDER-' . strtoupper($this->faker->bothify('####??')),
            'order_date' => now(),
            'total' => $this->faker->randomFloat(2, 100000, 1000000),
            'status' => 'pending',
            'payment_type' => 'manual',
        ];
    }
}
