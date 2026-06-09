@extends('layouts.app')
@section('title', 'Add Design')
@section('subtitle', 'Upload a new embroidery design')

@section('content')
<!-- Alert about auto-generation -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
    <div class="flex items-start gap-3">
        <i class="fas fa-info-circle text-blue-500 text-sm mt-0.5"></i>
        <div>
            <h4 class="text-sm font-medium text-blue-800">Automatic Code Generation</h4>
            <p class="text-sm text-blue-600 mt-1">A unique design code will be automatically generated when you save this design. You don't need to enter it manually.</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 bg-primary-600 text-white flex items-center gap-3">
        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
            <i class="fas fa-plus text-sm"></i>
        </div>
        <div>
            <h3 class="text-sm font-heading font-semibold">New Design</h3>
            <p class="text-xs text-primary-200">Upload embroidery design details</p>
        </div>
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route('designs.store') }}" enctype="multipart/form-data">
            @csrf
            @include('designs._form')

            <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
                <a href="{{ route('designs.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-sm font-medium text-gray-600">Cancel</a>
                <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-sm font-medium">
                    <i class="fas fa-check mr-1.5"></i>Save Design
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
