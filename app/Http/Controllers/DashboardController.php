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

    public function editPackagePurchase($id)
    {
        $purchase = \App\Models\CustomerPackagePurchase::with(['customer', 'package'])->findOrFail($id);
        return view('package-purchase-edit', compact('purchase'));
    }

    public function updatePackagePurchase(Request $request, $id)
    {
        $purchase = \App\Models\CustomerPackagePurchase::findOrFail($id);

        $request->validate([
            'total' => 'required|integer|min:0',
            'downloaded' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $purchase->update($request->only('total', 'downloaded', 'start_date', 'end_date'));

        return redirect()->back()->with('success', 'Package purchase updated successfully.');
    }

    public function editOrderPackage($id)
    {
        $order = Order::with(['customer', 'package'])->findOrFail($id);
        return view('order-package-edit', compact('order'));
    }

    public function updateOrderPackage(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid,failed',
        ]);

        $order->update($request->only('amount', 'status'));

        return redirect()->back()->with('success', 'Package order updated successfully.');
    }
}
