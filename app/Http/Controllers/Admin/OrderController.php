<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('nom_client', 'LIKE', "%$s%")->orWhere('telephone', 'LIKE', "%$s%")->orWhere('id', $s));
        }

        $orders  = $query->paginate(20)->withQueryString();
        $stats   = [
            'total'      => Order::count(),
            'pending'    => Order::where('statut', 'pending')->count(),
            'processing' => Order::where('statut', 'processing')->count(),
            'shipped'    => Order::where('statut', 'shipped')->count(),
            'delivered'  => Order::where('statut', 'delivered')->count(),
            'revenue'    => Order::where('payment_status', 'paid')->sum('total'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['statut' => 'required|in:pending,processing,shipped,delivered,cancelled']);
        $order = Order::findOrFail($id);
        $order->update(['statut' => $request->statut]);
        return redirect()->back()->with('success', 'Statut mis à jour : ' . $order->status_label);
    }
}