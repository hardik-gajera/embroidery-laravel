@extends('layouts.app')
@section('title', 'Edit Package Order')
@section('subtitle', 'Update package order details')

@section('content')
<div>
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-primary-600 text-white flex items-center gap-3">
            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-edit text-sm"></i>
            </div>
            <div>
                <h3 class="text-sm font-heading font-semibold">Edit Package Order</h3>
                <p class="text-xs text-primary-200">{{ $order->customer->name ?? 'Deleted' }} — {{ $order->package->name ?? 'Deleted Package' }}</p>
            </div>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('order-package.update', $order->id) }}">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Amount (₹)</label>
                        <input type="number" name="amount" value="{{ old('amount', $order->amount) }}" min="0" step="0.01"
                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-50 @error('amount') border-red-400 @enderror">
                        @error('amount') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status</label>
                        <select name="status"
                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-50 @error('status') border-red-400 @enderror">
                            <option value="pending" {{ old('status', $order->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ old('status', $order->status) === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ old('status', $order->status) === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                        @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Read-only info -->
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                        <p class="text-[10px] text-gray-400 uppercase font-semibold">Payment ID</p>
                        <p class="text-sm text-gray-700 mt-0.5">{{ $order->razorpay_payment_id ?: '-' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                        <p class="text-[10px] text-gray-400 uppercase font-semibold">Purchased On</p>
                        <p class="text-sm text-gray-700 mt-0.5">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
                    <a href="{{ url()->previous() }}" class="px-4 py-2.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-sm font-medium text-gray-600">Cancel</a>
                    <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-sm font-medium">
                        <i class="fas fa-save mr-1.5"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection