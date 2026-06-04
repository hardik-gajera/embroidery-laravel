@extends('layouts.app')
@section('title', 'Edit Package')
@section('subtitle', 'Update package information')

@section('content')
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 bg-primary-600 text-white flex items-center gap-3">
        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
            <i class="fas fa-pen text-sm"></i>
        </div>
        <div>
            <h3 class="text-sm font-heading font-semibold">Edit Package</h3>
            <p class="text-xs text-primary-200">Updating {{ $package->name }}</p>
        </div>
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route('packages.update', $package) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('packages._form')

            <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
                <a href="{{ route('packages.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-sm font-medium text-gray-600">Cancel</a>
                <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-sm font-medium">
                    <i class="fas fa-save mr-1.5"></i>Update Package
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
