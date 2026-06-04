@extends('frontend.layout')
@section('title', 'Packages')

@section('content')
<!-- Header -->
<section class="relative bg-gradient-to-br from-primary-900 via-primary-800 to-purple-900 py-16 overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute top-0 right-0 w-72 h-72 bg-primary-500/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-accent-500/15 rounded-full blur-3xl"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block px-3 py-1 bg-white/10 border border-white/20 text-white/80 text-xs font-semibold rounded-full mb-4">Best Value Plans</span>
        <h1 class="text-3xl md:text-4xl font-heading font-bold text-white mb-3">Design Packages</h1>
        <p class="text-primary-200/80 max-w-md mx-auto">Choose a package that suits your needs and save more on premium designs</p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 -mt-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        @foreach($packages as $index => $package)
        <a href="{{ route('frontend.package.detail', $package->id) }}" class="card-hover group relative bg-white rounded-2xl overflow-hidden {{ $index === 1 ? 'ring-2 ring-primary-400 ring-offset-4' : '' }}">
            @if($index === 1)
            <div class="absolute top-4 right-4 z-10 px-3 py-1.5 bg-gradient-to-r from-primary-500 to-accent-500 text-white text-[10px] font-bold rounded-full uppercase shadow-lg shadow-primary-200">🔥 Most Popular</div>
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
                    <p class="text-xs text-gray-400 mt-0.5">{{ $package->number_of_design }} designs · {{ $package->time_period }} months · ₹{{ number_format($package->price) }}</p>
                </div>
                <i class="fas fa-chevron-right text-gray-300 text-xs group-hover:text-primary-500 group-hover:translate-x-1 transition-all"></i>
            </div>
        </a>
        @endforeach
    </div>
    @if($packages->isEmpty())
        <div class="text-center py-20">
            <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-box-open text-gray-300 text-3xl"></i>
            </div>
            <p class="text-gray-400 font-medium text-lg">No packages available yet.</p>
            <p class="text-sm text-gray-400 mt-1">Check back soon for exciting offers!</p>
        </div>
    @endif
</section>
@endsection
