<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Seeder;

class UserPurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        if ($products->count() < 5) {
            Product::factory(10)->create();
            $products = Product::all();
        }

        User::factory()
            ->count(5)
            ->create()
            ->each(function (User $user) use ($products) {
                $address = UserAddress::factory()->for($user)->create([
                    'isMain' => true,
                ]);

                $ordersToCreate = fake()->numberBetween(3, 10);

                for ($i = 0; $i < $ordersToCreate; $i++) {
                    $selectionCount = min(fake()->numberBetween(1, 4), $products->count());
                    $selection = $products->random($selectionCount);
                    $items = $selection instanceof \Illuminate\Support\Collection ? $selection : collect([$selection]);

                    $total = 0;
                    $lines = [];

                    foreach ($items as $product) {
                        $quantity = fake()->numberBetween(1, 3);
                        $lines[] = [
                            'product_id' => $product->id,
                            'quantity' => $quantity,
                            'unit_price' => $product->price,
                        ];
                        $total += $quantity * $product->price;
                    }

                    $order = Order::factory()->create([
                        'user_address_id' => $address->id,
                        'total_price' => $total,
                        'status' => fake()->randomElement(['paid', 'pending', 'unpaid']),
                        'created_by' => $user->id,
                        'updated_by' => $user->id,
                    ]);

                    foreach ($lines as $line) {
                        OrderItem::factory()->create(array_merge($line, [
                            'order_id' => $order->id,
                        ]));
                    }
                }
            });
    }
}
