<?php
namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->check() && auth()->user()->is_admin) {
            $totalOrders   = Order::count();
            $revenue       = Order::where('payment_status', 'paid')->sum('total');
            $totalProducts = Product::count();
            $outOfStock    = Product::where('stock', 0)->count();
            $pendingOrders = Order::where('statut', 'pending')->count();
            $recentOrders  = Order::with('items')->latest()->take(8)->get();
            return view('admin.dashboard', compact('totalOrders','revenue','totalProducts','outOfStock','pendingOrders','recentOrders'));
        }
        return redirect()->route('profile.edit');
    }
}
