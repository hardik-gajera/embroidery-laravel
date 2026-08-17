<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Design;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        $perPage = $request->input('per_page', 10);

        $orders = Order::with('design')
                      ->where('customer_id', $request->user()->id)
                      ->whereNotNull('design_id')
                      ->where('status', 'paid')
                      ->latest()
                      ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $orders->items(),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ]
        ]);
    }

    public function createRazorpayOrder(Request $request)
    {
        $request->validate([
            'amount'   => 'required|numeric|min:1',
            'receipt'  => 'nullable|string|max:40',
        ]);

        $isTestUser = $request->user() && $request->user()->mobile_no === '+919999999999';
        $key    = $isTestUser ? config('services.razorpay_test.key')    : config('services.razorpay.key');
        $secret = $isTestUser ? config('services.razorpay_test.secret') : config('services.razorpay.secret');

        $response = Http::withBasicAuth($key, $secret)
            ->post('https://api.razorpay.com/v1/orders', [
                'amount'   => (int) round($request->amount * 100), // paise
                'currency' => 'INR',
                'receipt'  => $request->receipt ?? 'rcpt_' . uniqid(),
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create Razorpay order',
                'error'   => $response->json(),
            ], 502);
        }

        $order = $response->json();

        return response()->json([
            'success'          => true,
            'razorpay_order_id' => $order['id'],
            'amount'           => $order['amount'],
            'currency'         => $order['currency'],
            'key'              => $key,
        ]);
    }

    public function createRazorpayTestOrder(Request $request)
    {
        $request->validate([
            'amount'  => 'required|numeric|min:1',
            'receipt' => 'nullable|string|max:40',
        ]);

        $key    = config('services.razorpay_test.key');
        $secret = config('services.razorpay_test.secret');

        $response = Http::withBasicAuth($key, $secret)
            ->post('https://api.razorpay.com/v1/orders', [
                'amount'   => (int) round($request->amount * 100),
                'currency' => 'INR',
                'receipt'  => $request->receipt ?? 'rcpt_' . uniqid(),
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create Razorpay test order',
                'error'   => $response->json(),
            ], 502);
        }

        $order = $response->json();

        return response()->json([
            'success'           => true,
            'razorpay_order_id' => $order['id'],
            'amount'            => $order['amount'],
            'currency'          => $order['currency'],
            'key'               => $key,
        ]);
    }

    public function buyNow(Request $request)
    {
        $request->validate([
            'design_id' => 'required|exists:designs,id'
        ]);

        $design = Design::findOrFail($request->design_id);
        $customer = $request->user();

        $existingOrder = Order::where('customer_id', $customer->id)
            ->where('design_id', $design->id)
            ->where('status', 'paid')
            ->first();

        if ($existingOrder) {
            return response()->json([
                'success' => true,
                'data' => [
                    'already_purchased' => true,
                    'order' => $existingOrder,
                    'design' => $design,
                ]
            ]);
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

        $existingOrder = Order::where('customer_id', $customer->id)
            ->where('design_id', $design->id)
            ->where('status', 'paid')
            ->first();

        if ($existingOrder) {
            return response()->json([
                'success' => true,
                'data' => [
                    'already_purchased' => true,
                    'order' => $existingOrder,
                    'design' => $design,
                ]
            ]);
        }

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

    /**
     * Checkout multiple designs from cart
     */
    public function cartCheckout(Request $request)
    {
        $request->validate([
            'design_ids' => 'required|array|min:1',
            'design_ids.*' => 'exists:designs,id'
        ]);

        $customer = $request->user();
        $designIds = $request->design_ids;
        $designs = Design::whereIn('id', $designIds)->get();

        // Separate already purchased designs
        $purchasedOrders = Order::with('design')
            ->where('customer_id', $customer->id)
            ->whereIn('design_id', $designIds)
            ->where('status', 'paid')
            ->get();

        $purchasedIds = $purchasedOrders->pluck('design_id')->toArray();
        $alreadyPurchasedDesigns = $purchasedOrders;

        $designs = $designs->filter(fn($d) => !in_array($d->id, $purchasedIds))->values();

        if ($designs->isEmpty()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'already_purchased' => true,
                    'already_purchased_orders' => $alreadyPurchasedDesigns,
                    'new_designs' => [],
                ]
            ]);
        }

        // Check active package
        if ($customer->hasActivePackage()) {
            $remaining = $customer->total_design - $customer->downloaded_design;
            if ($remaining >= $designs->count()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'can_claim_all_free' => true,
                        'designs' => $designs,
                        'already_purchased_orders' => $alreadyPurchasedDesigns,
                        'remaining_downloads' => $remaining
                    ]
                ]);
            } elseif ($remaining > 0) {
                $freeDesigns = $designs->take($remaining)->values();
                $paidDesigns = $designs->slice($remaining)->values();
                $totalAmount = $paidDesigns->sum('design_price');

                return response()->json([
                    'success' => true,
                    'data' => [
                        'can_claim_all_free' => false,
                        'free_designs' => $freeDesigns,
                        'paid_designs' => $paidDesigns,
                        'total_amount' => $totalAmount,
                        'already_purchased_orders' => $alreadyPurchasedDesigns,
                        'remaining_downloads' => $remaining
                    ]
                ]);
            }
        }

        // No package — all need payment
        $totalAmount = $designs->sum('design_price');

        return response()->json([
            'success' => true,
            'data' => [
                'can_claim_all_free' => false,
                'free_designs' => [],
                'paid_designs' => $designs,
                'total_amount' => $totalAmount,
                'already_purchased_orders' => $alreadyPurchasedDesigns,
                'remaining_downloads' => 0
            ]
        ]);
    }

    /**
     * Claim multiple designs for free via package
     */
    public function claimDesignsBulk(Request $request)
    {
        $request->validate([
            'design_ids' => 'required|array|min:1',
            'design_ids.*' => 'exists:designs,id'
        ]);

        $customer = $request->user();
        $designIds = $request->design_ids;
        $designs = Design::whereIn('id', $designIds)->get();

        $remaining = $customer->total_design - $customer->downloaded_design;

        if (!$customer->hasActivePackage() || $remaining <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'No active package or package limit exceeded'
            ], 400);
        }

        $claimable = $designs->take($remaining);
        $claimed = [];

        foreach ($claimable as $design) {
            $alreadyClaimed = Order::where('customer_id', $customer->id)
                ->where('design_id', $design->id)
                ->where('status', 'paid')
                ->exists();

            if (!$alreadyClaimed) {
                Order::create([
                    'customer_id' => $customer->id,
                    'design_id' => $design->id,
                    'amount' => 0,
                    'razorpay_payment_id' => 'package_claim',
                    'status' => 'paid',
                ]);
                $customer->increment('downloaded_design');
                $claimed[] = $design;
            }
        }

        Cart::where('customer_id', $customer->id)->whereIn('design_id', $designIds)->delete();

        return response()->json([
            'success' => true,
            'message' => count($claimed) . ' design(s) claimed successfully',
            'data' => [
                'claimed_designs' => $claimed,
                'remaining_downloads' => $customer->total_design - $customer->downloaded_design
            ]
        ]);
    }

    /**
     * Payment success for multiple designs
     */
    public function bulkPaymentSuccess(Request $request)
    {
        $request->validate([
            'design_ids' => 'required|array|min:1',
            'design_ids.*' => 'exists:designs,id',
            'amount' => 'required|numeric',
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'nullable|string',
            'free_design_ids' => 'nullable|array',
            'free_design_ids.*' => 'exists:designs,id'
        ]);

        $customer = $request->user();
        $designIds = $request->design_ids;
        $freeDesignIds = $request->free_design_ids ?? [];

        // Create orders for paid designs
        $perDesignAmount = count($designIds) > 0 ? $request->amount / count($designIds) : 0;

        foreach ($designIds as $designId) {
            Order::create([
                'customer_id' => $customer->id,
                'design_id' => $designId,
                'amount' => $perDesignAmount,
                'razorpay_order_id' => $request->razorpay_order_id ?? '',
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'status' => 'paid',
            ]);
        }

        // Claim free designs via package
        foreach ($freeDesignIds as $designId) {
            Order::create([
                'customer_id' => $customer->id,
                'design_id' => $designId,
                'amount' => 0,
                'razorpay_payment_id' => 'package_claim',
                'status' => 'paid',
            ]);
            $customer->increment('downloaded_design');
        }

        // Clear cart
        $allIds = array_merge($designIds, $freeDesignIds);
        Cart::where('customer_id', $customer->id)->whereIn('design_id', $allIds)->delete();

        $designs = Design::whereIn('id', $allIds)->get();

        return response()->json([
            'success' => true,
            'message' => 'Payment successful. ' . count($allIds) . ' design(s) purchased.',
            'data' => [
                'designs' => $designs,
                'remaining_downloads' => $customer->total_design - $customer->downloaded_design
            ]
        ]);
    }
}