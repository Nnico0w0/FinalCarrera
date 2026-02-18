<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Inertia\Inertia;

class PricingController extends Controller
{
    public function index()
    {
        // Get top 3 best-selling products
        $topProducts = Product::with(['product_images', 'category', 'brand'])
            ->where('published', true)
            ->where('inStock', true)
            ->orderBy('sales_count', 'desc')
            ->take(3)
            ->get();

        return Inertia::render('User/Pricing', [
            'topProducts' => $topProducts
        ]);
    }
}
