@extends('frontend.layout')
@section('title', $package->name)

@section('content')
<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-2xl overflow-hidden animate-scale-in" style="box-shadow: 0 8px 30px -4px rgba(0,0,0,0.1), 0 4px 12px -2px rgba(0,0,0,0.05);">
        <div class="md:flex">
            @if($package->package_img)
                <div class="md:w-1/2 relative overflow-hidden">
                    <img src="{{ asset('storage/' . $package->package_img) }}" alt="{{ $package->name }}" class="w-full h-full object-cover min-h-[350px]">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/10"></div>
                </div>
            @else
                <div class="md:w-1/2 bg-gradient-to-br from-primary-50 via-accent-50 to-purple-50 flex items-center justify-center min-h-[350px]">
                    <div class="text-center">
                        <i class="fas fa-box-open text-primary-300 text-6xl mb-4"></i>
                        <p class="text-primary-400 font-medium">Package Preview</p>
                    </div>
                </div>
            @endif
            <div class="md:w-1/2 p-8 md:p-10 flex flex-col justify-center">
                <span class="inline-block w-fit px-3 py-1 bg-primary-50 text-primary-600 text-xs font-semibold rounded-full mb-4">Design Package</span>
                <h1 class="text-2xl md:text-3xl font-heading font-bold text-gray-800">{{ $package->name }}</h1>

                <div class="mt-6 space-y-4">
                    <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-blue-50 to-primary-50 rounded-xl">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">
                            <i class="fas fa-palette text-primary-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Designs Included</p>
                            <p class="text-lg font-bold text-gray-800">{{ $package->number_of_design }} designs</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">
                            <i class="fas fa-clock text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Validity Period</p>
                            <p class="text-lg font-bold text-gray-800">{{ $package->time_period }} months</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100">
                    <div class="flex items-end gap-2">
                        <p class="text-4xl font-bold bg-gradient-to-r from-primary-600 to-accent-600 bg-clip-text text-transparent">₹{{ number_format($package->price) }}</p>
                        <span class="text-sm text-gray-400 mb-1">one-time</span>
                    </div>
                </div>

                <form action="{{ route('frontend.package.buy') }}" method="POST" class="mt-6">
                    @csrf
                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                    <button type="submit" class="btn-glow w-full py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-xl font-semibold text-lg hover:from-primary-700 hover:to-primary-800 transition-all shadow-lg shadow-primary-200 hover:scale-[1.02]">
                        <i class="fas fa-shopping-bag mr-2"></i>Buy Package
                    </button>
                </form>

                <!-- Trust -->
                <div class="flex items-center justify-center gap-5 mt-5">
                    <span class="text-xs text-gray-400"><i class="fas fa-shield-alt text-green-400 mr-1"></i>Secure</span>
                    <span class="text-xs text-gray-400"><i class="fas fa-bolt text-yellow-400 mr-1"></i>Instant Access</span>
                    <span class="text-xs text-gray-400"><i class="fas fa-undo text-blue-400 mr-1"></i>Support</span>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
