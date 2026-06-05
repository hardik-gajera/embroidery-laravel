@extends('layouts.app')
@section('title', 'Order #' . $order->id)
@section('subtitle', 'Order details')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Order Info -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-heading font-semibold text-gray-800 uppercase tracking-wider">Order Information</h3>
                <span class="text-xs font-medium px-3 py-1 rounded-full {{ $order->status === 'paid' ? 'text-green-600 bg-green-50' : 'text-orange-600 bg-orange-50' }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[11px] text-gray-400 uppercase tracking-wider font-semibold">Order ID</p>
                    <p class="text-sm font-medium text-gray-800 mt-0.5">#{{ $order->id }}</p>
                </div>
                <div>
                    <p class="text-[11px] text-gray-400 uppercase tracking-wider font-semibold">Date</p>
                    <p class="text-sm text-gray-800 mt-0.5">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                </div>
                <div>
                    <p class="text-[11px] text-gray-400 uppercase tracking-wider font-semibold">Amount</p>
                    <p class="text-sm font-semibold {{ $order->amount > 0 ? 'text-green-600' : 'text-gray-500' }} mt-0.5">
                        {{ $order->amount > 0 ? '₹' . number_format($order->amount, 2) : 'Free (Package Claim)' }}
                    </p>
                </div>
                <div>
                    <p class="text-[11px] text-gray-400 uppercase tracking-wider font-semibold">Payment Via</p>
                    @if($order->razorpay_payment_id === 'package_claim')
                        <p class="text-sm font-medium text-amber-600 mt-0.5"><i class="fas fa-box-open mr-1"></i>Package Claim</p>
                    @else
                        <p class="text-sm font-medium text-blue-600 mt-0.5"><i class="fas fa-credit-card mr-1"></i>Razorpay</p>
                    @endif
                </div>
                @if($order->razorpay_order_id)
                <div class="col-span-2">
                    <p class="text-[11px] text-gray-400 uppercase tracking-wider font-semibold">Razorpay Order ID</p>
                    <p class="text-sm font-mono text-gray-600 mt-0.5">{{ $order->razorpay_order_id }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Item Details -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-heading font-semibold text-gray-800 uppercase tracking-wider mb-4">
                {{ $order->design_id ? 'Design' : 'Package' }} Details
            </h3>
            @if($order->design)
                <div class="flex items-center gap-4">
                    @if($order->design->design_img)
                        <img src="{{ asset('storage/' . $order->design->design_img) }}" class="w-16 h-16 rounded-lg object-cover border border-gray-200">
                    @else
                        <div class="w-16 h-16 bg-indigo-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-swatchbook text-indigo-400"></i>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $order->design->name }}</p>
                        <p class="text-xs text-gray-400">Code: {{ $order->design->design_code ?? '—' }}</p>
                        <p class="text-xs text-gray-400">Price: ₹{{ number_format($order->design->design_price, 2) }}</p>
                    </div>
                </div>
            @elseif($order->package)
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-purple-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-box text-purple-400 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $order->package->name }}</p>
                        <p class="text-xs text-gray-400">{{ $order->package->number_of_design }} designs / {{ $order->package->time_period }} months</p>
                        <p class="text-xs text-gray-400">Price: ₹{{ number_format($order->package->price, 2) }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Customer Info -->
    <div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-heading font-semibold text-gray-800 uppercase tracking-wider mb-4">Customer</h3>
            @if($order->customer)
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-xs font-bold">
                        {{ strtoupper(substr($order->customer->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $order->customer->name }}</p>
                        <p class="text-xs text-gray-400">{{ $order->customer->email }}</p>
                    </div>
                </div>
                <div class="space-y-2 text-sm">
                    <p class="text-gray-600"><i class="fas fa-phone text-xs text-gray-400 mr-2"></i>{{ $order->customer->mobile_no ?? '—' }}</p>
                </div>
                <a href="{{ route('customers.show', $order->customer) }}" class="mt-4 inline-flex items-center text-xs text-primary-600 font-medium hover:text-primary-700">
                    View Customer <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                </a>
            @else
                <p class="text-sm text-gray-400">Customer not found</p>
            @endif
        </div>

        <a href="{{ route('orders.index') }}" class="mt-4 inline-flex items-center gap-2 text-sm text-gray-500 hover:text-primary-600 transition">
            <i class="fas fa-arrow-left text-xs"></i> Back to Orders
        </a>
    </div>
</div>
@endsection
