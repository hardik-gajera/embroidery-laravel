@extends('frontend.layout')
@section('title', $design->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-8 animate-fade-in">
        <a href="{{ route('home') }}" class="hover:text-primary-600 transition"><i class="fas fa-home"></i></a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        @if($design->category)
            <a href="{{ route('frontend.designs', $design->category->id) }}" class="hover:text-primary-600 transition">{{ $design->category->name }}</a>
            <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        @endif
        <span class="text-gray-800 font-medium">{{ $design->name }}</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <!-- Image -->
        <div class="animate-scale-in">
            <div class="bg-white rounded-2xl overflow-hidden group" style="box-shadow: 0 8px 30px -4px rgba(0,0,0,0.1), 0 4px 12px -2px rgba(0,0,0,0.05);">
                @if($design->design_img)
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('storage/' . $design->design_img) }}" alt="{{ $design->name }}" class="w-full aspect-square object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1.5 bg-white/90 backdrop-blur-sm rounded-lg text-xs font-semibold text-primary-600 shadow-sm">{{ strtoupper($design->design_format) }}</span>
                        </div>
                    </div>
                @else
                    <div class="w-full aspect-square bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center">
                        <i class="fas fa-swatchbook text-gray-300 text-6xl"></i>
                    </div>
                @endif
            </div>
        </div>

        <!-- Details -->
        <div class="animate-slide-up">
            <div class="sticky top-24">
                @if($design->design_code)
                    <span class="inline-block px-3 py-1 bg-gray-100 text-gray-500 text-xs font-mono rounded-lg mb-3">{{ $design->design_code }}</span>
                @endif
                <h1 class="text-3xl font-heading font-bold text-gray-800">{{ $design->name }}</h1>

                <div class="flex items-center gap-3 mt-4">
                    <p class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-accent-600 bg-clip-text text-transparent">₹{{ number_format($design->design_price, 2) }}</p>
                    <span class="px-2.5 py-1 bg-green-50 text-green-600 text-xs font-semibold rounded-lg"><i class="fas fa-check-circle mr-1"></i>Available</span>
                </div>

                <!-- Specs -->
                <div class="grid grid-cols-2 gap-3 mt-8">
                    @if($design->stitches)
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-4 border border-gray-100">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-layer-group text-primary-400 text-xs"></i>
                            <p class="text-[10px] text-gray-400 uppercase font-semibold tracking-wider">Stitches</p>
                        </div>
                        <p class="text-sm font-bold text-gray-800">{{ number_format($design->stitches) }}</p>
                    </div>
                    @endif
                    @if($design->height)
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-4 border border-gray-100">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-arrows-alt-v text-green-400 text-xs"></i>
                            <p class="text-[10px] text-gray-400 uppercase font-semibold tracking-wider">Height</p>
                        </div>
                        <p class="text-sm font-bold text-gray-800">{{ $design->height }}</p>
                    </div>
                    @endif
                    @if($design->width)
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-4 border border-gray-100">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-arrows-alt-h text-blue-400 text-xs"></i>
                            <p class="text-[10px] text-gray-400 uppercase font-semibold tracking-wider">Width</p>
                        </div>
                        <p class="text-sm font-bold text-gray-800">{{ $design->width }}</p>
                    </div>
                    @endif
                    @if($design->needle_color)
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-4 border border-gray-100">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-palette text-accent-400 text-xs"></i>
                            <p class="text-[10px] text-gray-400 uppercase font-semibold tracking-wider">Needle/Color</p>
                        </div>
                        <p class="text-sm font-bold text-gray-800">{{ $design->needle_color }}</p>
                    </div>
                    @endif
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-4 border border-gray-100">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-file text-orange-400 text-xs"></i>
                            <p class="text-[10px] text-gray-400 uppercase font-semibold tracking-wider">Format</p>
                        </div>
                        <p class="text-sm font-bold text-gray-800">{{ strtoupper($design->design_format) }}</p>
                    </div>
                    @if($design->category)
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-4 border border-gray-100">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-tag text-purple-400 text-xs"></i>
                            <p class="text-[10px] text-gray-400 uppercase font-semibold tracking-wider">Category</p>
                        </div>
                        <p class="text-sm font-bold text-gray-800">{{ $design->category->name }}</p>
                    </div>
                    @endif
                </div>

                @if($design->description)
                <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2"><i class="fas fa-info-circle text-primary-400 mr-1.5"></i>Description</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ $design->description }}</p>
                </div>
                @endif

                <!-- Actions -->
                <div class="flex gap-3 mt-8">
                    <form action="{{ route('frontend.cart.add') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="design_id" value="{{ $design->id }}">
                        <button class="w-full py-3.5 border-2 border-primary-600 text-primary-600 rounded-xl font-semibold hover:bg-primary-50 transition-all hover:scale-[1.02]">
                            <i class="fas fa-cart-plus mr-2"></i>Add to Cart
                        </button>
                    </form>
                    <form action="{{ route('frontend.buy') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="design_id" value="{{ $design->id }}">
                        <button class="btn-glow w-full py-3.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-xl font-semibold hover:from-primary-700 hover:to-primary-800 transition-all shadow-lg shadow-primary-200 hover:scale-[1.02]">
                            <i class="fas fa-bolt mr-2"></i>Buy Now
                        </button>
                    </form>
                </div>

                <!-- Trust badges -->
                <div class="flex items-center justify-center gap-6 mt-6 pt-6 border-t border-gray-100">
                    <span class="text-xs text-gray-400"><i class="fas fa-shield-alt text-green-400 mr-1"></i>Secure Payment</span>
                    <span class="text-xs text-gray-400"><i class="fas fa-download text-blue-400 mr-1"></i>Instant Download</span>
                    <span class="text-xs text-gray-400"><i class="fas fa-headset text-purple-400 mr-1"></i>24/7 Support</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Designs -->
    @if($related->count() > 0)
    <section class="mt-20 scroll-reveal">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xl font-heading font-bold text-gray-800">Related Designs</h2>
            <a href="{{ route('frontend.designs', $design->category_id) }}" class="text-sm text-primary-600 font-medium hover:text-primary-700">View All →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
            @foreach($related as $item)
            <a href="{{ route('frontend.design.detail', $item->id) }}" class="card-hover bg-white rounded-2xl overflow-hidden group">
                @if($item->design_img)
                    <div class="aspect-[4/3] overflow-hidden">
                        <img src="{{ asset('storage/' . $item->design_img) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                @else
                    <div class="aspect-[4/3] bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center"><i class="fas fa-swatchbook text-gray-600 text-2xl"></i></div>
                @endif
                <div class="p-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-swatchbook text-green-500 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-800 text-sm truncate group-hover:text-primary-600 transition-colors">{{ $item->name }}</h3>
                        <p class="text-xs text-gray-400 mt-0.5">₹{{ number_format($item->design_price) }}</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-300 text-xs group-hover:text-primary-500 group-hover:translate-x-1 transition-all"></i>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection
