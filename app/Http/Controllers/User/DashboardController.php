<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();

        $orders = Order::with('order_items.product.brand', 'order_items.product.category')
            ->where('created_by', $user->id)
            ->latest()
            ->get();

        $paidOrders = $orders->filter(function ($order) {
            return strtolower($order->status ?? '') === 'paid';
        });

        $recentProducts = $orders->flatMap(function ($order) {
            return $order->order_items->map(function ($item) use ($order) {
                $product = $item->product;

                if (!$product) {
                    return null;
                }

                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'brand' => optional($product->brand)->name,
                    'category' => optional($product->category)->name,
                    'price' => $product->price,
                    'order_id' => $order->id,
                    'purchased_at' => $order->created_at,
                ];
            });
        })
            ->filter()
            ->unique('id')
            ->sortByDesc('purchased_at')
            ->values()
            ->take(6);

        return Inertia::render('User/Dashboard', [
            'orders' => $orders,
            'profile' => [
                'name' => $user->name,
                'email' => $user->email,
                'member_since' => $user->created_at,
            ],
            'stats' => [
                'total_orders' => $orders->count(),
                'items_count' => $orders->sum(function ($order) {
                    return $order->order_items->sum('quantity');
                }),
                'pending_orders' => $orders->reject(function ($order) {
                    return strtolower($order->status ?? '') === 'paid';
                })->count(),
                'total_spent' => $paidOrders->sum('total_price'),
                'last_order_at' => optional($orders->first())->created_at,
            ],
            'recentProducts' => $recentProducts,
        ]);
    }
}
