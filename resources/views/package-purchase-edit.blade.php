@extends('layouts.app')
@section('title', 'Edit Package Purchase')
@section('subtitle', 'Update package purchase details')

@section('content')
<div>
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-primary-600 text-white flex items-center gap-3">
            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-edit text-sm"></i>
            </div>
            <div>
                <h3 class="text-sm font-heading font-semibold">Edit Package Purchase</h3>
                <p class="text-xs text-primary-200">{{ $purchase->customer->name }} — {{ $purchase->package->name ?? 'Deleted Package' }}</p>
            </div>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('package-purchase.update', $purchase->id) }}">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Total Designs</label>
                        <input type="number" name="total" value="{{ old('total', $purchase->total) }}" min="0"
                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-50 @error('total') border-red-400 @enderror">
                        @error('total') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Downloaded</label>
                        <input type="number" name="downloaded" value="{{ old('downloaded', $purchase->downloaded) }}" min="0"
                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-50 @error('downloaded') border-red-400 @enderror">
                        @error('downloaded') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Start Date</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $purchase->start_date?->format('Y-m-d')) }}"
                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-50 @error('start_date') border-red-400 @enderror">
                        @error('start_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">End Date</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $purchase->end_date?->format('Y-m-d')) }}"
                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-50 @error('end_date') border-red-400 @enderror">
                        @error('end_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
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