<?php

namespace App\Http\Controllers\User;

use App\Helper\Cart;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    private const EMPTY_CART_MESSAGE = 'Your cart is empty';

    public function view(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $cartItems = CartItem::where('user_id', $user->id)->get();
            $userAddress = UserAddress::where('user_id', $user->id)->where('isMain', 1)->first();
            if ($cartItems->count() > 0) {
                $cart = new CartResource(Cart::getProductsAndCartItems());
                return Inertia::render(
                    'User/CartList',
                    [
                        'cart' => $cart,
                        'userAddress' => $userAddress
                    ]
                );
            } else {
                return redirect()->back()->with('info', self::EMPTY_CART_MESSAGE);
            }
        } else {
            $cartItems = Cart::getCookieCartItems();
            if (count($cartItems) > 0) {
                $cart = new CartResource(Cart::getProductsAndCartItems());
                return Inertia::render('User/CartList', ['cart' => $cart]);
            } else {
                return redirect()->back()->with('info', self::EMPTY_CART_MESSAGE);
            }
        }
    }
    public function store(Request $request, Product $product)
    {
        $quantity = max(1, (int) $request->post('quantity', 1));
        $availableStock = max(0, (int) $product->quantity);

        if ($availableStock < 1) {
            return redirect()->back()->with('info', 'This product is out of stock.');
        }

        $user = $request->user();

        if ($user) {
            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first();
            if ($cartItem) {
                $newQuantity = min(((int) $cartItem->quantity + $quantity), $availableStock);
                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => min($quantity, $availableStock),
                ]);
            }
        } else {
            $cartItems = Cart::getCookieCartItems();
            $isProductExists = false;
            foreach ($cartItems as &$item) {
                if ($item['product_id'] === $product->id) {
                    $item['quantity'] = min(((int) $item['quantity'] + $quantity), $availableStock);
                    $isProductExists = true;
                    break;
                }
            }
            unset($item);

            if (!$isProductExists) {
                $cartItems[] = [
                    'user_id' => null,
                    'product_id' => $product->id,
                    'quantity' => min($quantity, $availableStock),
                    'price' => $product->price,
                ];
            }
            Cart::setCookieCartItems($cartItems);
        }

        return redirect()->back()->with('success', 'cart added successfully');
    }
    public function update(Request $request, Product $product)
    {
        $requestedQuantity = max(1, $request->integer('quantity'));
        $availableStock = max(0, (int) $product->quantity);
        if ($availableStock < 1) {
            $user = $request->user();
            if ($user) {
                CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->delete();
            } else {
                $cartItems = Cart::getCookieCartItems();
                foreach ($cartItems as $index => $item) {
                    if ($item['product_id'] === $product->id) {
                        array_splice($cartItems, $index, 1);
                        break;
                    }
                }
                Cart::setCookieCartItems($cartItems);
            }

            return redirect()->back()->with('info', 'This product is out of stock.');
        }

        $quantity = min($requestedQuantity, $availableStock);
        $user = $request->user();
        if ($user) {
            CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->update(['quantity' => $quantity]);
        } else {
            $cartItems = Cart::getCookieCartItems();
            foreach ($cartItems as &$item) {
                if ($item['product_id'] === $product->id) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }
            Cart::setCookieCartItems($cartItems);
        }

        return redirect()->back();
    }
    public function delete(Request $request, Product $product)
    {
        $user = $request->user();
        if ($user) {
            CartItem::query()->where(['user_id' => $user->id, 'product_id' => $product->id])->first()?->delete();
            if (CartItem::where('user_id', $user->id)->count() <= 0) {
                return redirect()->route('home')->with('info', self::EMPTY_CART_MESSAGE);
            } else {
                return redirect()->back()->with('success', 'item removed successfully');
            }
        } else {
            $cartItems = Cart::getCookieCartItems();
            foreach ($cartItems as $i => &$item) {
                if ($item['product_id'] === $product->id) {
                    array_splice($cartItems, $i, 1);
                    break;
                }
            }
            Cart::setCookieCartItems($cartItems);
            if (count($cartItems) <= 0) {
                return redirect()->route('home')->with('info', self::EMPTY_CART_MESSAGE);
            } else {
                return redirect()->back()->with('success', 'item removed successfully');
            }
        }
    }
}
