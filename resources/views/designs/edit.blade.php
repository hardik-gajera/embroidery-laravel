@extends('layouts.app')
@section('title', 'Edit Design')
@section('subtitle', 'Update design information')

@section('content')
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 bg-primary-600 text-white flex items-center gap-3">
        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
            <i class="fas fa-pen text-sm"></i>
        </div>
        <div>
            <h3 class="text-sm font-heading font-semibold">Edit Design</h3>
            <p class="text-xs text-primary-200">Updating {{ $design->name }}</p>
        </div>
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route('designs.update', $design) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('designs._form')

            @if($design->design_img)
            <div class="mt-4">
                <p class="text-xs text-gray-500 mb-1.5">Current Image:</p>
                <img src="{{ asset('storage/' . $design->design_img) }}" class="w-20 h-20 rounded-lg object-cover border border-gray-200">
            </div>
            @endif

            <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
                <a href="{{ route('designs.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-sm font-medium text-gray-600">Cancel</a>
                <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-sm font-medium">
                    <i class="fas fa-save mr-1.5"></i>Update Design
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
