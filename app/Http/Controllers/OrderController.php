<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'design', 'package'])->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->type === 'design') {
            $query->whereNotNull('design_id');
        } elseif ($request->type === 'package') {
            $query->whereNotNull('package_id');
        }
        if ($request->search) {
            $search = $request->search;
            $query->whereHas('customer', fn($q) => $q->where('name', 'like', "%$search%")->orWhere('email', 'like', "%$search%"));
        }

        $orders = $query->paginate(20);
        $stats = [
            'total' => Order::count(),
            'revenue' => Order::where('status', 'paid')->sum('amount'),
            'designs_sold' => Order::whereNotNull('design_id')->where('status', 'paid')->count(),
            'packages_sold' => Order::whereNotNull('package_id')->where('status', 'paid')->count(),
        ];

        return view('orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'design', 'package']);
        return view('orders.show', compact('order'));
    }
}
