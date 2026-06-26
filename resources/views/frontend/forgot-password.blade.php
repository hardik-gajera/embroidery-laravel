@extends('frontend.layout')
@section('title', 'Forgot Password')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-md animate-scale-in">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl shadow-gray-100/50 p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-100 to-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-key text-orange-600 text-xl"></i>
                </div>
                <h1 class="text-xl font-heading font-bold text-gray-800">Forgot Password?</h1>
                <p class="text-sm text-gray-400 mt-1">Enter your mobile number to receive reset code</p>
            </div>

            <form method="POST" action="{{ route('frontend.forgot-password.post') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-1.5 block">Mobile Number</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-phone text-sm"></i></span>
                        <input type="text" name="mobile_no" value="{{ old('mobile_no') }}" required
                            class="w-full border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm focus:outline-none focus:border-primary-400 focus:ring-4 focus:ring-primary-50 transition-all"
                            placeholder="9876543210">
                    </div>
                    @error('mobile_no')<p class="text-red-500 text-xs mt-1.5"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>@enderror
                </div>

                <button type="submit" id="sendOtpBtn" class="btn-glow w-full py-3.5 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-xl font-semibold hover:from-orange-700 hover:to-red-700 transition-all shadow-lg shadow-orange-200 hover:scale-[1.02]">
                    Send Reset Code
                </button>
            </form>

            <script>
                document.querySelector('form').addEventListener('submit', function(e) {
                    var btn = document.getElementById('sendOtpBtn');
                    if (btn.disabled) {
                        e.preventDefault();
                        return false;
                    }
                    btn.disabled = true;
                    btn.textContent = 'Sending...';
                });
            </script>

            <div class="text-center mt-6">
                <a href="{{ route('frontend.login') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                    <i class="fas fa-arrow-left mr-1"></i>Back to Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection