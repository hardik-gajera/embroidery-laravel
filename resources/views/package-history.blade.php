@extends('layouts.app')
@section('title', 'Package History')
@section('subtitle', 'All customer package purchases')

@section('content')
<!-- Header Actions -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
    <form method="GET" class="flex-1 max-w-sm">
        <div class="relative">
            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by customer name or email..."
                class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-50 transition">
            @if(request('search'))
                <a href="{{ route('package-history') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"><i class="fas fa-times-circle text-sm"></i></a>
            @endif
        </div>
    </form>
    <div class="text-sm text-gray-500">
        <span class="font-semibold text-gray-700">{{ $orders->total() }}</span> total purchases
    </div>
</div>

@if(request('search'))
<div class="mb-4 text-sm text-gray-500">
    Showing results for "<span class="font-medium text-gray-700">{{ request('search') }}</span>"
</div>
@endif

<!-- Table -->
<div class="bg-white rounded-xl border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">#</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Customer</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Package</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Amount</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Payment ID</th>
                    <th class="px-5 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Date</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-5 py-3.5 text-sm text-gray-400">{{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-[11px] font-bold">
                                {{ strtoupper(substr($order->customer->name ?? 'X', 0, 2)) }}
                            </div>
                            <div>
                                <a href="{{ route('customers.show', $order->customer_id) }}" class="text-sm font-medium text-gray-800 hover:text-primary-600 transition">{{ $order->customer->name ?? 'Deleted' }}</a>
                                <p class="text-xs text-gray-400">{{ $order->customer->email ?? '' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        <p class="text-sm font-medium text-gray-800">{{ $order->package->name ?? 'Deleted Package' }}</p>
                        @if($order->package)
                            <p class="text-xs text-gray-400">{{ $order->package->number_of_design }} designs · {{ $order->package->time_period }} months</p>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-sm font-semibold text-gray-800">₹{{ number_format($order->amount, 2) }}</td>
                    <td class="px-5 py-3.5 text-xs font-mono text-gray-500">{{ $order->razorpay_payment_id ?: '-' }}</td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full
                            {{ $order->status === 'paid' ? 'text-green-600 bg-green-50' : ($order->status === 'failed' ? 'text-red-600 bg-red-50' : 'text-yellow-600 bg-yellow-50') }}">
                            <i class="fas fa-circle text-[6px] mr-1.5"></i>{{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3.5">
                        <a href="{{ route('order-package.edit', $order->id) }}" class="inline-flex items-center text-xs font-medium text-blue-600 hover:text-blue-800 transition">
                            <i class="fas fa-pen text-[10px] mr-1"></i>Edit
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-12 text-center text-gray-400 text-sm">
                        <i class="fas fa-receipt text-2xl mb-2 block"></i>
                        <p class="font-medium">No package purchases found</p>
                        <p class="text-xs mt-1">{{ request('search') ? 'Try a different search term' : 'Package purchases will appear here' }}</p>
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