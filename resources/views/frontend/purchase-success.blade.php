@extends('frontend.layout')
@section('title', 'Purchase Successful')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-lg animate-scale-in">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl shadow-gray-100/50 p-8 text-center">
            <!-- Success Icon -->
            <div class="w-20 h-20 bg-gradient-to-br from-green-100 to-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check-circle text-green-500 text-3xl"></i>
            </div>

            <h1 class="text-2xl font-heading font-bold text-gray-800">Purchase Successful!</h1>
            <p class="text-sm text-gray-400 mt-2">{{ $designs->count() > 1 ? 'Your designs are' : 'Your design is' }} ready to download</p>

            <!-- Design(s) Info -->
            <div class="mt-6 space-y-3">
                @foreach($designs as $design)
                <div class="bg-gradient-to-r from-gray-50 to-white rounded-xl p-4 border border-gray-100">
                    <div class="flex items-center gap-4">
                        @if($design->design_img)
                            <img src="{{ asset('storage/' . $design->design_img) }}" class="w-14 h-14 rounded-xl object-cover border border-gray-100 shadow-sm">
                        @else
                            <div class="w-14 h-14 bg-gradient-to-br from-gray-100 to-gray-50 rounded-xl flex items-center justify-center"><i class="fas fa-swatchbook text-gray-400"></i></div>
                        @endif
                        <div class="flex-1 text-left">
                            <p class="text-sm font-semibold text-gray-800">{{ $design->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $design->design_code }} · {{ $design->design_format }}</p>
                        </div>
                        <a href="{{ route('frontend.design.download', $design->id) }}" class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center text-green-600 hover:bg-green-100 hover:scale-110 transition-all">
                            <i class="fas fa-download text-sm"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Secondary Actions -->
            <div class="flex items-center justify-center gap-4 mt-6">
                <a href="{{ route('frontend.my-designs') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                    <i class="fas fa-swatchbook mr-1"></i>My Designs
                </a>
                <span class="text-gray-300">|</span>
                <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-gray-700 font-medium">
                    <i class="fas fa-home mr-1"></i>Continue Browsing
                </a>
            </div>
        </div>
    </div>
</div>
@endsection