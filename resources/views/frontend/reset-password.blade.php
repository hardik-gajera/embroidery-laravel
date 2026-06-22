@extends('frontend.layout')
@section('title', 'Reset Password')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-md animate-scale-in">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl shadow-gray-100/50 p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-green-600 text-xl"></i>
                </div>
                <h1 class="text-xl font-heading font-bold text-gray-800">Reset Password</h1>
                <p class="text-sm text-gray-400 mt-1">Enter the reset code and your new password</p>
                @if(isset($reset_mobile))
                    <p class="text-xs text-primary-600 mt-2 font-medium">Code sent to: {{ $reset_mobile }}</p>
                @endif
            </div>

            <form method="POST" action="{{ route('frontend.reset-password.post') }}" class="space-y-5" id="resetForm">
                @csrf
                <input type="hidden" name="_reset_mobile" value="{{ $reset_mobile ?? '' }}">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-1.5 block">Reset Code</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-key text-sm"></i></span>
                        <input type="text" name="reset_code" value="{{ old('reset_code') }}" required maxlength="6"
                            class="w-full border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm focus:outline-none focus:border-primary-400 focus:ring-4 focus:ring-primary-50 transition-all text-center font-mono tracking-widest"
                            placeholder="123456">
                    </div>
                    @error('reset_code')<p class="text-red-500 text-xs mt-1.5"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700 mb-1.5 block">New Password</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-lock text-sm"></i></span>
                        <input type="password" name="password" required
                            class="w-full border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm focus:outline-none focus:border-primary-400 focus:ring-4 focus:ring-primary-50 transition-all"
                            placeholder="••••••••">
                    </div>
                    @error('password')<p class="text-red-500 text-xs mt-1.5"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700 mb-1.5 block">Confirm New Password</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-lock text-sm"></i></span>
                        <input type="password" name="password_confirmation" required
                            class="w-full border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm focus:outline-none focus:border-primary-400 focus:ring-4 focus:ring-primary-50 transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" id="resetBtn" class="btn-glow w-full py-3.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl font-semibold hover:from-green-700 hover:to-emerald-700 transition-all shadow-lg shadow-green-200 hover:scale-[1.02]">
                    Reset Password
                </button>
            </form>

            <script>
                document.getElementById('resetForm').addEventListener('submit', function() {
                    var btn = document.getElementById('resetBtn');
                    btn.disabled = true;
                    btn.textContent = 'Resetting...';
                });
            </script>

            <div class="text-center mt-6">
                <a href="{{ route('frontend.forgot-password') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                    <i class="fas fa-arrow-left mr-1"></i>Request New Code
                </a>
            </div>
            
            @if(session('password_reset_expires'))
                <div class="text-center mt-4">
                    <p class="text-xs text-gray-500">Code expires in: <span id="countdown" class="font-medium">10:00</span></p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection