@extends('frontend.layout')
@section('title', 'My Packages')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center gap-4 mb-8 animate-slide-up">
        <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-accent-100 rounded-xl flex items-center justify-center">
            <i class="fas fa-box text-purple-600"></i>
        </div>
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-800">My Packages</h1>
            <p class="text-sm text-gray-400">Your active package and purchase history</p>
        </div>
    </div>

    <!-- Active Package -->
    <div class="bg-white rounded-2xl overflow-hidden mb-6" style="box-shadow: 0 4px 20px -2px rgba(0,0,0,0.08);">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-bolt text-green-500 text-sm"></i>
            </div>
            <h2 class="text-sm font-heading font-semibold text-gray-800">Active Package</h2>
        </div>
        <div class="p-6">
            @if($customer->package && $customer->package_end_date && $customer->package_end_date->isFuture())
                <div class="flex flex-col md:flex-row md:items-center gap-6 p-5 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-100 rounded-xl">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-800">{{ $customer->package->name }}</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-3">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-semibold">Total Designs</p>
                                <p class="text-sm font-bold text-gray-800">{{ $customer->total_design }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-semibold">Downloaded</p>
                                <p class="text-sm font-bold text-gray-800">{{ $customer->downloaded_design }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-semibold">Remaining</p>
                                <p class="text-sm font-bold text-green-600">{{ $customer->total_design - $customer->downloaded_design }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-semibold">Expires</p>
                                <p class="text-sm font-bold text-gray-800">{{ $customer->package_end_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <!-- Progress bar -->
                        <div class="mt-4">
                            <div class="h-2 bg-green-200 rounded-full overflow-hidden">
                                <div class="h-full bg-green-500 rounded-full" style="width: {{ $customer->total_design > 0 ? ($customer->downloaded_design / $customer->total_design * 100) : 0 }}%"></div>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">{{ $customer->downloaded_design }} of {{ $customer->total_design }} designs used</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-box-open text-3xl mb-3 block"></i>
                    <p class="font-medium">No active package</p>
                    <p class="text-xs mt-1">Purchase a package to get access to multiple designs</p>
                    <a href="{{ route('frontend.packages') }}" class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 bg-primary-50 text-primary-600 rounded-xl font-semibold text-sm hover:bg-primary-100 transition-all">
                        <i class="fas fa-box-open"></i>View Packages
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Package History -->
    <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow: 0 4px 20px -2px rgba(0,0,0,0.08);">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-history text-blue-500 text-sm"></i>
            </div>
            <h2 class="text-sm font-heading font-semibold text-gray-800">Purchase History</h2>
            @if($packageOrders->count() > 0)
                <span class="ml-auto text-xs font-semibold text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full">{{ $packageOrders->count() }}</span>
            @endif
        </div>
        <div class="p-6">
            @if($packageOrders->count() > 0)
            <div class="space-y-3">
                @foreach($packageOrders as $order)
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-box text-purple-500 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-gray-800">{{ $order->package->name ?? 'Deleted Package' }}</h4>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $order->created_at->format('M d, Y') }}
                            @if($order->package)
                                · {{ $order->package->number_of_design }} designs · {{ $order->package->time_period }} months
                            @endif
                        </p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-sm font-bold text-gray-800">₹{{ number_format($order->amount, 2) }}</p>
                        <span class="inline-flex items-center text-[10px] font-semibold px-2 py-0.5 rounded-full text-green-600 bg-green-50 mt-1">
                            <i class="fas fa-circle text-[5px] mr-1"></i>Paid
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-400">
                <i class="fas fa-receipt text-2xl mb-2 block"></i>
                <p class="text-sm font-medium">No package purchases yet</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
