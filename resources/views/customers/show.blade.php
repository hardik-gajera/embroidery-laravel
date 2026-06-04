@extends('layouts.app')
@section('title', 'Customer Details')
@section('subtitle', 'View customer information')

@section('content')
<div class="space-y-6">
    <!-- Customer Info Card -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-5 bg-primary-600 text-white flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-lg font-bold">
                {{ strtoupper(substr($customer->name, 0, 2)) }}
            </div>
            <div>
                <h3 class="text-lg font-heading font-semibold">{{ $customer->name }}</h3>
                <p class="text-sm text-primary-200">{{ $customer->email }}</p>
            </div>
        </div>

        <!-- Details -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <p class="text-[11px] text-gray-400 uppercase font-semibold mb-1">Mobile Number</p>
                    <p class="text-sm font-medium text-gray-800">{{ $customer->mobile_no }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <p class="text-[11px] text-gray-400 uppercase font-semibold mb-1">Downloaded Designs</p>
                    <p class="text-sm font-medium text-gray-800">{{ $customer->downloaded_design }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <p class="text-[11px] text-gray-400 uppercase font-semibold mb-1">Total Designs</p>
                    <p class="text-sm font-medium text-gray-800">{{ $customer->total_design }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <p class="text-[11px] text-gray-400 uppercase font-semibold mb-1">Member Since</p>
                    <p class="text-sm font-medium text-gray-800">{{ $customer->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <!-- Progress -->
            <div class="mt-5 bg-gray-50 rounded-lg p-4 border border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-semibold text-gray-600">Download Progress</p>
                    <p class="text-xs text-gray-500">{{ $customer->total_design > 0 ? round(($customer->downloaded_design / $customer->total_design) * 100) : 0 }}%</p>
                </div>
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-primary-500 rounded-full" style="width: {{ $customer->total_design > 0 ? ($customer->downloaded_design / $customer->total_design * 100) : 0 }}%"></div>
                </div>
                <p class="text-[11px] text-gray-400 mt-1.5">{{ $customer->downloaded_design }} of {{ $customer->total_design }} designs downloaded</p>
            </div>
        </div>
    </div>

    <!-- Active Package -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-box text-purple-500 text-sm"></i>
            </div>
            <h3 class="text-sm font-heading font-semibold text-gray-800">Active Package</h3>
        </div>
        <div class="p-6">
            @if($customer->package && $customer->package_end_date && $customer->package_end_date->isFuture())
                <div class="flex items-start gap-4 p-4 bg-green-50 border border-green-100 rounded-xl">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-gray-800">{{ $customer->package->name }}</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-3">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-semibold">Designs</p>
                                <p class="text-sm font-bold text-gray-800">{{ $customer->package->number_of_design }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-semibold">Start Date</p>
                                <p class="text-sm font-bold text-gray-800">{{ $customer->package_start_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-semibold">End Date</p>
                                <p class="text-sm font-bold text-gray-800">{{ $customer->package_end_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-semibold">Days Left</p>
                                <p class="text-sm font-bold text-green-600">{{ now()->diffInDays($customer->package_end_date) }} days</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-6 text-gray-400">
                    <i class="fas fa-box-open text-2xl mb-2 block"></i>
                    <p class="text-sm font-medium">No active package</p>
                    <p class="text-xs mt-1">This customer hasn't purchased any package yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Package Purchase History -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-history text-blue-500 text-sm"></i>
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
                    <p class="text-xs mt-1">Package purchase history will appear here.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center gap-3">
        <a href="{{ route('customers.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-sm font-medium text-gray-600 bg-white">
            <i class="fas fa-arrow-left mr-1.5 text-xs"></i>Back
        </a>
        <a href="{{ route('customers.edit', $customer) }}" class="px-4 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-sm font-medium">
            <i class="fas fa-pen mr-1.5 text-xs"></i>Edit
        </a>
    </div>
</div>
@endsection
