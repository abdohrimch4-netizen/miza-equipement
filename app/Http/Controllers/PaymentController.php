<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;

class PaymentController extends Controller
{
    public function show($id)
    {
        $order = Order::findOrFail($id);

        if ($order->payment_status === 'paid') {
            return redirect()->route('order.confirmation', $order->id);
        }

        return view('payment', compact('order'));
    }

    public function process(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $order->update([
            'payment_status' => 'paid',
            'payment_method' => 'carte_bancaire',
            'statut'         => 'processing',
        ]);

        if (auth()->check() && auth()->user()->email) {
            try {
                Mail::to(auth()->user()->email)->send(new OrderConfirmation($order));
            } catch (\Exception $e) {
                // Silently bypass if email setup fails in local test
            }
        }

        return redirect()->route('order.confirmation', $order->id)
                         ->with('success', 'Paiement effectué avec succès !');
    }

    public function confirmation($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return view('order-confirmation', compact('order'));
    }
}
