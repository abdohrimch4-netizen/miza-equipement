<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    /**
     * Homepage — show products with optional category/search filter.
     */
    public function home(Request $request)
    {
        $query = Product::with('category')->where('stock', '>', 0);

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by search term
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'LIKE', "%{$term}%")
                  ->orWhere('description', 'LIKE', "%{$term}%")
                  ->orWhere('reference', 'LIKE', "%{$term}%");
            });
        }

        // Featured products (for flash deals section)
        $flashDeals = Product::with('category')
            ->where('stock', '>', 0)
            ->where('is_featured', true)
            ->latest()
            ->take(5)
            ->get();

        // If no featured products, use latest
        if ($flashDeals->isEmpty()) {
            $flashDeals = Product::with('category')
                ->where('stock', '>', 0)
                ->latest()
                ->take(5)
                ->get();
        }

        $products   = $query->latest()->paginate(12);
        $categories = Category::withCount('products')->orderBy('name')->get();

        return view('welcome', compact('products', 'flashDeals', 'categories'));
    }

    /**
     * Product detail page.
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        // Similar products (same category, exclude current)
        $similarProducts = collect();
        if ($product->category_id) {
            $similarProducts = Product::with('category')
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->where('stock', '>', 0)
                ->latest()
                ->take(4)
                ->get();
        }

        return view('details', compact('product', 'similarProducts'));
    }

    /**
     * Search results page.
     */
    public function search(Request $request)
    {
        $term       = $request->input('q', $request->input('search', ''));
        $categoryId = $request->input('category');
        $minPrice   = $request->input('min_price');
        $maxPrice   = $request->input('max_price');

        $query = Product::with('category')->where('stock', '>', 0);

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'LIKE', "%{$term}%")
                  ->orWhere('description', 'LIKE', "%{$term}%")
                  ->orWhere('reference', 'LIKE', "%{$term}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }

        $products   = $query->latest()->paginate(16)->withQueryString();
        $categories = Category::withCount('products')->orderBy('name')->get();

        return view('search', compact('products', 'categories', 'term', 'categoryId', 'minPrice', 'maxPrice'));
    }
}
