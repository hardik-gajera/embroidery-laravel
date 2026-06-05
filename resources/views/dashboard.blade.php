@extends('layouts.app')
@section('title', 'Dashboard')
@section('subtitle', 'Overview of your embroidery business')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-5 border-t-4 border-t-indigo-500">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-indigo-500"></i>
            </div>
            <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full">Active</span>
        </div>
        <p class="text-3xl font-heading font-bold text-gray-800">{{ number_format($stats['total_customers']) }}</p>
        <p class="text-sm text-gray-500 mt-1">Total customers</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5 border-t-4 border-t-green-500">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                <i class="fas fa-download text-green-500"></i>
            </div>
            <span class="text-xs font-medium text-green-600 bg-green-50 px-2.5 py-1 rounded-full">Downloads</span>
        </div>
        <p class="text-3xl font-heading font-bold text-gray-800">{{ number_format($stats['total_downloaded']) }}</p>
        <p class="text-sm text-gray-500 mt-1">Downloaded designs</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5 border-t-4 border-t-purple-500">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                <i class="fas fa-palette text-purple-500"></i>
            </div>
            <span class="text-xs font-medium text-purple-600 bg-purple-50 px-2.5 py-1 rounded-full">Total</span>
        </div>
        <p class="text-3xl font-heading font-bold text-gray-800">{{ number_format($stats['total_designs']) }}</p>
        <p class="text-sm text-gray-500 mt-1">Total designs</p>
    </div>
</div>

<!-- Monthly Stats -->
<div class="bg-white rounded-xl border border-gray-200 p-5 mb-8">
    <div class="flex items-center justify-between mb-5">
        <h3 class="text-base font-heading font-semibold text-gray-800">Monthly Overview</h3>
        <form method="GET" class="flex items-center gap-2">
            <input type="month" name="month" value="{{ $selectedMonth }}" onchange="this.form.submit()"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition cursor-pointer">
        </form>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-xl p-5 border border-blue-100">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-blue-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-credit-card text-white text-sm"></i>
                </div>
                <span class="text-xs font-medium text-blue-600 uppercase tracking-wider">Razorpay Revenue</span>
            </div>
            <p class="text-2xl font-heading font-bold text-gray-800">₹{{ number_format($monthlyStats['razorpay_revenue'], 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">Direct payments this month</p>
        </div>
        <div class="bg-gradient-to-br from-amber-50 to-amber-100/50 rounded-xl p-5 border border-amber-100">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-amber-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box-open text-white text-sm"></i>
                </div>
                <span class="text-xs font-medium text-amber-600 uppercase tracking-wider">Package Downloads</span>
            </div>
            <p class="text-2xl font-heading font-bold text-gray-800">{{ number_format($monthlyStats['package_downloads']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Designs claimed via packages</p>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-purple-100/50 rounded-xl p-5 border border-purple-100">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-purple-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box text-white text-sm"></i>
                </div>
                <span class="text-xs font-medium text-purple-600 uppercase tracking-wider">Package Revenue</span>
            </div>
            <p class="text-2xl font-heading font-bold text-gray-800">₹{{ number_format($monthlyStats['package_revenue'], 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">Packages activated this month</p>
        </div>
    </div>
</div>

<!-- Recent Customers -->
<div class="bg-white rounded-xl border border-gray-200">
    <div class="px-5 py-4 flex items-center justify-between">
        <h3 class="text-base font-heading font-semibold text-gray-800">Recent customers</h3>
        <a href="{{ route('customers.index') }}" class="text-xs text-gray-500 hover:text-primary-600 font-medium">View all →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-t border-gray-100">
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Customer</th>
                    <th class="px-5 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Downloads</th>
                    <th class="px-5 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Designs</th>
                    <th class="px-5 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($recentCustomers as $customer)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-[11px] font-bold">
                                {{ strtoupper(substr($customer->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $customer->name }}</p>
                                <p class="text-xs text-gray-400">{{ $customer->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-orange-600 bg-orange-50 px-2.5 py-1 rounded-full">
                            <i class="fas fa-arrow-trend-up text-[9px]"></i>{{ $customer->downloaded_design }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="inline-flex items-center text-xs font-semibold text-green-600 bg-green-50 px-2.5 py-1 rounded-full">
                            {{ $customer->total_design }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('customers.show', $customer) }}" class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-400 hover:text-blue-600 transition" title="View">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ route('customers.edit', $customer) }}" class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center text-green-400 hover:text-green-600 transition" title="Edit">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            <a href="#" class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-400 hover:text-red-600 transition" title="Delete">
                                <i class="fas fa-trash text-xs"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-10 text-center text-gray-400 text-sm">No customers yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
