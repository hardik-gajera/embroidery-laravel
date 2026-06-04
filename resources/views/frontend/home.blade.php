@extends('frontend.layout')
@section('title', 'Home')

@section('content')
<!-- Hero Banner -->
<section class="relative overflow-hidden bg-gradient-to-br from-primary-900 via-primary-800 to-purple-900 min-h-[500px] flex items-center">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0">
        <div class="absolute top-10 right-10 w-72 h-72 bg-primary-500/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-10 left-10 w-96 h-96 bg-accent-500/15 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-primary-400/10 rounded-full blur-3xl"></div>
        <!-- Floating shapes -->
        <div class="absolute top-20 left-[20%] w-4 h-4 bg-white/10 rounded-full" style="animation: float 6s ease-in-out infinite;"></div>
        <div class="absolute top-40 right-[30%] w-3 h-3 bg-white/10 rounded-full" style="animation: float 4s ease-in-out infinite; animation-delay: 1s;"></div>
        <div class="absolute bottom-32 left-[40%] w-5 h-5 bg-white/10 rounded-full" style="animation: float 5s ease-in-out infinite; animation-delay: 2s;"></div>
    </div>
    <style>@keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }</style>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
        <div class="text-center animate-slide-up">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 border border-white/20 rounded-full text-sm text-white/80 mb-6 backdrop-blur-sm">
                <i class="fas fa-sparkles text-yellow-300"></i>
                <span>Premium Collection Available</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-heading font-bold text-white mb-5 leading-tight">
                Premium Embroidery<br><span class="bg-gradient-to-r from-primary-200 to-accent-300 bg-clip-text text-transparent">Designs</span>
            </h1>
            <p class="text-lg text-primary-200/80 max-w-2xl mx-auto mb-10">Discover thousands of beautiful embroidery patterns crafted by professionals. Download instantly and start creating masterpieces.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#categories" class="btn-glow inline-flex items-center gap-2 px-8 py-3.5 bg-white text-primary-700 rounded-xl font-semibold hover:bg-gray-50 transition-all shadow-xl shadow-black/10 hover:scale-105">
                    <i class="fas fa-th-large"></i> Browse Categories
                </a>
                <a href="{{ route('frontend.packages') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-white/10 border border-white/20 text-white rounded-xl font-semibold hover:bg-white/20 transition-all backdrop-blur-sm hover:scale-105">
                    <i class="fas fa-box-open"></i> View Packages
                </a>
            </div>
        </div>
    </div>

    <!-- Wave Divider -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 80h1440V30c-240 30-480 50-720 30S240 0 0 30v50z" fill="#f9fafb"/></svg>
    </div>
</section>

<!-- Features -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 scroll-reveal">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="card-hover bg-white rounded-2xl p-8 text-center">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-inner">
                <i class="fas fa-download text-primary-600 text-xl"></i>
            </div>
            <h3 class="font-heading font-semibold text-gray-800 text-lg mb-2">Instant Download</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Get your designs immediately after purchase. No waiting, no delays.</p>
        </div>
        <div class="card-hover bg-white rounded-2xl p-8 text-center">
            <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-inner">
                <i class="fas fa-gem text-green-600 text-xl"></i>
            </div>
            <h3 class="font-heading font-semibold text-gray-800 text-lg mb-2">Premium Quality</h3>
            <p class="text-sm text-gray-500 leading-relaxed">High-quality EMB files crafted by professionals, ready for production.</p>
        </div>
        <div class="card-hover bg-white rounded-2xl p-8 text-center">
            <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-accent-100 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-inner">
                <i class="fas fa-shield-alt text-purple-600 text-xl"></i>
            </div>
            <h3 class="font-heading font-semibold text-gray-800 text-lg mb-2">Secure Payment</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Safe & secure payments via Razorpay. Your data is always protected.</p>
        </div>
    </div>
</section>

<!-- Parent Categories -->
<section id="categories" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 scroll-reveal">
    <div class="text-center mb-12">
        <span class="inline-block px-3 py-1 bg-primary-50 text-primary-600 text-xs font-semibold rounded-full uppercase tracking-wider mb-3">Collections</span>
        <h2 class="text-3xl font-heading font-bold text-gray-800">Design Categories</h2>
        <p class="text-gray-500 text-sm mt-2 max-w-md mx-auto">Browse our curated collection of embroidery designs organized by category</p>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
        @foreach($parentCategories as $category)
        <a href="{{ route('frontend.categories', $category->id) }}" class="group card-hover bg-white rounded-2xl overflow-hidden">
            @if($category->image)
                <div class="aspect-[4/3] overflow-hidden">
                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
            @else
                <div class="aspect-[4/3] bg-gradient-to-br from-primary-900 to-primary-800 flex items-center justify-center">
                    <i class="fas fa-folder text-primary-400 text-4xl group-hover:scale-110 transition-transform"></i>
                </div>
            @endif
            <div class="p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-th-large text-primary-500 text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-800 text-sm truncate group-hover:text-primary-600 transition-colors">{{ $category->name }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        @if($category->children_count > 0)
                            {{ $category->children_count }} sub-categories
                        @else
                            Browse designs
                        @endif
                    </p>
                </div>
                <i class="fas fa-chevron-right text-gray-300 text-xs group-hover:text-primary-500 group-hover:translate-x-1 transition-all"></i>
            </div>
        </a>
        @endforeach
    </div>
    @if($parentCategories->isEmpty())
        <div class="text-center py-16">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-folder-open text-gray-300 text-2xl"></i>
            </div>
            <p class="text-gray-400 font-medium">No categories available yet.</p>
        </div>
    @endif
</section>

<!-- Featured Designs -->
<section id="designs" class="bg-white py-16 scroll-reveal">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="inline-block px-3 py-1 bg-green-50 text-green-600 text-xs font-semibold rounded-full uppercase tracking-wider mb-3">New Arrivals</span>
            <h2 class="text-3xl font-heading font-bold text-gray-800">Latest Designs</h2>
            <p class="text-gray-500 text-sm mt-2">Freshly added embroidery patterns for your next project</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
            @foreach($featuredDesigns as $design)
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
</section>

<!-- Packages Section -->
<section id="packages" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 scroll-reveal">
    <div class="text-center mb-12">
        <span class="inline-block px-3 py-1 bg-accent-50 text-accent-600 text-xs font-semibold rounded-full uppercase tracking-wider mb-3">Best Value</span>
        <h2 class="text-3xl font-heading font-bold text-gray-800">Design Packages</h2>
        <p class="text-gray-500 text-sm mt-2">Get more designs at discounted prices with our curated packages</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($packages as $package)
        <a href="{{ route('frontend.package.detail', $package->id) }}" class="card-hover group bg-white rounded-2xl overflow-hidden relative">
            @if($loop->first)
            <div class="absolute top-4 right-4 z-10 px-3 py-1.5 bg-gradient-to-r from-amber-400 to-orange-400 text-white text-[10px] font-bold rounded-full uppercase shadow-lg shadow-amber-200">🔥 Popular</div>
            @endif
            @if($package->package_img)
                <div class="aspect-[4/3] overflow-hidden">
                    <img src="{{ asset('storage/' . $package->package_img) }}" alt="{{ $package->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
            @else
                <div class="aspect-[4/3] bg-gradient-to-br from-purple-900 to-primary-900 flex items-center justify-center">
                    <i class="fas fa-box-open text-purple-400 text-4xl group-hover:scale-110 group-hover:rotate-3 transition-all"></i>
                </div>
            @endif
            <div class="p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-accent-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-box-open text-accent-500 text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-800 text-sm truncate group-hover:text-primary-600 transition-colors">{{ $package->name }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $package->number_of_design }} designs · {{ $package->time_period }}mo · ₹{{ number_format($package->price) }}</p>
                </div>
                <i class="fas fa-chevron-right text-gray-300 text-xs group-hover:text-primary-500 group-hover:translate-x-1 transition-all"></i>
            </div>
        </a>
        @endforeach
    </div>
    @if($packages->isNotEmpty())
        <div class="text-center mt-8">
            <a href="{{ route('frontend.packages') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-50 text-primary-600 rounded-xl font-semibold hover:bg-primary-100 transition-all hover:scale-105">
                View All Packages <i class="fas fa-arrow-right text-sm"></i>
            </a>
        </div>
    @endif
</section>

<!-- CTA Section -->
<section class="scroll-reveal">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="relative bg-gradient-to-r from-primary-600 to-accent-600 rounded-3xl p-10 md:p-16 text-center overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full translate-y-1/2 -translate-x-1/2"></div>
            </div>
            <div class="relative">
                <h2 class="text-2xl md:text-3xl font-heading font-bold text-white mb-4">Ready to Start Creating?</h2>
                <p class="text-primary-100 max-w-lg mx-auto mb-8">Join thousands of embroidery enthusiasts. Get access to premium designs and start your creative journey today.</p>
                @if(!session('customer_id'))
                <a href="{{ route('frontend.login') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-white text-primary-700 rounded-xl font-semibold hover:bg-gray-50 transition-all shadow-xl hover:scale-105">
                    <i class="fas fa-rocket"></i> Get Started Free
                </a>
                @else
                <a href="#designs" class="inline-flex items-center gap-2 px-8 py-3.5 bg-white text-primary-700 rounded-xl font-semibold hover:bg-gray-50 transition-all shadow-xl hover:scale-105">
                    <i class="fas fa-shopping-bag"></i> Browse Designs
                </a>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
