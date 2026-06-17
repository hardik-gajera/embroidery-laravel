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
            <!-- Logo Section -->
            @if($appSettings['logo'])
                <div class="mb-8 animate-scale-in">
                    <div class="relative">
                        <div class="absolute inset-0 bg-white/20 rounded-3xl blur-xl"></div>
                        <img src="{{ asset('storage/' . $appSettings['logo']) }}" alt="{{ $appSettings['company_name'] }}" class="relative w-32 h-32 mx-auto object-contain bg-white/90 backdrop-blur-sm p-4 rounded-3xl shadow-2xl shadow-black/30 hover:scale-105 transition-transform duration-500">
                    </div>
                </div>
            @else
                <div class="mb-8 animate-scale-in">
                    <div class="relative">
                        <div class="absolute inset-0 bg-white/20 rounded-3xl blur-xl"></div>
                        <div class="relative w-32 h-32 mx-auto bg-gradient-to-br from-primary-500 to-accent-500 rounded-3xl flex items-center justify-center text-white text-3xl font-bold shadow-2xl shadow-black/30 hover:scale-105 transition-transform duration-500">
                            <i class="fas fa-palette"></i>
                        </div>
                    </div>
                </div>
            @endif
            
            <h1 class="text-4xl md:text-6xl font-heading font-bold text-white mb-5 leading-tight">
                {{ explode(' ', $appSettings['company_name'])[0] ?? 'Aaradhya' }}<br><span class="bg-gradient-to-r from-primary-200 to-accent-300 bg-clip-text text-transparent">{{ implode(' ', array_slice(explode(' ', $appSettings['company_name']), 1)) ?: 'Design Gallery' }}</span>
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

<!-- Latest Designs -->
<section id="latest-designs" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 scroll-reveal">
    <div class="text-center mb-12">
        <span class="inline-block px-3 py-1 bg-green-50 text-green-600 text-xs font-semibold rounded-full uppercase tracking-wider mb-3">New Arrivals</span>
        <h2 class="text-3xl font-heading font-bold text-gray-800">Latest Designs</h2>
        <p class="text-gray-500 text-sm mt-2">Freshly added embroidery patterns for your next project</p>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
        @foreach($featuredDesigns as $design)
        <div class="card-hover bg-white rounded-2xl overflow-hidden group relative">
            @if($design->design_img)
                <div class="aspect-[4/3] overflow-hidden cursor-pointer" onclick="openHomeGallery({{ $loop->index }})">
                    <img src="{{ asset('storage/' . $design->design_img) }}" alt="{{ $design->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
            @else
                <div class="aspect-[4/3] bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center cursor-pointer" onclick="openHomeGallery({{ $loop->index }})">
                    <i class="fas fa-swatchbook text-gray-600 text-3xl group-hover:scale-110 transition-transform"></i>
                </div>
            @endif
            
            <!-- Quick Action Buttons -->
            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <button onclick="openHomeGallery({{ $loop->index }})" 
                    class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-gray-600 hover:text-primary-600 transition-colors shadow-md mb-2" title="View Gallery">
                    <i class="fas fa-images text-xs"></i>
                </button>
                <a href="{{ route('frontend.design.detail', $design->id) }}" 
                    class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-gray-600 hover:text-primary-600 transition-colors shadow-md mb-2" title="View Details">
                    <i class="fas fa-eye text-xs"></i>
                </a>
            </div>
            
            <div class="p-4">
                <a href="{{ route('frontend.design.detail', $design->id) }}">
                    <h3 class="font-semibold text-gray-800 text-sm truncate group-hover:text-primary-600 transition-colors">{{ $design->name }}</h3>
                </a>
                <p class="text-xs text-gray-400 mt-1">₹{{ number_format($design->design_price) }}</p>
                @if($design->design_code)
                    <p class="text-[10px] text-primary-500 font-medium">{{ $design->design_code }}</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    <div class="text-center mt-10">
        <a href="{{ route('frontend.all-designs') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-primary-600 text-white rounded-xl font-semibold hover:bg-primary-700 transition-all shadow-lg shadow-primary-200 hover:shadow-primary-300 hover:scale-105">
            <i class="fas fa-th-large"></i> View All Designs
        </a>
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

<!-- Image Gallery Popup -->
<div id="homeGalleryModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80">
    <div class="relative max-w-5xl max-h-[90vh] w-full mx-4">
        <!-- Close Button -->
        <button onclick="closeHomeGallery()" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors z-10">
            <i class="fas fa-times text-2xl"></i>
        </button>
        
        <!-- Main Image Container with Zoom -->
        <div class="relative bg-white rounded-2xl overflow-hidden shadow-2xl">
            <!-- Image Container with Zoom and Pan -->
            <div class="aspect-[4/3] bg-gray-100 flex items-center justify-center overflow-hidden relative">
                <div id="imageZoomContainer" class="w-full h-full flex items-center justify-center cursor-grab active:cursor-grabbing">
                    <img id="homeGalleryImage" src="" alt="Design" class="max-w-full max-h-full object-contain transition-transform duration-300 cursor-zoom-in select-none">
                    <div id="homeNoImagePlaceholder" class="hidden text-gray-400 text-center">
                        <i class="fas fa-image text-6xl mb-4 block"></i>
                        <p class="text-lg font-medium">No Image Available</p>
                    </div>
                </div>
            </div>
            
            <!-- Zoom Controls -->
            <div class="absolute top-4 left-4 flex flex-col gap-2">
                <button onclick="zoomIn()" class="w-10 h-10 bg-white/90 rounded-full flex items-center justify-center text-gray-700 hover:bg-white hover:text-primary-600 transition-all shadow-lg" title="Zoom In">
                    <i class="fas fa-search-plus text-sm"></i>
                </button>
                <button onclick="zoomOut()" class="w-10 h-10 bg-white/90 rounded-full flex items-center justify-center text-gray-700 hover:bg-white hover:text-primary-600 transition-all shadow-lg" title="Zoom Out">
                    <i class="fas fa-search-minus text-sm"></i>
                </button>
                <button onclick="resetZoom()" class="w-10 h-10 bg-white/90 rounded-full flex items-center justify-center text-gray-700 hover:bg-white hover:text-primary-600 transition-all shadow-lg" title="Reset Zoom">
                    <i class="fas fa-expand-arrows-alt text-sm"></i>
                </button>
            </div>
            
            <!-- Navigation Arrows -->
            <button onclick="prevHomeImage()" class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 rounded-full flex items-center justify-center text-gray-700 hover:bg-white hover:text-primary-600 transition-all shadow-lg" title="Previous">
                <i class="fas fa-chevron-left text-lg"></i>
            </button>
            <button onclick="nextHomeImage()" class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 rounded-full flex items-center justify-center text-gray-700 hover:bg-white hover:text-primary-600 transition-all shadow-lg" title="Next">
                <i class="fas fa-chevron-right text-lg"></i>
            </button>
            
            <!-- Design Info Overlay -->
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-6 text-white">
                <div class="flex items-end justify-between">
                    <div class="flex-1">
                        <h3 id="homeGalleryTitle" class="text-xl font-heading font-bold mb-2"></h3>
                        <div class="flex items-center gap-4 text-sm">
                            <span id="homeGalleryCode" class="bg-white/20 px-3 py-1 rounded-full font-mono"></span>
                            <span id="homeGalleryPrice" class="bg-primary-600 px-3 py-1 rounded-full font-semibold"></span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 ml-4">
                        <span id="homeGalleryCounter" class="text-sm bg-white/20 px-3 py-1 rounded-full"></span>
                        <a id="homeGalleryViewButton" href="#" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-semibold transition-all hover:scale-105">
                            <i class="fas fa-external-link-alt text-sm"></i>Go to Design
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Thumbnails Strip -->
        <div class="mt-4 max-h-24 overflow-hidden">
            <div id="homeThumbnailStrip" class="flex gap-2 justify-center">
                <!-- Thumbnails will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
// Home Gallery functionality
let currentHomeDesigns = [];
let currentHomeIndex = 0;
let currentZoom = 1;
let panX = 0;
let panY = 0;
let isPanning = false;
let startX = 0;
let startY = 0;

// Populate home designs array from server data
const homeDesigns = [
    @foreach($featuredDesigns as $design)
    {
        id: {{ $design->id }},
        name: @json($design->name),
        code: @json($design->design_code),
        price: {{ $design->design_price }},
        image: @json($design->design_img ? asset('storage/' . $design->design_img) : ''),
        url: @json(route('frontend.design.detail', $design->id))
    }@if(!$loop->last),@endif
    @endforeach
];

function openHomeGallery(index) {
    currentHomeDesigns = homeDesigns;
    currentHomeIndex = index;
    currentZoom = 1;
    panX = 0;
    panY = 0;
    const modal = document.getElementById('homeGalleryModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    updateHomeGalleryContent();
    generateHomeThumbnails();
    setupHomePanEvents();
    document.body.style.overflow = 'hidden';
}

function closeHomeGallery() {
    const modal = document.getElementById('homeGalleryModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
    resetZoom();
}

function nextHomeImage() {
    currentHomeIndex = (currentHomeIndex + 1) % currentHomeDesigns.length;
    updateHomeGalleryContent();
    updateHomeActiveThumbnail();
    resetZoom();
}

function prevHomeImage() {
    currentHomeIndex = (currentHomeIndex - 1 + currentHomeDesigns.length) % currentHomeDesigns.length;
    updateHomeGalleryContent();
    updateHomeActiveThumbnail();
    resetZoom();
}

function zoomIn() {
    currentZoom = Math.min(currentZoom * 1.2, 3);
    applyZoom();
}

function zoomOut() {
    currentZoom = Math.max(currentZoom / 1.2, 0.5);
    applyZoom();
}

function resetZoom() {
    currentZoom = 1;
    panX = 0;
    panY = 0;
    applyZoom();
}

function applyZoom() {
    const image = document.getElementById('homeGalleryImage');
    const container = document.getElementById('imageZoomContainer');
    
    image.style.transform = `scale(${currentZoom}) translate(${panX}px, ${panY}px)`;
    
    if (currentZoom > 1) {
        image.style.cursor = 'grab';
        container.style.cursor = 'grab';
    } else {
        image.style.cursor = 'zoom-in';
        container.style.cursor = 'zoom-in';
        panX = 0;
        panY = 0;
    }
}

function setupHomePanEvents() {
    const container = document.getElementById('imageZoomContainer');
    const image = document.getElementById('homeGalleryImage');
    
    // Mouse events
    container.addEventListener('mousedown', startHomePan);
    document.addEventListener('mousemove', doHomePan);
    document.addEventListener('mouseup', endHomePan);
    
    // Touch events for mobile
    container.addEventListener('touchstart', startHomePanTouch, { passive: false });
    document.addEventListener('touchmove', doHomePanTouch, { passive: false });
    document.addEventListener('touchend', endHomePan);
}

function startHomePan(e) {
    if (currentZoom <= 1) return;
    
    isPanning = true;
    startX = e.clientX - panX;
    startY = e.clientY - panY;
    
    const container = document.getElementById('imageZoomContainer');
    const image = document.getElementById('homeGalleryImage');
    container.style.cursor = 'grabbing';
    image.style.cursor = 'grabbing';
    
    e.preventDefault();
}

function startHomePanTouch(e) {
    if (currentZoom <= 1 || e.touches.length !== 1) return;
    
    isPanning = true;
    const touch = e.touches[0];
    startX = touch.clientX - panX;
    startY = touch.clientY - panY;
    
    e.preventDefault();
}

function doHomePan(e) {
    if (!isPanning || currentZoom <= 1) return;
    
    const newPanX = e.clientX - startX;
    const newPanY = e.clientY - startY;
    
    // Limit panning to keep image within reasonable bounds
    const maxPan = 200 * currentZoom;
    panX = Math.max(-maxPan, Math.min(maxPan, newPanX));
    panY = Math.max(-maxPan, Math.min(maxPan, newPanY));
    
    applyZoom();
    e.preventDefault();
}

function doHomePanTouch(e) {
    if (!isPanning || currentZoom <= 1 || e.touches.length !== 1) return;
    
    const touch = e.touches[0];
    const newPanX = touch.clientX - startX;
    const newPanY = touch.clientY - startY;
    
    // Limit panning to keep image within reasonable bounds
    const maxPan = 200 * currentZoom;
    panX = Math.max(-maxPan, Math.min(maxPan, newPanX));
    panY = Math.max(-maxPan, Math.min(maxPan, newPanY));
    
    applyZoom();
    e.preventDefault();
}

function endHomePan() {
    isPanning = false;
    
    if (currentZoom > 1) {
        const container = document.getElementById('imageZoomContainer');
        const image = document.getElementById('homeGalleryImage');
        container.style.cursor = 'grab';
        image.style.cursor = 'grab';
    }
}

function updateHomeGalleryContent() {
    const design = currentHomeDesigns[currentHomeIndex];
    const galleryImage = document.getElementById('homeGalleryImage');
    const noImagePlaceholder = document.getElementById('homeNoImagePlaceholder');
    
    if (design.image) {
        galleryImage.src = design.image;
        galleryImage.classList.remove('hidden');
        noImagePlaceholder.classList.add('hidden');
    } else {
        galleryImage.classList.add('hidden');
        noImagePlaceholder.classList.remove('hidden');
    }
    
    document.getElementById('homeGalleryTitle').textContent = design.name;
    document.getElementById('homeGalleryCode').textContent = design.code || 'No Code';
    document.getElementById('homeGalleryPrice').textContent = '₹' + new Intl.NumberFormat().format(design.price);
    document.getElementById('homeGalleryCounter').textContent = `${currentHomeIndex + 1} of ${currentHomeDesigns.length}`;
    document.getElementById('homeGalleryViewButton').href = design.url;
}

function generateHomeThumbnails() {
    const thumbnailStrip = document.getElementById('homeThumbnailStrip');
    thumbnailStrip.innerHTML = '';
    
    currentHomeDesigns.forEach((design, index) => {
        const thumb = document.createElement('div');
        thumb.className = `w-16 h-16 rounded-lg overflow-hidden cursor-pointer border-2 transition-all ${
            index === currentHomeIndex ? 'border-primary-500 ring-2 ring-primary-200' : 'border-transparent hover:border-gray-300'
        }`;
        thumb.onclick = () => {
            currentHomeIndex = index;
            updateHomeGalleryContent();
            updateHomeActiveThumbnail();
            resetZoom();
        };
        
        if (design.image) {
            thumb.innerHTML = `<img src="${design.image}" alt="${design.name}" class="w-full h-full object-cover">`;
        } else {
            thumb.innerHTML = `<div class="w-full h-full bg-gray-200 flex items-center justify-center"><i class="fas fa-image text-gray-400"></i></div>`;
        }
        
        thumbnailStrip.appendChild(thumb);
    });
}

function updateHomeActiveThumbnail() {
    const thumbnails = document.querySelectorAll('#homeThumbnailStrip > div');
    thumbnails.forEach((thumb, index) => {
        if (index === currentHomeIndex) {
            thumb.className = thumb.className.replace('border-transparent hover:border-gray-300', 'border-primary-500 ring-2 ring-primary-200');
        } else {
            thumb.className = thumb.className.replace('border-primary-500 ring-2 ring-primary-200', 'border-transparent hover:border-gray-300');
        }
    });
}

// Mouse wheel zoom for home gallery
document.getElementById('imageZoomContainer').addEventListener('wheel', function(e) {
    e.preventDefault();
    if (e.deltaY < 0) {
        zoomIn();
    } else {
        zoomOut();
    }
});

// Click to zoom for home gallery
document.getElementById('homeGalleryImage').addEventListener('click', function() {
    if (currentZoom < 3) {
        zoomIn();
    } else {
        resetZoom();
    }
});

// Close modal on escape key (enhanced for home gallery)
document.addEventListener('keydown', function(e) {
    const homeModal = document.getElementById('homeGalleryModal');
    if (!homeModal.classList.contains('hidden')) {
        if (e.key === 'Escape') {
            closeHomeGallery();
        }
        if (e.key === 'ArrowRight') {
            nextHomeImage();
        }
        if (e.key === 'ArrowLeft') {
            prevHomeImage();
        }
        if (e.key === '=' || e.key === '+') {
            zoomIn();
        }
        if (e.key === '-') {
            zoomOut();
        }
        if (e.key === '0') {
            resetZoom();
        }
    }
});

// Close modal on outside click (home gallery)
document.getElementById('homeGalleryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeHomeGallery();
    }
});
</script>
@endsection
