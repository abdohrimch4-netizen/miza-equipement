<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'LIKE', "%$s%")->orWhere('reference', 'LIKE', "%$s%"));
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('stock')) {
            match($request->stock) {
                'out'  => $query->where('stock', 0),
                'low'  => $query->whereBetween('stock', [1, 5]),
                'ok'   => $query->where('stock', '>', 5),
                default => null,
            };
        }

        $products      = $query->latest()->paginate(15)->withQueryString();
        $categories    = Category::orderBy('name')->get();
        $totalProducts = Product::count();
        $totalValue    = Product::selectRaw('SUM(price * stock) as v')->value('v') ?? 0;
        $outOfStock    = Product::where('stock', 0)->count();
        $featured      = Product::where('is_featured', true)->count();

        try {
            return view('products.index', compact('products', 'categories', 'totalProducts', 'totalValue', 'outOfStock', 'featured'))->render();
        } catch (\Throwable $e) {
            return response($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        }
    }

    public function create()
    {
        try {
            $categories = Category::orderBy('name')->get();
            return view('products.create', compact('categories'))->render();
        } catch (\Throwable $e) {
            return response($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:200',
            'reference'   => 'nullable|string|max:60',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'is_featured' => 'boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'gallery.*'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $gallery = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('products/gallery', 'public');
            }
        }
        $data['gallery']     = $gallery ?: null;
        $data['is_featured'] = $request->boolean('is_featured');

        Product::create($data);
        return redirect()->route('products.index')->with('success', 'Produit ajouté avec succès !');
    }

    public function edit($id)
    {
        $product    = Product::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'name'        => 'required|string|max:200',
            'reference'   => 'nullable|string|max:60',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'is_featured' => 'boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'gallery.*'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) Storage::disk('public')->delete($product->image);
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        if ($request->hasFile('gallery')) {
            $gallery = $product->gallery ?? [];
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('products/gallery', 'public');
            }
            $data['gallery'] = $gallery;
        }

        // Remove individual gallery image
        if ($request->filled('remove_gallery')) {
            $gallery = $product->gallery ?? [];
            $remove  = $request->remove_gallery;
            Storage::disk('public')->delete($remove);
            $data['gallery'] = array_values(array_filter($gallery, fn($g) => $g !== $remove));
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $product->update($data);
        return redirect()->route('products.index')->with('success', 'Produit modifié avec succès !');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) Storage::disk('public')->delete($product->image);
        foreach ($product->gallery ?? [] as $img) Storage::disk('public')->delete($img);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produit supprimé !');
    }

    public function show($id) { return redirect()->route('produit.details', $id); }
}