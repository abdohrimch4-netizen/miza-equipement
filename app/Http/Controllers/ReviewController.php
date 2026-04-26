<?php
namespace App\Http\Controllers;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $product = Product::findOrFail($productId);
        
        Review::updateOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $productId],
            ['rating' => $request->rating, 'comment' => $request->comment]
        );

        return back()->with('success', 'Votre avis a été enregistré.');
    }
}
