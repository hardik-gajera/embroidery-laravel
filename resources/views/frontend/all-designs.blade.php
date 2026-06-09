@extends('frontend.layout')
@section('title', 'All Designs')

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-br from-primary-900 via-primary-800 to-purple-900 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-heading font-bold text-white mb-4">All Designs</h1>
        <p class="text-primary-200 text-lg">Explore our complete collection of premium embroidery designs</p>
    </div>
</section>

<!-- Search & Filter Section -->
<section class="bg-white py-8 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="GET" action="{{ route('frontend.all-designs') }}" class="flex flex-col md:flex-row gap-4 items-center">
            <!-- Search Input -->
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by design code or name..."
                        class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-primary-500 focus:bg-white transition-all">
                </div>
            </div>

            <!-- Category Filter -->
            <div class="w-full md:w-48">
                <select name="category" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-primary-500 transition-all">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
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
            @if(request()->hasAny(['search', 'category', 'sort']))
                <a href="{{ route('frontend.all-designs') }}" class="px-4 py-3 text-gray-600 hover:text-primary-600 transition-colors">
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
                        Search Results for "{{ request('search') }}"
                    @else
                        All Designs
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
                            <a href="{{ route('frontend.design.detail', $design->id) }}" 
                                class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-gray-600 hover:text-primary-600 transition-colors shadow-md mb-2">
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
                <p class="text-gray-500 mb-6">Try adjusting your search criteria or browse our categories.</p>
                <a href="{{ route('frontend.all-designs') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white rounded-xl font-semibold hover:bg-primary-700 transition-all">
                    <i class="fas fa-refresh"></i>View All Designs
                </a>
            </div>
        @endif
    </div>
</section>

<script>
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