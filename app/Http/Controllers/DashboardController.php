<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_customers' => Customer::count(),
            'total_downloaded' => Customer::sum('downloaded_design'),
            'total_designs' => Customer::sum('total_design'),
        ];
        $recentCustomers = Customer::latest()->take(5)->get();

        return view('dashboard', compact('stats', 'recentCustomers'));
    }

    public function packageHistory()
    {
        $query = Order::with(['customer', 'package'])->whereNotNull('package_id');

        if ($search = request('search')) {
            $query->whereHas('customer', fn($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
        }

        $orders = $query->latest()->paginate(15)->withQueryString();
        return view('package-history', compact('orders'));
    }
}
