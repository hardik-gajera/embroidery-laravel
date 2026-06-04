<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DesignPackage;
use App\Models\Order;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = DesignPackage::where('state', 'confirm')->get();
        
        return response()->json([
            'success' => true,
            'data' => $packages
        ]);
    }

    public function show($id)
    {
        $package = DesignPackage::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $package
        ]);
    }

    public function myPackages(Request $request)
    {
        $customer = $request->user()->load('package');
        $packageOrders = Order::with('package')
            ->where('customer_id', $customer->id)
            ->whereNotNull('package_id')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'current_package' => $customer->package,
                'has_active_package' => $customer->hasActivePackage(),
                'package_start_date' => $customer->package_start_date,
                'package_end_date' => $customer->package_end_date,
                'total_design' => $customer->total_design,
                'downloaded_design' => $customer->downloaded_design,
                'remaining_downloads' => $customer->hasActivePackage() ? 
                    ($customer->total_design - $customer->downloaded_design) : 0,
                'package_history' => $packageOrders
            ]
        ]);
    }

    public function buy(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:design_packages,id'
        ]);

        $package = DesignPackage::findOrFail($request->package_id);

        return response()->json([
            'success' => true,
            'data' => [
                'package' => $package,
                'amount' => $package->price
            ]
        ]);
    }

    public function paymentSuccess(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:design_packages,id',
            'amount' => 'required|numeric',
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'nullable|string'
        ]);

        $package = DesignPackage::findOrFail($request->package_id);
        $customer = $request->user();

        $startDate = Carbon::today();
        $endDate = $startDate->copy()->addMonths($package->time_period);

        $customer->update([
            'package_id' => $package->id,
            'package_start_date' => $startDate,
            'package_end_date' => $endDate,
            'total_design' => $package->number_of_design,
            'downloaded_design' => 0,
        ]);

        Order::create([
            'customer_id' => $customer->id,
            'package_id' => $package->id,
            'amount' => $request->amount,
            'razorpay_order_id' => $request->razorpay_order_id ?? '',
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'status' => 'paid',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Package purchased successfully',
            'data' => [
                'package' => $package,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_downloads' => $package->number_of_design
            ]
        ]);
    }
}