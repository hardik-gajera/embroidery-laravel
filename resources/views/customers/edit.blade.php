@extends('layouts.app')
@section('title', 'Edit Customer')
@section('subtitle', 'Update customer information')

@section('content')
<div class="space-y-6">
    <!-- Edit Form -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-primary-600 text-white flex items-center gap-3">
            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-edit text-sm"></i>
            </div>
            <div>
                <h3 class="text-sm font-heading font-semibold">Edit Customer</h3>
                <p class="text-xs text-primary-200">Updating {{ $customer->name }}</p>
            </div>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('customers.update', $customer) }}">
                @csrf @method('PUT')
                @include('customers._form')

                <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
                    <a href="{{ route('customers.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-sm font-medium text-gray-600">Cancel</a>
                    <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-sm font-medium">
                        <i class="fas fa-save mr-1.5"></i>Update Customer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Package Purchase History -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-history text-purple-500 text-sm"></i>
            </div>
            <h3 class="text-sm font-heading font-semibold text-gray-800">Package Purchase History</h3>
            @if($packageOrders->count() > 0)
                <span class="ml-auto text-xs font-semibold text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full">{{ $packageOrders->count() }} purchases</span>
            @endif
        </div>
        <div class="p-6">
            @if($packageOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="px-4 py-2.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">#</th>
                                <th class="px-4 py-2.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Package</th>
                                <th class="px-4 py-2.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-2.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Downloaded</th>
                                <th class="px-4 py-2.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Period</th>
                                <th class="px-4 py-2.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($packageOrders as $order)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3 text-sm text-gray-400">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3">
                                    <p class="text-sm font-medium text-gray-800">{{ $order->package->name ?? 'Deleted Package' }}</p>
                                    @if($order->package)
                                        <p class="text-xs text-gray-400">{{ $order->total }} designs · {{ $order->package->time_period }} months</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-800">₹{{ number_format($order->amount, 2) }}</td>
                                <td class="px-4 py-3">
                                    <span class="text-xs font-semibold text-primary-600 bg-primary-50 px-2 py-0.5 rounded-full">{{ $order->downloaded }} / {{ $order->total }}</span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500">
                                    @if($order->start_date)
                                        {{ \Carbon\Carbon::parse($order->start_date)->format('M d, Y') }} — {{ \Carbon\Carbon::parse($order->end_date)->format('M d, Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($order->end_date && \Carbon\Carbon::parse($order->end_date)->isFuture() && $order->downloaded < $order->total)
                                        <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full text-green-600 bg-green-50">
                                            <i class="fas fa-circle text-[6px] mr-1.5"></i>Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full text-gray-500 bg-gray-100">
                                            <i class="fas fa-circle text-[6px] mr-1.5"></i>Expired
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-6 text-gray-400">
                    <i class="fas fa-receipt text-2xl mb-2 block"></i>
                    <p class="text-sm font-medium">No package purchases yet</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
