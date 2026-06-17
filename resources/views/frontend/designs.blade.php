@extends('frontend.layout')
@section('title', $category->name . ' Designs')

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-br from-primary-900 via-primary-800 to-purple-900 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-primary-200 mb-6">
            <a href="{{ route('home') }}" class="hover:text-white transition"><i class="fas fa-home"></i></a>
            <i class="fas fa-chevron-right text-[10px] text-primary-300"></i>
            @if($category->parent)
                <a href="{{ route('frontend.categories', $category->parent->id) }}" class="hover:text-white transition">{{ $category->parent->name }}</a>
                <i class="fas fa-chevron-right text-[10px] text-primary-300"></i>
            @endif
            <span class="text-white font-medium">{{ $category->name }}</span>
        </div>
        
        <div class="text-center">
            <div class="flex items-center justify-center gap-4 mb-4">
                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-swatchbook text-white text-2xl"></i>
                </div>
            </div>
            <h1 class="text-4xl font-heading font-bold text-white mb-4">{{ $category->name }}</h1>
            <p class="text-primary-200 text-lg">Explore {{ $designs->total() }} premium embroidery designs in this category</p>
        </div>
    </div>
</section>

<!-- Search & Filter Section -->
<section class="bg-white py-8 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="GET" action="{{ route('frontend.designs', $category->id) }}" class="flex flex-col md:flex-row gap-4 items-center">
            <!-- Search Input -->
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by design code or name..."
                        class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-primary-500 focus:bg-white transition-all">
                </div>
            </div>

            <!-- Sort Filter -->
            <div class="w-full md:w-48">
                <select name="sort" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-primary-500 transition-all">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest First</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                </select>
            </div>

            <!-- Search Button -->
            <button type="submit" class="px-8 py-3 bg-primary-600 text-white rounded-xl font-semibold hover:bg-primary-700 transition-all hover:scale-105">
                <i class="fas fa-search mr-2"></i>Search
            </button>

            <!-- Clear Filters -->
            @if(request()->hasAny(['search', 'sort']))
                <a href="{{ route('frontend.designs', $category->id) }}" class="px-4 py-3 text-gray-600 hover:text-primary-600 transition-colors">
                    <i class="fas fa-times mr-1"></i>Clear
                </a>
            @endif
        </form>
    </div>
</section>

<!-- Results Section -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Results Info -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h2 class="text-2xl font-heading font-bold text-gray-800">
                    @if(request('search'))
                        Search Results for "{{ request('search') }}" in {{ $category->name }}
                    @else
                        {{ $category->name }} Designs
                    @endif
                </h2>
                <p class="text-gray-500 mt-1">{{ $designs->total() }} designs found</p>
            </div>
            
            <!-- View Toggle -->
            <div class="flex items-center gap-2 mt-4 md:mt-0">
                <span class="text-sm text-gray-600">View:</span>
                <div class="flex rounded-lg border border-gray-200 overflow-hidden">
                    <button onclick="setView('grid')" id="grid-btn" class="px-3 py-1.5 text-sm bg-primary-600 text-white hover:bg-primary-700 transition-colors">
                        <i class="fas fa-th"></i>
                    </button>
                    <button onclick="setView('list')" id="list-btn" class="px-3 py-1.5 text-sm bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
        </div>

        @if($designs->count() > 0)
            <!-- Designs Grid -->
            <div id="designs-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @foreach($designs as $design)
                <div class="design-card card-hover bg-white rounded-2xl overflow-hidden group flex flex-col h-full">
                    <!-- Design Image -->
                    <div class="relative">
                        @if($design->design_img)
                            <div class="aspect-[4/3] overflow-hidden">
                                <img src="{{ asset('storage/' . $design->design_img) }}" alt="{{ $design->name }}" 
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                        @else
                            <div class="aspect-[4/3] bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center">
                                <i class="fas fa-swatchbook text-gray-600 text-3xl group-hover:scale-110 transition-transform"></i>
                            </div>
                        @endif
                        
                        <!-- Quick Action Buttons -->
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="openGallery({{ $loop->index }})" 
                                class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-gray-600 hover:text-primary-600 transition-colors shadow-md mb-2" title="View Gallery">
                                <i class="fas fa-images text-xs"></i>
                            </button>
                            <a href="{{ route('frontend.design.detail', $design->id) }}" 
                                class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-gray-600 hover:text-primary-600 transition-colors shadow-md mb-2" title="View Details">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Design Info - Flex grow to push buttons to bottom -->
                    <div class="p-4 flex flex-col flex-grow">
                        <a href="{{ route('frontend.design.detail', $design->id) }}" class="block">
                            <h3 class="font-semibold text-gray-800 text-sm truncate group-hover:text-primary-600 transition-colors">
                                {{ $design->name }}
                            </h3>
                        </a>
                        
                        @if($design->design_code)
                            <p class="text-xs text-primary-500 font-medium mt-1">{{ $design->design_code }}</p>
                        @endif
                        
                        <p class="text-sm font-bold text-gray-800 mt-2">₹{{ number_format($design->design_price) }}</p>
                        
                        @if($design->category)
                            <p class="text-xs text-gray-400 mb-3">{{ $design->category->name }}</p>
                        @else
                            <div class="mb-3"></div>
                        @endif

                        <!-- Action Buttons - Always at bottom -->
                        <div class="flex gap-2 mt-auto">
                            @if(session('customer_id'))
                                <!-- Buy Now Button -->
                                <form action="{{ route('frontend.buy') }}" method="POST" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="design_id" value="{{ $design->id }}">
                                    <button type="submit" class="w-full px-3 py-2 bg-primary-600 text-white text-xs font-semibold rounded-lg hover:bg-primary-700 transition-all hover:scale-105">
                                        <i class="fas fa-bolt mr-1"></i>Buy Now
                                    </button>
                                </form>

                                <!-- Add to Cart Button -->
                                <form action="{{ route('frontend.cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="design_id" value="{{ $design->id }}">
                                    <button type="submit" class="px-3 py-2 bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition-all text-xs">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('frontend.login') }}" class="flex-1 px-3 py-2 bg-primary-600 text-white text-xs font-semibold rounded-lg hover:bg-primary-700 transition-all hover:scale-105 text-center flex items-center justify-center">
                                    <i class="fas fa-sign-in-alt mr-1"></i>Login to Buy
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $designs->appends(request()->query())->links('pagination::tailwind') }}
            </div>
        @else
            <!-- No Results -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-search text-gray-300 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No designs found</h3>
                <p class="text-gray-500 mb-6">Try adjusting your search criteria or browse other categories.</p>
                <div class="flex gap-3 justify-center">
                    <a href="{{ route('frontend.designs', $category->id) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white rounded-xl font-semibold hover:bg-primary-700 transition-all">
                        <i class="fas fa-refresh"></i>View All in {{ $category->name }}
                    </a>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all">
                        <i class="fas fa-arrow-left"></i>Back to Home
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Image Gallery Popup -->
<div id="galleryModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80">
    <div class="relative max-w-5xl max-h-[90vh] w-full mx-4">
        <!-- Close Button -->
        <button onclick="closeGallery()" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors z-10">
            <i class="fas fa-times text-2xl"></i>
        </button>
        
        <!-- Main Image Container -->
        <div class="relative bg-white rounded-2xl overflow-hidden shadow-2xl">
            <!-- Image Container with Zoom and Pan -->
            <div class="aspect-[4/3] bg-gray-100 flex items-center justify-center overflow-hidden relative">
                <div id="imageZoomContainer" class="w-full h-full flex items-center justify-center cursor-grab active:cursor-grabbing">
                    <img id="galleryImage" src="" alt="Design" class="max-w-full max-h-full object-contain transition-transform duration-300 cursor-zoom-in select-none">
                    <div id="noImagePlaceholder" class="hidden text-gray-400 text-center">
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
            <button onclick="prevImage()" class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 rounded-full flex items-center justify-center text-gray-700 hover:bg-white hover:text-primary-600 transition-all shadow-lg" title="Previous">
                <i class="fas fa-chevron-left text-lg"></i>
            </button>
            <button onclick="nextImage()" class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 rounded-full flex items-center justify-center text-gray-700 hover:bg-white hover:text-primary-600 transition-all shadow-lg" title="Next">
                <i class="fas fa-chevron-right text-lg"></i>
            </button>
            
            <!-- Design Info Overlay -->
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-6 text-white">
                <div class="flex items-end justify-between">
                    <div class="flex-1">
                        <h3 id="galleryTitle" class="text-xl font-heading font-bold mb-2"></h3>
                        <div class="flex items-center gap-4 text-sm">
                            <span id="galleryCode" class="bg-white/20 px-3 py-1 rounded-full font-mono"></span>
                            <span id="galleryPrice" class="bg-primary-600 px-3 py-1 rounded-full font-semibold"></span>
                            <span id="galleryCategory" class="bg-white/20 px-3 py-1 rounded-full"></span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 ml-4">
                        <span id="galleryCounter" class="text-sm bg-white/20 px-3 py-1 rounded-full"></span>
                        <a id="galleryViewButton" href="#" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-semibold transition-all hover:scale-105">
                            <i class="fas fa-external-link-alt text-sm"></i>Go to Design
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Thumbnails Strip -->
        <div class="mt-4 max-h-24 overflow-hidden">
            <div id="thumbnailStrip" class="flex gap-2 justify-center">
                <!-- Thumbnails will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
// Gallery functionality
let currentDesigns = [];
let currentIndex = 0;
let currentZoom = 1;
let panX = 0;
let panY = 0;
let isPanning = false;
let startX = 0;
let startY = 0;

// Populate designs array from server data
const designs = [
    @foreach($designs as $design)
    {
        id: {{ $design->id }},
        name: @json($design->name),
        code: @json($design->design_code),
        price: {{ $design->design_price }},
        category: @json($design->category ? $design->category->name : ''),
        image: @json($design->design_img ? asset('storage/' . $design->design_img) : ''),
        url: @json(route('frontend.design.detail', $design->id))
    }@if(!$loop->last),@endif
    @endforeach
];

function openGallery(index) {
    currentDesigns = designs;
    currentIndex = index;
    currentZoom = 1;
    panX = 0;
    panY = 0;
    const modal = document.getElementById('galleryModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    updateGalleryContent();
    generateThumbnails();
    setupPanEvents();
    document.body.style.overflow = 'hidden';
}

function closeGallery() {
    const modal = document.getElementById('galleryModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
    resetZoom();
}

function nextImage() {
    currentIndex = (currentIndex + 1) % currentDesigns.length;
    updateGalleryContent();
    updateActiveThumbnail();
    resetZoom();
}

function prevImage() {
    currentIndex = (currentIndex - 1 + currentDesigns.length) % currentDesigns.length;
    updateGalleryContent();
    updateActiveThumbnail();
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
    const image = document.getElementById('galleryImage');
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

function updateGalleryContent() {
    const design = currentDesigns[currentIndex];
    const galleryImage = document.getElementById('galleryImage');
    const noImagePlaceholder = document.getElementById('noImagePlaceholder');
    
    if (design.image) {
        galleryImage.src = design.image;
        galleryImage.classList.remove('hidden');
        noImagePlaceholder.classList.add('hidden');
    } else {
        galleryImage.classList.add('hidden');
        noImagePlaceholder.classList.remove('hidden');
    }
    
    document.getElementById('galleryTitle').textContent = design.name;
    document.getElementById('galleryCode').textContent = design.code || 'No Code';
    document.getElementById('galleryPrice').textContent = '₹' + new Intl.NumberFormat().format(design.price);
    document.getElementById('galleryCategory').textContent = design.category || 'No Category';
    document.getElementById('galleryCounter').textContent = `${currentIndex + 1} of ${currentDesigns.length}`;
    document.getElementById('galleryViewButton').href = design.url;
}

function generateThumbnails() {
    const thumbnailStrip = document.getElementById('thumbnailStrip');
    thumbnailStrip.innerHTML = '';
    
    currentDesigns.forEach((design, index) => {
        const thumb = document.createElement('div');
        thumb.className = `w-16 h-16 rounded-lg overflow-hidden cursor-pointer border-2 transition-all ${
            index === currentIndex ? 'border-primary-500 ring-2 ring-primary-200' : 'border-transparent hover:border-gray-300'
        }`;
        thumb.onclick = () => {
            currentIndex = index;
            updateGalleryContent();
            updateActiveThumbnail();
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

function updateActiveThumbnail() {
    const thumbnails = document.querySelectorAll('#thumbnailStrip > div');
    thumbnails.forEach((thumb, index) => {
        if (index === currentIndex) {
            thumb.className = thumb.className.replace('border-transparent hover:border-gray-300', 'border-primary-500 ring-2 ring-primary-200');
        } else {
            thumb.className = thumb.className.replace('border-primary-500 ring-2 ring-primary-200', 'border-transparent hover:border-gray-300');
        }
    });
}

function setupPanEvents() {
    const container = document.getElementById('imageZoomContainer');
    
    // Mouse events
    container.addEventListener('mousedown', startPan);
    document.addEventListener('mousemove', doPan);
    document.addEventListener('mouseup', endPan);
    
    // Touch events for mobile
    container.addEventListener('touchstart', startPanTouch, { passive: false });
    document.addEventListener('touchmove', doPanTouch, { passive: false });
    document.addEventListener('touchend', endPan);
}

function startPan(e) {
    if (currentZoom <= 1) return;
    
    isPanning = true;
    startX = e.clientX - panX;
    startY = e.clientY - panY;
    
    const container = document.getElementById('imageZoomContainer');
    const image = document.getElementById('galleryImage');
    container.style.cursor = 'grabbing';
    image.style.cursor = 'grabbing';
    
    e.preventDefault();
}

function startPanTouch(e) {
    if (currentZoom <= 1 || e.touches.length !== 1) return;
    
    isPanning = true;
    const touch = e.touches[0];
    startX = touch.clientX - panX;
    startY = touch.clientY - panY;
    
    e.preventDefault();
}

function doPan(e) {
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

function doPanTouch(e) {
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

function endPan() {
    isPanning = false;
    
    if (currentZoom > 1) {
        const container = document.getElementById('imageZoomContainer');
        const image = document.getElementById('galleryImage');
        container.style.cursor = 'grab';
        image.style.cursor = 'grab';
    }
}

// Mouse wheel zoom
document.addEventListener('DOMContentLoaded', function() {
    const zoomContainer = document.getElementById('imageZoomContainer');
    if (zoomContainer) {
        zoomContainer.addEventListener('wheel', function(e) {
            e.preventDefault();
            if (e.deltaY < 0) {
                zoomIn();
            } else {
                zoomOut();
            }
        });
    }
});

// Click to zoom
document.addEventListener('DOMContentLoaded', function() {
    const galleryImage = document.getElementById('galleryImage');
    if (galleryImage) {
        galleryImage.addEventListener('click', function() {
            if (currentZoom < 3) {
                zoomIn();
            } else {
                resetZoom();
            }
        });
    }
});

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('galleryModal');
    if (modal && !modal.classList.contains('hidden')) {
        if (e.key === 'Escape') {
            closeGallery();
        }
        if (e.key === 'ArrowRight') {
            nextImage();
        }
        if (e.key === 'ArrowLeft') {
            prevImage();
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

// Close modal on outside click
document.getElementById('galleryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeGallery();
    }
});

// View toggle functionality
function setView(view) {
    const container = document.getElementById('designs-container');
    const gridBtn = document.getElementById('grid-btn');
    const listBtn = document.getElementById('list-btn');
    
    if (view === 'grid') {
        container.className = 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6';
        gridBtn.className = 'px-3 py-1.5 text-sm bg-primary-600 text-white hover:bg-primary-700 transition-colors';
        listBtn.className = 'px-3 py-1.5 text-sm bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors';
        
        // Reset cards for grid view
        document.querySelectorAll('.design-card').forEach(card => {
            card.className = 'design-card card-hover bg-white rounded-2xl overflow-hidden group flex flex-col h-full';
        });
    } else {
        container.className = 'space-y-4';
        listBtn.className = 'px-3 py-1.5 text-sm bg-primary-600 text-white hover:bg-primary-700 transition-colors';
        gridBtn.className = 'px-3 py-1.5 text-sm bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors';
        
        // Modify cards for list view
        document.querySelectorAll('.design-card').forEach(card => {
            card.className = 'design-card flex bg-white rounded-2xl overflow-hidden hover:shadow-lg transition-shadow';
        });
    }
    
    localStorage.setItem('designView', view);
}

// Restore saved view preference
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('designView') || 'grid';
    setView(savedView);
});
</script>
@endsection
