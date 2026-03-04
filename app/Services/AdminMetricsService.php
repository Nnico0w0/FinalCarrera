<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminMetricsService
{
    public function buildSummary(): array
    {
        $paidOrdersQuery = Order::query()->whereRaw('LOWER(status) = ?', ['paid']);

        $totalOrders = Order::count();
        $paidOrdersCount = (clone $paidOrdersQuery)->count();
        $totalRevenue = (clone $paidOrdersQuery)->sum('total_price');
        $itemsSold = OrderItem::whereHas('order', function ($query) {
            $query->whereRaw('LOWER(status) = ?', ['paid']);
        })->sum('quantity');
        $averageTicket = $paidOrdersCount > 0 ? round($totalRevenue / $paidOrdersCount, 2) : 0;

        $trendWindowStart = Carbon::now()->subMonths(11)->startOfMonth();
        $salesTrend = Order::select('created_at', 'total_price')
            ->whereRaw('LOWER(status) = ?', ['paid'])
            ->where('created_at', '>=', $trendWindowStart)
            ->orderBy('created_at')
            ->get()
            ->groupBy(function (Order $order) {
                return $order->created_at?->format('Y-m');
            })
            ->filter()
            ->map(function ($orders, $period) {
                $date = Carbon::createFromFormat('Y-m', $period)->locale('es');
                return [
                    'label' => $date->translatedFormat('M Y'),
                    'total' => (float) $orders->sum('total_price'),
                ];
            })
            ->values();

        $recentOrders = Order::with(['order_items', 'customer'])
            ->latest()
            ->take(10)
            ->get()
            ->map(function (Order $order) {
                return [
                    'id' => $order->id,
                    'customer' => $order->customer?->name ?? 'Cliente invitado',
                    'email' => $order->customer?->email,
                    'status' => $order->status,
                    'total' => (float) $order->total_price,
                    'items' => $order->order_items->sum('quantity'),
                    'created_at' => optional($order->created_at)->toIso8601String(),
                ];
            })
            ->toArray();

        $topProducts = OrderItem::select(
            'product_id',
            DB::raw('SUM(quantity) as units'),
            DB::raw('SUM(quantity * unit_price) as revenue')
        )
            ->with('product:id,title')
            ->groupBy('product_id')
            ->orderByDesc('units')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'product' => $item->product?->title ?? 'Producto eliminado',
                    'units' => (int) $item->units,
                    'revenue' => (float) $item->revenue,
                ];
            })
            ->toArray();

        $topCustomers = User::select(
            'users.id',
            'users.name',
            'users.email',
            DB::raw('SUM(orders.total_price) as total_spent'),
            DB::raw('COUNT(orders.id) as orders_count')
        )
            ->join('orders', 'orders.created_by', '=', 'users.id')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->take(5)
            ->get()
            ->map(function ($customer) {
                return [
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'orders' => (int) $customer->orders_count,
                    'spent' => (float) $customer->total_spent,
                ];
            })
            ->toArray();

        $lowStockProducts = Product::select('id', 'title', 'quantity', 'price')
            ->orderBy('quantity')
            ->take(6)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'quantity' => (int) $product->quantity,
                    'price' => (float) $product->price,
                ];
            })
            ->toArray();

        return [
            'stats' => [
                'total_orders' => $totalOrders,
                'paid_orders' => $paidOrdersCount,
                'total_revenue' => $totalRevenue,
                'average_ticket' => $averageTicket,
                'items_sold' => $itemsSold,
            ],
            'salesTrend' => $salesTrend,
            'recentOrders' => $recentOrders,
            'topProducts' => $topProducts,
            'topCustomers' => $topCustomers,
            'lowStockProducts' => $lowStockProducts,
        ];
    }
}
