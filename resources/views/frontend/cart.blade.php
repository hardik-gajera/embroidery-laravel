@extends('frontend.layout')
@section('title', 'Cart')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center gap-4 mb-8 animate-slide-up">
        <div class="w-12 h-12 bg-gradient-to-br from-primary-100 to-accent-100 rounded-xl flex items-center justify-center">
            <i class="fas fa-shopping-cart text-primary-600"></i>
        </div>
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-800">Your Cart</h1>
            <p class="text-sm text-gray-400">{{ $cartItems->count() }} {{ $cartItems->count() === 1 ? 'item' : 'items' }}</p>
        </div>
    </div>

    @if($cartItems->count() > 0)
    <div class="bg-white rounded-2xl overflow-hidden animate-scale-in" style="box-shadow: 0 8px 30px -4px rgba(0,0,0,0.1), 0 4px 12px -2px rgba(0,0,0,0.05);">
        <div class="divide-y divide-gray-50">
            @foreach($cartItems as $item)
            <div class="flex items-center gap-4 p-5 hover:bg-gray-50/50 transition-colors group">
                @if($item->design->design_img)
                    <img src="{{ asset('storage/' . $item->design->design_img) }}" class="w-16 h-16 rounded-xl object-cover border border-gray-100 group-hover:scale-105 transition-transform">
                @else
                    <div class="w-16 h-16 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl flex items-center justify-center"><i class="fas fa-swatchbook text-gray-300"></i></div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $item->design->name }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $item->design->design_format }} • {{ number_format($item->design->stitches) }} stitches</p>
                </div>
                <p class="text-sm font-bold bg-gradient-to-r from-primary-600 to-accent-600 bg-clip-text text-transparent">₹{{ number_format($item->design->design_price, 2) }}</p>
                <form action="{{ route('frontend.cart.remove', $item->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-100 transition-all hover:scale-110">
                        <i class="fas fa-trash-alt text-xs"></i>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
        <div class="border-t border-gray-100 p-6 bg-gradient-to-r from-gray-50 to-white flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total ({{ $cartItems->count() }} items)</p>
                <p class="text-2xl font-bold bg-gradient-to-r from-primary-600 to-accent-600 bg-clip-text text-transparent">₹{{ number_format($total, 2) }}</p>
            </div>
            <form action="{{ route('frontend.buy') }}" method="POST">
                @csrf
                <input type="hidden" name="design_id" value="{{ $cartItems->first()->design_id }}">
                <button class="btn-glow px-8 py-3.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-xl font-semibold hover:from-primary-700 hover:to-primary-800 transition-all shadow-lg shadow-primary-200 hover:scale-105">
                    <i class="fas fa-bolt mr-2"></i>Checkout
                </button>
            </form>
        </div>
    </div>
    @else
    <div class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm animate-scale-in">
        <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <i class="fas fa-shopping-cart text-gray-300 text-3xl"></i>
        </div>
        <p class="text-gray-500 font-medium text-lg">Your cart is empty</p>
        <p class="text-sm text-gray-400 mt-1">Add some beautiful designs to get started</p>
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-3 bg-primary-50 text-primary-600 rounded-xl font-semibold hover:bg-primary-100 transition-all hover:scale-105">
            <i class="fas fa-arrow-left text-sm"></i>Continue Shopping
        </a>
    </div>
    @endif
</div>
@endsection
