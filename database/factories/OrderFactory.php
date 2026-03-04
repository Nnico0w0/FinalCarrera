<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $purchaseDate = $this->faker->dateTimeBetween('-8 months', 'now');

        return [
            'total_price' => $this->faker->randomFloat(2, 250, 6500),
            'status' => $this->faker->randomElement(['paid', 'unpaid']),
            'session_id' => (string) Str::uuid(),
            'user_address_id' => UserAddress::factory(),
            'created_by' => null,
            'updated_by' => null,
            'created_at' => $purchaseDate,
            'updated_at' => $purchaseDate,
        ];
    }
}
