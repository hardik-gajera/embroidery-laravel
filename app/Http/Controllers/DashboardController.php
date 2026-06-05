<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month ? Carbon::parse($request->month . '-01') : Carbon::now();

        $stats = [
            'total_customers' => Customer::count(),
            'total_downloaded' => Customer::sum('downloaded_design'),
            'total_designs' => Customer::sum('total_design'),
        ];

        $monthlyStats = [
            'razorpay_revenue' => Order::where('status', 'paid')
                ->where('razorpay_payment_id', '!=', 'package_claim')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount'),
            'package_downloads' => Order::where('status', 'paid')
                ->whereNotNull('design_id')
                ->where('razorpay_payment_id', 'package_claim')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count(),
            'package_revenue' => Order::where('status', 'paid')
                ->whereNotNull('package_id')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount'),
        ];

        $recentCustomers = Customer::latest()->take(5)->get();
        $selectedMonth = $month->format('Y-m');

        return view('dashboard', compact('stats', 'monthlyStats', 'recentCustomers', 'selectedMonth'));
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
