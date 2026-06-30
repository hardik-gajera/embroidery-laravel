@extends('frontend.layout')
@section('title', 'My Designs')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center gap-4 mb-8 animate-slide-up">
        <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-emerald-100 rounded-xl flex items-center justify-center">
            <i class="fas fa-swatchbook text-green-600"></i>
        </div>
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-800">My Designs</h1>
            <p class="text-sm text-gray-400">Designs you have purchased</p>
        </div>
    </div>

    @if($orders->count() > 0)
    <div class="space-y-4">
        @foreach($orders as $order)
        <div id="design-{{ $order->design_id }}" class="bg-white rounded-2xl overflow-hidden flex items-center gap-4 p-4 transition-all duration-500" style="box-shadow: 0 4px 20px -2px rgba(0,0,0,0.08);">
            @if($order->design && $order->design->design_img)
                <img src="{{ asset('storage/' . $order->design->design_img) }}" class="w-20 h-20 rounded-xl object-cover flex-shrink-0">
            @else
                <div class="w-20 h-20 rounded-xl bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-swatchbook text-gray-600"></i>
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <h3 class="text-sm font-semibold text-gray-800 truncate">{{ $order->design->name ?? 'Design Unavailable' }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">
                    Purchased on {{ $order->created_at->format('M d, Y') }}
                    · ₹{{ number_format($order->amount, 2) }}
                </p>
                @if($order->design)
                <div class="flex items-center gap-3 mt-1.5">
                    @if($order->design->stitches)
                        <span class="text-[10px] text-gray-400"><i class="fas fa-layer-group mr-1"></i>{{ number_format($order->design->stitches) }} stitches</span>
                    @endif
                    <span class="text-[10px] text-gray-400"><i class="fas fa-file mr-1"></i>{{ strtoupper($order->design->design_format) }}</span>
                </div>
                @endif
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full text-green-600 bg-green-50">
                    <i class="fas fa-circle text-[6px] mr-1.5"></i>{{ $order->amount > 0 ? 'Paid' : 'Package' }}
                </span>
                @if($order->design)
                <a href="{{ route('frontend.design.download', $order->design->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-primary-600 to-primary-700 text-white text-xs font-semibold rounded-xl hover:from-primary-700 hover:to-primary-800 transition-all shadow-sm hover:scale-105">
                    <i class="fas fa-download"></i> Download EMB
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-20 bg-white rounded-2xl" style="box-shadow: 0 4px 20px -2px rgba(0,0,0,0.08);">
        <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <i class="fas fa-swatchbook text-gray-300 text-3xl"></i>
        </div>
        <p class="text-gray-500 font-medium text-lg">No designs purchased yet</p>
        <p class="text-sm text-gray-400 mt-1">Browse our collection and purchase your first design</p>
        <a href="{{ route('home') }}#designs" class="inline-flex items-center gap-2 mt-6 px-6 py-3 bg-primary-50 text-primary-600 rounded-xl font-semibold hover:bg-primary-100 transition-all hover:scale-105">
            <i class="fas fa-shopping-bag text-sm"></i>Browse Designs
        </a>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var params = new URLSearchParams(window.location.search);
    var highlight = params.get('highlight');
    if (highlight) {
        var el = document.getElementById('design-' + highlight);
        if (el) {
            setTimeout(function() {
                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 300);
            el.style.outline = '3px solid #f97316';
            el.style.outlineOffset = '2px';
            el.style.boxShadow = '0 0 20px rgba(249, 115, 22, 0.3)';
            setTimeout(function() {
                el.style.outline = 'none';
                el.style.boxShadow = '';
            }, 4000);
        }
    }
});
</script>
@endpush
