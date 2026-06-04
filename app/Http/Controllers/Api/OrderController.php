<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Design;
use App\Models\Cart;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('design', 'package')
                      ->where('customer_id', $request->user()->id)
                      ->latest()
                      ->paginate(10);
        
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function myDesigns(Request $request)
    {
        $orders = Order::with('design')
                      ->where('customer_id', $request->user()->id)
                      ->whereNotNull('design_id')
                      ->where('status', 'paid')
                      ->latest()
                      ->get();
        
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function buyNow(Request $request)
    {
        $request->validate([
            'design_id' => 'required|exists:designs,id'
        ]);

        $design = Design::findOrFail($request->design_id);
        $customer = $request->user();

        $alreadyPurchased = Order::where('customer_id', $customer->id)
            ->where('design_id', $design->id)
            ->where('status', 'paid')
            ->exists();

        if ($alreadyPurchased) {
            return response()->json([
                'success' => false,
                'message' => 'You already own this design'
            ], 400);
        }

        if ($customer->hasActivePackage()) {
            if ($customer->downloaded_design < $customer->total_design) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'can_claim_free' => true,
                        'design' => $design,
                        'remaining_downloads' => $customer->total_design - $customer->downloaded_design
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'can_claim_free' => false,
                        'package_exceeded' => true,
                        'design' => $design,
                        'amount' => $design->design_price
                    ]
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'can_claim_free' => false,
                'package_exceeded' => false,
                'design' => $design,
                'amount' => $design->design_price
            ]
        ]);
    }

    public function claimDesign(Request $request)
    {
        $request->validate([
            'design_id' => 'required|exists:designs,id'
        ]);

        $customer = $request->user();
        $design = Design::findOrFail($request->design_id);

        if (!$customer->hasActivePackage() || $customer->downloaded_design >= $customer->total_design) {
            return response()->json([
                'success' => false,
                'message' => 'Package limit exceeded or no active package'
            ], 400);
        }

        Order::create([
            'customer_id' => $customer->id,
            'design_id' => $design->id,
            'amount' => 0,
            'razorpay_payment_id' => 'package_claim',
            'status' => 'paid',
        ]);

        $customer->increment('downloaded_design');
        Cart::where('customer_id', $customer->id)->where('design_id', $design->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Design claimed successfully',
            'data' => [
                'design' => $design,
                'remaining_downloads' => $customer->total_design - $customer->downloaded_design
            ]
        ]);
    }

    public function paymentSuccess(Request $request)
    {
        $request->validate([
            'design_id' => 'required|exists:designs,id',
            'amount' => 'required|numeric',
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'nullable|string'
        ]);

        Order::create([
            'customer_id' => $request->user()->id,
            'design_id' => $request->design_id,
            'amount' => $request->amount,
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'status' => 'paid',
        ]);

        Cart::where('customer_id', $request->user()->id)
            ->where('design_id', $request->design_id)
            ->delete();

        $design = Design::findOrFail($request->design_id);

        return response()->json([
            'success' => true,
            'message' => 'Payment successful',
            'data' => ['design' => $design]
        ]);
    }
}