<?php
namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function index()
    {
        return view('order-tracking');
    }

    public function track(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'telephone' => 'required|string'
        ]);

        $order = Order::with('items')->where('id', $request->order_id)
                      ->where('telephone', $request->telephone)
                      ->first();

        if (!$order) {
            return back()->with('error', 'Commande introuvable avec ces informations.');
        }

        return view('order-tracking', compact('order'));
    }
}
