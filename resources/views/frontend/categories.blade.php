@extends('frontend.layout')
@section('title', $category->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-8 animate-fade-in">
        <a href="{{ route('home') }}" class="hover:text-primary-600 transition"><i class="fas fa-home"></i></a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <span class="text-gray-800 font-medium">{{ $category->name }}</span>
    </div>

    <div class="flex items-center gap-4 mb-10 animate-slide-up">
        <div class="w-12 h-12 bg-gradient-to-br from-primary-100 to-accent-100 rounded-xl flex items-center justify-center">
            <i class="fas fa-folder-open text-primary-600"></i>
        </div>
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-800">{{ $category->name }}</h1>
            <p class="text-sm text-gray-400">{{ $children->count() }} sub-categories</p>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
        @foreach($children as $child)
        <a href="{{ route('frontend.categories', $child->id) }}" class="group card-hover bg-white rounded-2xl overflow-hidden">
            @if($child->image)
                <div class="aspect-[4/3] overflow-hidden">
                    <img src="{{ asset('storage/' . $child->image) }}" alt="{{ $child->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
            @else
                <div class="aspect-[4/3] bg-gradient-to-br from-primary-900 to-primary-800 flex items-center justify-center">
                    <i class="fas fa-folder text-primary-400 text-3xl group-hover:scale-110 transition-transform"></i>
                </div>
            @endif
            <div class="p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-th-large text-primary-500 text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-800 text-sm truncate group-hover:text-primary-600 transition-colors">{{ $child->name }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Browse designs</p>
                </div>
                <i class="fas fa-chevron-right text-gray-300 text-xs group-hover:text-primary-500 group-hover:translate-x-1 transition-all"></i>
            </div>
        </a>
        @endforeach
    </div>

    @if($designs->count())
    <div class="mt-12">
        <h2 class="text-xl font-heading font-bold text-gray-800 mb-6">Designs in {{ $category->name }}</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
            @foreach($designs as $design)
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
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
