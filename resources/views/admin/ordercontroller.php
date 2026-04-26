<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        // هادي هي اللي غتخلي بوطونة Voir تخدم
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        
        // جرب هاد السمية حيت قلتي كاينة order_details
        return view('admin.orders.order_details', compact('order'));
    }
}