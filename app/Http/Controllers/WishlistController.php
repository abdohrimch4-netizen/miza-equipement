<?php
namespace App\Http\Controllers;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with('product')->where('user_id', auth()->id())->get();
        return view('wishlist', compact('wishlists'));
    }

    public function toggle(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $exists = Wishlist::where('user_id', auth()->id())->where('product_id', $productId)->first();

        if ($exists) {
            $exists->delete();
            return back()->with('success', 'Produit retiré de vos favoris.');
        } else {
            Wishlist::create([
                'user_id' => auth()->id(),
                'product_id' => $productId
            ]);
            return back()->with('success', 'Produit ajouté à vos favoris.');
        }
    }
}
