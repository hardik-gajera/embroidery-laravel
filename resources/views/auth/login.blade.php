@extends('layouts.guest')
@section('title', 'Login')

@section('content')
<div class="w-full max-w-md">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-primary-50 text-primary-500 rounded-xl flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                EM
            </div>
            <h1 class="text-2xl font-heading font-bold text-gray-800">Embroidery</h1>
            <p class="text-gray-400 text-sm mt-1">Sign in to your admin account</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm font-medium text-gray-700 mb-1.5 block">Email</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full border border-gray-200 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-50 transition"
                        placeholder="admin@example.com">
                </div>
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700 mb-1.5 block">Password</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="password" name="password" required id="password"
                        class="w-full border border-gray-200 rounded-lg pl-10 pr-10 py-2.5 text-sm focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-50 transition"
                        placeholder="••••••••">
                    <button type="button" onclick="togglePassword()" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-eye text-sm" id="eye-icon"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-gray-500 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-primary-500 focus:ring-primary-400">
                    Remember me
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-primary-500 hover:text-primary-600 font-medium">Forgot password?</a>
            </div>

            <button type="submit" class="w-full bg-primary-500 text-white py-2.5 rounded-lg font-medium text-sm hover:bg-primary-600 transition">
                Sign In
            </button>
        </form>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('eye-icon');
    if (input.type === 'password') { input.type = 'text'; icon.classList.replace('fa-eye', 'fa-eye-slash'); }
    else { input.type = 'password'; icon.classList.replace('fa-eye-slash', 'fa-eye'); }
}
</script>
@endsection
