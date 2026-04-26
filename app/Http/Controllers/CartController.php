<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the cart page.
     */
    public function index()
    {
        return view('cart');
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'Ce produit est en rupture de stock.');
        }

        $cart = session()->get('cart', []);
        $qty  = max(1, (int) $request->input('quantity', 1));

        if (isset($cart[$id])) {
            $newQty = $cart[$id]['quantity'] + $qty;
            $cart[$id]['quantity'] = min($newQty, $product->stock);
        } else {
            $cart[$id] = [
                'name'     => $product->name,
                'quantity' => min($qty, $product->stock),
                'price'    => $product->price,
                'image'    => $product->image,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', '«&nbsp;' . $product->name . '&nbsp;» ajouté au panier !');
    }

    /**
     * Update the quantity of a cart item.
     */
    public function update(Request $request)
    {
        $id     = $request->input('id');
        $action = $request->input('action'); // 'increment' | 'decrement'
        $cart   = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return redirect()->back();
        }

        $product = Product::find($id);
        $maxQty  = $product ? $product->stock : 99;

        if ($action === 'increment') {
            $cart[$id]['quantity'] = min($cart[$id]['quantity'] + 1, $maxQty);
        } elseif ($action === 'decrement') {
            if ($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
            } else {
                unset($cart[$id]);
            }
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Panier mis à jour.');
    }

    /**
     * Remove a product from the cart.
     */
    public function remove(Request $request)
    {
        $id   = $request->input('id');
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Article retiré du panier.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return back()->with('error', 'Code promo invalide.');
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return back()->with('error', 'Ce code promo a expiré.');
        }

        session()->put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->discount_type,
            'value' => $coupon->discount_value
        ]);

        return back()->with('success', 'Code promo appliqué !');
    }

    /**
     * Show the checkout form.
     */
    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        return view('checkout');
    }

    /**
     * Process the checkout — create the order and redirect to payment.
     */
    public function processCheckout(Request $request)
    {
        $request->validate([
            'nom_client' => 'required|string|max:120',
            'telephone'  => 'required|string|max:30',
            'ville'      => 'required|string|max:60',
            'adresse'    => 'required|string|max:255',
            'notes'      => 'nullable|string|max:500',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $total = collect($cart)->sum(fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 1));

        if (session()->has('coupon')) {
            $coupon = session('coupon');
            if ($coupon['type'] == 'fixed') {
                $total = max(0, $total - $coupon['value']);
            } else {
                $total = $total - ($total * ($coupon['value'] / 100));
            }
        }

        // Create order
        $order = Order::create([
            'nom_client'     => $request->nom_client,
            'telephone'      => $request->telephone,
            'ville'          => $request->ville,
            'adresse'        => $request->adresse,
            'notes'          => $request->notes,
            'total'          => $total,
            'statut'         => 'pending',
            'payment_status' => 'unpaid',
            'user_id'        => auth()->id(),
        ]);

        // Create order items
        foreach ($cart as $productId => $details) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $productId,
                'name'       => $details['name'],
                'quantity'   => $details['quantity'] ?? 1,
                'price'      => $details['price'],
            ]);
        }

        // Clear the cart
        session()->forget('cart');

        return redirect()->route('payment.page', $order->id);
    }
}
