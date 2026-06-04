@extends('layouts.guest')
@section('title', 'Forgot Password')

@section('content')
<div class="w-full max-w-md">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-primary-50 text-primary-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-key text-2xl"></i>
            </div>
            <h1 class="text-2xl font-heading font-bold text-gray-800">Reset Password</h1>
            <p class="text-gray-400 text-sm mt-1">We'll send you a reset link</p>
        </div>

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
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

            <button type="submit" class="w-full bg-primary-500 text-white py-2.5 rounded-lg font-medium text-sm hover:bg-primary-600 transition">
                Send Reset Link
            </button>

            <a href="{{ route('login') }}" class="block text-center text-sm text-gray-500 hover:text-primary-500 transition">
                <i class="fas fa-arrow-left mr-1 text-xs"></i>Back to Login
            </a>
        </form>
    </div>
</div>
@endsection
