<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cartItems = Cart::with('design')->where('customer_id', $request->user()->id)->get();
        $total = $cartItems->sum(fn($item) => $item->design->design_price);
        
        return response()->json([
            'success' => true,
            'data' => [
                'cart_items' => $cartItems,
                'total_amount' => $total,
                'item_count' => $cartItems->count()
            ]
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'design_id' => 'required|exists:designs,id'
        ]);

        $cartItem = Cart::firstOrCreate([
            'customer_id' => $request->user()->id,
            'design_id' => $request->design_id,
        ]);

        $cartCount = Cart::where('customer_id', $request->user()->id)->count();

        return response()->json([
            'success' => true,
            'message' => 'Added to cart successfully',
            'data' => [
                'cart_item' => $cartItem->load('design'),
                'cart_count' => $cartCount
            ]
        ]);
    }

    public function remove(Request $request, $id)
    {
        $deleted = Cart::where('id', $id)
                      ->where('customer_id', $request->user()->id)
                      ->delete();

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart'
            ], 404);
        }

        $cartCount = Cart::where('customer_id', $request->user()->id)->count();

        return response()->json([
            'success' => true,
            'message' => 'Removed from cart successfully',
            'data' => ['cart_count' => $cartCount]
        ]);
    }

    public function clear(Request $request)
    {
        Cart::where('customer_id', $request->user()->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }
}