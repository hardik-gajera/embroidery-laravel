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
                <div class="mt-4 flex items-center gap-3">
                    <button type="button" onclick="document.getElementById('editPackageSection').classList.toggle('hidden')" class="px-3.5 py-2 text-xs font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">
                        <i class="fas fa-pen mr-1"></i>Edit Downloads
                    </button>
                    <form method="POST" action="{{ route('customers.remove-package', $customer) }}" onsubmit="return confirm('Are you sure you want to remove this package?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-3.5 py-2 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition">
                            <i class="fas fa-times mr-1"></i>Remove Package
                        </button>
                    </form>
                </div>

                <!-- Edit Downloaded Designs -->
                <div id="editPackageSection" class="hidden mt-4 p-4 bg-blue-50 border border-blue-100 rounded-xl">
                    <h4 class="text-xs font-semibold text-gray-700 mb-3"><i class="fas fa-edit mr-1"></i>Edit Downloaded Designs Count</h4>
                    <form method="POST" action="{{ route('customers.update-package', $customer) }}">
                        @csrf @method('PUT')
                        <div class="flex items-end gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Downloaded Designs</label>
                                <input type="number" name="downloaded_design" value="{{ $customer->downloaded_design }}" min="0" max="{{ $customer->total_design }}" required
                                    class="w-40 px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-50">
                                <p class="text-[10px] text-gray-400 mt-1">Max: {{ $customer->total_design }}</p>
                            </div>
                            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                                <i class="fas fa-save mr-1.5"></i>Update
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <!-- Assign Package Form -->
                <form method="POST" action="{{ route('customers.assign-package', $customer) }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Select Package</label>
                            <select name="package_id" required
                                class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-50 @error('package_id') border-red-400 @enderror">
                                <option value="">Choose a package...</option>
                                @foreach($packages as $pkg)
                                    <option value="{{ $pkg->id }}">{{ $pkg->name }} ({{ $pkg->number_of_design }} designs / {{ $pkg->time_period }} months - ₹{{ number_format($pkg->price, 2) }})</option>
                                @endforeach
                            </select>
                            @error('package_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Start Date</label>
                            <input type="date" name="start_date" value="{{ date('Y-m-d') }}" required
                                class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-50 @error('start_date') border-red-400 @enderror">
                            @error('start_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Downloaded Designs</label>
                            <input type="number" name="downloaded_design" value="0" min="0" required
                                class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-50 @error('downloaded_design') border-red-400 @enderror">
                            @error('downloaded_design') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-sm font-medium">
                                <i class="fas fa-plus mr-1.5"></i>Assign Package
                            </button>
                        </div>
                    </div>
                </form>
            @endif
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
                                <th class="px-4 py-2.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Action</th>
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
                                <td class="px-4 py-3">
                                    @if($order->source === 'odoo')
                                        <a href="{{ route('package-purchase.edit', $order->id) }}" class="inline-flex items-center text-xs font-medium text-blue-600 hover:text-blue-800 transition">
                                            <i class="fas fa-pen text-[10px] mr-1"></i>Edit
                                        </a>
                                    @else
                                        <a href="{{ route('order-package.edit', $order->id) }}" class="inline-flex items-center text-xs font-medium text-blue-600 hover:text-blue-800 transition">
                                            <i class="fas fa-pen text-[10px] mr-1"></i>Edit
                                        </a>
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