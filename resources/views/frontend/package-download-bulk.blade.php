@extends('frontend.layout')
@section('title', 'Claim Designs')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-lg animate-scale-in">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl shadow-gray-100/50 p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-gift text-green-600 text-xl"></i>
                </div>
                <h1 class="text-xl font-heading font-bold text-gray-800">Free with Your Package!</h1>
                <p class="text-sm text-gray-400 mt-1">{{ $designs->count() }} designs included in your active package</p>
            </div>

            <!-- Designs List -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-100 mb-6 space-y-3">
                @foreach($designs as $design)
                <div class="flex items-center gap-3">
                    @if($design->design_img)
                        <img src="{{ asset('storage/' . $design->design_img) }}" class="w-12 h-12 rounded-lg object-cover border border-green-200">
                    @else
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center"><i class="fas fa-swatchbook text-green-500 text-sm"></i></div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $design->name }}</p>
                        <p class="text-xs text-gray-500">{{ $design->design_code }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs line-through text-gray-400">₹{{ number_format($design->design_price) }}</p>
                        <p class="text-sm font-bold text-green-600">FREE</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Package Info -->
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 mb-6">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Package Downloads Used</span>
                    <span class="font-bold text-gray-800">{{ $customer->downloaded_design }} / {{ $customer->total_design }}</span>
                </div>
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden mt-2">
                    <div class="h-full bg-green-500 rounded-full" style="width: {{ $customer->total_design > 0 ? ($customer->downloaded_design / $customer->total_design * 100) : 0 }}%"></div>
                </div>
                <p class="text-xs text-gray-400 mt-1.5">{{ $customer->total_design - $customer->downloaded_design }} downloads remaining (will use {{ $designs->count() }})</p>
            </div>

            <form action="{{ route('frontend.claim.bulk') }}" method="POST">
                @csrf
                @foreach($designs as $design)
                    <input type="hidden" name="design_ids[]" value="{{ $design->id }}">
                @endforeach
                <button type="submit" class="btn-glow w-full py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl font-semibold text-lg hover:from-green-700 hover:to-emerald-700 transition-all shadow-lg shadow-green-200 hover:scale-[1.02]">
                    <i class="fas fa-check-circle mr-2"></i>Claim {{ $designs->count() }} Designs for Free
                </button>
            </form>

            <p class="text-center text-xs text-gray-400 mt-4"><i class="fas fa-info-circle mr-1"></i>This will use {{ $designs->count() }} downloads from your package quota</p>
        </div>
    </div>
</div>
@endsection