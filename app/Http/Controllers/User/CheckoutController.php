<?php

namespace App\Http\Controllers\User;

use App\Helper\Cart;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $addressData = $request->input('address', []);
        $hasNewAddress = ! empty(trim((string) ($addressData['address1'] ?? '')));

        $cartItems = CartItem::with('product')->where('user_id', $user->id)->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('home')->with('info', 'Your cart is empty');
        }

        try {
            $order = DB::transaction(function () use ($user, $addressData, $hasNewAddress, $cartItems) {
                if ($hasNewAddress) {
                    UserAddress::where('user_id', $user->id)->update(['isMain' => 0]);
                    $newAddress = new UserAddress();
                    $newAddress->address1 = $addressData['address1'] ?? null;
                    $newAddress->state = $addressData['state'] ?? null;
                    $newAddress->zipcode = $addressData['zipcode'] ?? null;
                    $newAddress->city = $addressData['city'] ?? null;
                    $newAddress->country_code = $addressData['country_code'] ?? null;
                    $newAddress->type = $addressData['type'] ?? 'home';
                    $newAddress->user_id = $user->id;
                    $newAddress->isMain = 1;
                    $newAddress->save();
                }

                $mainAddress = UserAddress::where('user_id', $user->id)->where('isMain', 1)->first();
                if (! $mainAddress) {
                    throw new \RuntimeException('Please add a delivery address before confirming your order.');
                }

                $productIds = $cartItems->pluck('product_id')->all();
                $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

                $total = 0;
                $lines = [];

                foreach ($cartItems as $cartItem) {
                    $product = $products->get($cartItem->product_id);
                    if (! $product) {
                        throw new \RuntimeException('One product in your cart is no longer available.');
                    }

                    if ((int) $product->quantity < (int) $cartItem->quantity) {
                        throw new \RuntimeException("Insufficient stock for {$product->title}. Available: {$product->quantity}.");
                    }

                    $unitPrice = (float) $product->price;
                    $lineQuantity = (int) $cartItem->quantity;

                    $lines[] = [
                        'product' => $product,
                        'quantity' => $lineQuantity,
                        'unit_price' => $unitPrice,
                    ];

                    $total += $lineQuantity * $unitPrice;
                }

                $order = Order::create([
                    'status' => 'paid',
                    'total_price' => $total,
                    'session_id' => 'SIM-' . Str::uuid(),
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                    'user_address_id' => $mainAddress->id,
                ]);

                foreach ($lines as $line) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $line['product']->id,
                        'quantity' => $line['quantity'],
                        'unit_price' => $line['unit_price'],
                    ]);

                    $newQuantity = max(((int) $line['product']->quantity - $line['quantity']), 0);
                    $line['product']->update([
                        'quantity' => $newQuantity,
                        'inStock' => $newQuantity > 0,
                    ]);
                }

                CartItem::where('user_id', $user->id)->delete();
                Cart::setCookieCartItems([]);

                return $order;
            });

            return redirect()->route('dashboard')->with('success', "Purchase #{$order->id} completed successfully.");
        } catch (\Throwable $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function success(Request $request)
    {
        return redirect()->route('dashboard');
    }

    public function cancel(Request $request)
    {
        return redirect()->route('cart.view')->with('info', 'Checkout was canceled.');
    }
}
