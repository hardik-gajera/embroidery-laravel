@extends('layouts.app')
@section('title', 'Package Details')
@section('subtitle', 'View package information')

@section('content')
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-5 bg-primary-600 text-white flex items-center gap-4">
        @if($package->package_img)
            <img src="{{ asset('storage/' . $package->package_img) }}" class="w-14 h-14 rounded-lg object-cover border-2 border-white/20">
        @else
            <div class="w-14 h-14 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-box text-xl"></i>
            </div>
        @endif
        <div>
            <h3 class="text-lg font-heading font-semibold">{{ $package->name }}</h3>
            <p class="text-sm text-primary-200">
                @if($package->state === 'draft')
                    <span class="bg-white/20 px-2 py-0.5 rounded text-xs">Draft</span>
                @elseif($package->state === 'confirm')
                    <span class="bg-green-400/20 px-2 py-0.5 rounded text-xs">Confirmed</span>
                @else
                    <span class="bg-blue-400/20 px-2 py-0.5 rounded text-xs">Finished</span>
                @endif
            </p>
        </div>
    </div>

    <!-- Details -->
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                <p class="text-[11px] text-gray-400 uppercase font-semibold mb-1">Number of Designs</p>
                <p class="text-lg font-bold text-gray-800">{{ $package->number_of_design }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                <p class="text-[11px] text-gray-400 uppercase font-semibold mb-1">Time Period</p>
                <p class="text-lg font-bold text-gray-800">{{ $package->time_period }} {{ $package->time_period == 1 ? 'month' : 'months' }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                <p class="text-[11px] text-gray-400 uppercase font-semibold mb-1">Price</p>
                <p class="text-lg font-bold text-gray-800">₹{{ number_format($package->price, 2) }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                <p class="text-[11px] text-gray-400 uppercase font-semibold mb-1">Created</p>
                <p class="text-lg font-bold text-gray-800">{{ $package->created_at->format('M d, Y') }}</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
            <a href="{{ route('packages.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-sm font-medium text-gray-600">
                <i class="fas fa-arrow-left mr-1.5 text-xs"></i>Back
            </a>
            <a href="{{ route('packages.edit', $package) }}" class="px-4 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-sm font-medium">
                <i class="fas fa-pen mr-1.5 text-xs"></i>Edit
            </a>
        </div>
    </div>
</div>
@endsection
