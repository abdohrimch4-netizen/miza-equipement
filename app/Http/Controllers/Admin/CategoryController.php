<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('name')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:500',
            'icon'        => 'nullable|string|max:60',
        ]);
        $data['slug'] = Str::slug($data['name']);
        Category::create($data);
        return redirect()->route('admin.categories')->with('success', 'Catégorie créée avec succès !');
    }

    public function edit($id)
    {
        $category = Category::withCount('products')->findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $data = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name,' . $id,
            'description' => 'nullable|string|max:500',
            'icon'        => 'nullable|string|max:60',
        ]);
        $data['slug'] = Str::slug($data['name']);
        $category->update($data);
        return redirect()->route('admin.categories')->with('success', 'Catégorie mise à jour !');
    }

    public function destroy($id)
    {
        $category = Category::withCount('products')->findOrFail($id);
        if ($category->products_count > 0) {
            return redirect()->back()->with('error', 'Impossible : cette catégorie contient ' . $category->products_count . ' produit(s).');
        }
        $category->delete();
        return redirect()->route('admin.categories')->with('success', 'Catégorie supprimée !');
    }
}