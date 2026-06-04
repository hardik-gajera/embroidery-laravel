@extends('frontend.layout')
@section('title', $category->name . ' Designs')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-8 animate-fade-in">
        <a href="{{ route('home') }}" class="hover:text-primary-600 transition"><i class="fas fa-home"></i></a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        @if($category->parent)
            <a href="{{ route('frontend.categories', $category->parent->id) }}" class="hover:text-primary-600 transition">{{ $category->parent->name }}</a>
            <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        @endif
        <span class="text-gray-800 font-medium">{{ $category->name }}</span>
    </div>

    <div class="flex items-center justify-between mb-10 animate-slide-up">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-primary-100 to-accent-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-swatchbook text-primary-600"></i>
            </div>
            <div>
                <h1 class="text-2xl font-heading font-bold text-gray-800">{{ $category->name }}</h1>
                <p class="text-sm text-gray-400">{{ $designs->total() }} designs available</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
        @forelse($designs as $design)
        <a href="{{ route('frontend.design.detail', $design->id) }}" class="card-hover bg-white rounded-2xl overflow-hidden group">
            @if($design->design_img)
                <div class="aspect-[4/3] overflow-hidden">
                    <img src="{{ asset('storage/' . $design->design_img) }}" alt="{{ $design->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
            @else
                <div class="aspect-[4/3] bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center">
                    <i class="fas fa-swatchbook text-gray-600 text-3xl group-hover:scale-110 transition-transform"></i>
                </div>
            @endif
            <div class="p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-swatchbook text-green-500 text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-800 text-sm truncate group-hover:text-primary-600 transition-colors">{{ $design->name }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">₹{{ number_format($design->design_price) }} · {{ $design->stitches ? number_format($design->stitches) . ' stitches' : $design->design_format }}</p>
                </div>
                <i class="fas fa-chevron-right text-gray-300 text-xs group-hover:text-primary-500 group-hover:translate-x-1 transition-all"></i>
            </div>
        </a>
        @empty
        <div class="col-span-full text-center py-20">
            <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-swatchbook text-gray-300 text-3xl"></i>
            </div>
            <p class="font-medium text-gray-500">No designs in this category yet.</p>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mt-4 text-sm text-primary-600 font-medium hover:text-primary-700">
                <i class="fas fa-arrow-left text-xs"></i>Back to Home
            </a>
        </div>
        @endforelse
    </div>

    @if($designs->hasPages())
    <div class="mt-10 flex justify-center">{{ $designs->links() }}</div>
    @endif
</div>
@endsection
