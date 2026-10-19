<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->only('search', 'category');

        return Inertia::render('Admin/Products/Index', [
            'products' => Product::query()
                ->when($filters['search'] ?? null, fn ($q, $s) => $q->where(fn ($q) => $q
                    ->where('name', 'like', "%{$s}%")->orWhere('brand', 'like', "%{$s}%")))
                ->when($filters['category'] ?? null, fn ($q, $c) => $q->where('category', $c))
                ->latest()
                ->paginate(12)
                ->withQueryString(),
            'filters' => $filters,
            'categories' => config('khalil.product_categories'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Products/Form', [
            'product' => null,
            'categories' => config('khalil.product_categories'),
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $data = $this->payload($request);
        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Product added.');
    }

    public function edit(Product $product): Response
    {
        return Inertia::render('Admin/Products/Form', [
            'product' => $product,
            'categories' => config('khalil.product_categories'),
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($this->payload($request, $product));

        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return back()->with('success', 'Product deleted.');
    }

    /** Build the data to save, handling image upload / removal. */
    private function payload(ProductRequest $request, ?Product $product = null): array
    {
        $data = collect($request->validated())->except(['image', 'remove_image'])->all();

        $removeOld = $request->hasFile('image') || $request->boolean('remove_image');

        if ($product?->image && $removeOld) {
            Storage::disk('public')->delete($product->image);
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        return $data;
    }
}
