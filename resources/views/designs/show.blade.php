@extends('layouts.app')
@section('title', 'Design Details')
@section('subtitle', 'View design information')

@section('content')
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-5 bg-primary-600 text-white flex items-center gap-4">
        @if($design->design_img)
            <img src="{{ asset('storage/' . $design->design_img) }}" class="w-14 h-14 rounded-lg object-cover border-2 border-white/20">
        @else
            <div class="w-14 h-14 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-swatchbook text-xl"></i>
            </div>
        @endif
        <div>
            <h3 class="text-lg font-heading font-semibold">{{ $design->name }}</h3>
            <p class="text-sm text-primary-200">{{ $design->design_code ?? 'No code' }} • {{ $design->design_format }}</p>
        </div>
    </div>

    <!-- Details -->
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                <p class="text-[11px] text-gray-400 uppercase font-semibold mb-1">Design Code</p>
                <p class="text-sm font-medium text-gray-800">{{ $design->design_code ?? '—' }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                <p class="text-[11px] text-gray-400 uppercase font-semibold mb-1">Category</p>
                <p class="text-sm font-medium text-gray-800">{{ $design->category->name ?? '—' }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                <p class="text-[11px] text-gray-400 uppercase font-semibold mb-1">Price</p>
                <p class="text-sm font-medium text-gray-800">₹{{ number_format($design->design_price, 2) }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                <p class="text-[11px] text-gray-400 uppercase font-semibold mb-1">Stitches</p>
                <p class="text-sm font-medium text-gray-800">{{ number_format($design->stitches) }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                <p class="text-[11px] text-gray-400 uppercase font-semibold mb-1">Height</p>
                <p class="text-sm font-medium text-gray-800">{{ $design->height ?? '—' }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                <p class="text-[11px] text-gray-400 uppercase font-semibold mb-1">Width</p>
                <p class="text-sm font-medium text-gray-800">{{ $design->width ?? '—' }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                <p class="text-[11px] text-gray-400 uppercase font-semibold mb-1">Area</p>
                <p class="text-sm font-medium text-gray-800">{{ $design->area ?? '—' }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                <p class="text-[11px] text-gray-400 uppercase font-semibold mb-1">Needle/Color</p>
                <p class="text-sm font-medium text-gray-800">{{ $design->needle_color ?? '—' }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                <p class="text-[11px] text-gray-400 uppercase font-semibold mb-1">Format</p>
                <p class="text-sm font-medium text-gray-800">{{ $design->design_format }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                <p class="text-[11px] text-gray-400 uppercase font-semibold mb-1">File Name</p>
                <p class="text-sm font-medium text-gray-800">{{ $design->file_name ?? '—' }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                <p class="text-[11px] text-gray-400 uppercase font-semibold mb-1">Created</p>
                <p class="text-sm font-medium text-gray-800">{{ $design->created_at->format('M d, Y') }}</p>
            </div>
        </div>

        @if($design->description)
        <div class="mt-4 bg-gray-50 rounded-lg p-4 border border-gray-100">
            <p class="text-[11px] text-gray-400 uppercase font-semibold mb-1">Description</p>
            <p class="text-sm text-gray-700">{{ $design->description }}</p>
        </div>
        @endif

        <!-- Actions -->
        <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
            <a href="{{ route('designs.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-sm font-medium text-gray-600">
                <i class="fas fa-arrow-left mr-1.5 text-xs"></i>Back
            </a>
            <a href="{{ route('designs.edit', $design) }}" class="px-4 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-sm font-medium">
                <i class="fas fa-pen mr-1.5 text-xs"></i>Edit
            </a>
            <a href="{{ asset('storage/' . $design->emb_file) }}" download class="px-4 py-2.5 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm font-medium">
                <i class="fas fa-download mr-1.5 text-xs"></i>Download File
            </a>
        </div>
    </div>
</div>
@endsection
