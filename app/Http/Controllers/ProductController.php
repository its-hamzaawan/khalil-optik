<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->validate([
            'category' => ['nullable', 'string', 'max:40'],
            'search' => ['nullable', 'string', 'max:80'],
            'sort' => ['nullable', 'in:newest,price_asc,price_desc,name'],
        ]);

        $products = Product::active()
            ->filter($filters)
            ->paginate(9)
            ->withQueryString();

        $counts = Product::active()
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        return Inertia::render('Products/Index', [
            'products' => $products,
            'filters' => $filters + ['sort' => 'newest'],
            'categories' => config('khalil.product_categories'),
            'counts' => $counts,
        ]);
    }

    public function show(Product $product): Response
    {
        abort_unless($product->is_active, 404);

        return Inertia::render('Products/Show', [
            'product' => $product,
            'related' => Product::active()
                ->where('category', $product->category)
                ->whereKeyNot($product->id)
                ->inRandomOrder()
                ->take(3)
                ->get(),
        ]);
    }
}
