@extends('layouts.app')
@section('title', 'Orders')
@section('subtitle', 'Manage design & package orders')

@section('content')
<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-400 font-medium uppercase">Total Orders</p>
        <p class="text-2xl font-heading font-bold text-gray-800 mt-1">{{ number_format($stats['total']) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-400 font-medium uppercase">Revenue</p>
        <p class="text-2xl font-heading font-bold text-green-600 mt-1">₹{{ number_format($stats['revenue'], 2) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-400 font-medium uppercase">Designs Sold</p>
        <p class="text-2xl font-heading font-bold text-primary-600 mt-1">{{ number_format($stats['designs_sold']) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-400 font-medium uppercase">Packages Sold</p>
        <p class="text-2xl font-heading font-bold text-purple-600 mt-1">{{ number_format($stats['packages_sold']) }}</p>
    </div>
</div>

<!-- Filters -->
<div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-5">
    <form method="GET" class="flex-1 max-w-sm">
        <div class="relative">
            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by customer..."
                class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition">
        </div>
    </form>
    <div class="flex items-center gap-2">
        <a href="{{ route('orders.index') }}" class="px-3 py-2 rounded-lg text-xs font-medium transition {{ !request('type') && !request('status') ? 'bg-primary-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">All</a>
        <a href="{{ route('orders.index', ['type' => 'design']) }}" class="px-3 py-2 rounded-lg text-xs font-medium transition {{ request('type') === 'design' ? 'bg-primary-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">Designs</a>
        <a href="{{ route('orders.index', ['type' => 'package']) }}" class="px-3 py-2 rounded-lg text-xs font-medium transition {{ request('type') === 'package' ? 'bg-primary-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">Packages</a>
        <a href="{{ route('orders.index', ['status' => 'paid']) }}" class="px-3 py-2 rounded-lg text-xs font-medium transition {{ request('status') === 'paid' ? 'bg-green-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">Paid</a>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-xl border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">#</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Customer</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Item</th>
                    <th class="px-5 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Amount</th>
                    <th class="px-5 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Type</th>
                    <th class="px-5 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Payment Via</th>
                    <th class="px-5 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Date</th>
                    <th class="px-5 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-5 py-3.5 text-sm text-gray-400">{{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                    <td class="px-5 py-3.5">
                        <p class="text-sm font-medium text-gray-800">{{ $order->customer->name ?? '—' }}</p>
                        <p class="text-xs text-gray-400">{{ $order->customer->email ?? '' }}</p>
                    </td>
                    <td class="px-5 py-3.5">
                        @if($order->design)
                            <div class="flex items-center gap-2">
                                @if($order->design->design_img)
                                    <img src="{{ asset('storage/' . $order->design->design_img) }}" class="w-8 h-8 rounded object-cover border border-gray-200">
                                @endif
                                <span class="text-sm text-gray-700">{{ $order->design->name }}</span>
                            </div>
                        @elseif($order->package)
                            <span class="text-sm text-gray-700"><i class="fas fa-box text-purple-400 mr-1"></i>{{ $order->package->name }}</span>
                        @else
                            <span class="text-xs text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        @if($order->amount > 0)
                            <span class="text-sm font-semibold text-green-600">₹{{ number_format($order->amount, 2) }}</span>
                        @else
                            <span class="text-xs text-gray-400">Free</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        @if($order->design_id)
                            <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full">Design</span>
                        @else
                            <span class="text-xs font-medium text-purple-600 bg-purple-50 px-2.5 py-1 rounded-full">Package</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        @if($order->razorpay_payment_id === 'package_claim')
                            <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full"><i class="fas fa-box-open mr-0.5"></i> Package Claim</span>
                        @else
                            <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full"><i class="fas fa-credit-card mr-0.5"></i> Razorpay</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $order->status === 'paid' ? 'text-green-600 bg-green-50' : 'text-orange-600 bg-orange-50' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-center text-xs text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3.5 text-center">
                        <a href="{{ route('orders.show', $order) }}" class="w-8 h-8 rounded-full bg-blue-50 inline-flex items-center justify-center text-blue-400 hover:text-blue-600 transition" title="View">
                            <i class="fas fa-eye text-xs"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-5 py-12 text-center text-gray-400 text-sm">
                        <i class="fas fa-receipt text-2xl mb-2 block"></i>
                        <p class="font-medium">No orders found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
