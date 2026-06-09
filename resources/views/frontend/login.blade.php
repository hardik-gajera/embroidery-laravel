@extends('frontend.layout')
@section('title', 'Login / Register')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-md animate-scale-in">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl shadow-gray-100/50 p-8">
            <!-- Tabs -->
            <div class="flex bg-gray-100 rounded-xl p-1 mb-8">
                <button onclick="showTab('login')" id="login-tab" class="flex-1 py-2.5 text-sm font-semibold rounded-lg bg-white text-primary-600 shadow-sm transition-all">Sign In</button>
                <button onclick="showTab('register')" id="register-tab" class="flex-1 py-2.5 text-sm font-semibold rounded-lg text-gray-500 hover:text-gray-700 transition-all">Register</button>
            </div>

            <!-- Login Form -->
            <div id="login-form">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary-100 to-accent-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user text-primary-600 text-xl"></i>
                    </div>
                    <h1 class="text-xl font-heading font-bold text-gray-800">Welcome Back</h1>
                    <p class="text-sm text-gray-400 mt-1">Sign in to your account</p>
                </div>

                <form method="POST" action="{{ route('frontend.login.post') }}" class="space-y-5">
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
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-lock text-sm"></i></span>
                            <input type="password" name="password" required
                                class="w-full border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm focus:outline-none focus:border-primary-400 focus:ring-4 focus:ring-primary-50 transition-all"
                                placeholder="••••••••">
                        </div>
                    </div>
                    <button type="submit" class="btn-glow w-full py-3.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-xl font-semibold hover:from-primary-700 hover:to-primary-800 transition-all shadow-lg shadow-primary-200 hover:scale-[1.02]">
                        Sign In
                    </button>
                </form>
                <p class="text-center text-sm text-gray-400 mt-6">Don't have an account? <a href="javascript:void(0)" onclick="showTab('register')" class="text-primary-600 font-semibold hover:text-primary-700">Create one</a></p>
            </div>

            <!-- Register Form -->
            <div id="register-form" class="hidden">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-plus text-green-600 text-xl"></i>
                    </div>
                    <h1 class="text-xl font-heading font-bold text-gray-800">Create Account</h1>
                    <p class="text-sm text-gray-400 mt-1">Register to start purchasing designs</p>
                </div>

                <form method="POST" action="{{ route('frontend.register.post') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Full Name</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-user text-sm"></i></span>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm focus:outline-none focus:border-primary-400 focus:ring-4 focus:ring-primary-50 transition-all"
                                placeholder="John Doe">
                        </div>
                        @error('name')<p class="text-red-500 text-xs mt-1.5"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Email</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-envelope text-sm"></i></span>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm focus:outline-none focus:border-primary-400 focus:ring-4 focus:ring-primary-50 transition-all"
                                placeholder="your@email.com">
                        </div>
                        @error('email')<p class="text-red-500 text-xs mt-1.5"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Mobile No</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-phone text-sm"></i></span>
                            <input type="text" name="mobile_no" value="{{ old('mobile_no') }}" required
                                class="w-full border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm focus:outline-none focus:border-primary-400 focus:ring-4 focus:ring-primary-50 transition-all"
                                placeholder="9876543210">
                        </div>
                        @error('mobile_no')<p class="text-red-500 text-xs mt-1.5"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-lock text-sm"></i></span>
                            <input type="password" name="password" required
                                class="w-full border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm focus:outline-none focus:border-primary-400 focus:ring-4 focus:ring-primary-50 transition-all"
                                placeholder="••••••••">
                        </div>
                        @error('password')<p class="text-red-500 text-xs mt-1.5"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Confirm Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-lock text-sm"></i></span>
                            <input type="password" name="password_confirmation" required
                                class="w-full border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm focus:outline-none focus:border-primary-400 focus:ring-4 focus:ring-primary-50 transition-all"
                                placeholder="••••••••">
                        </div>
                    </div>
                    <button type="submit" class="btn-glow w-full py-3.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl font-semibold hover:from-green-700 hover:to-emerald-700 transition-all shadow-lg shadow-green-200 hover:scale-[1.02]">
                        Create Account
                    </button>
                </form>
                <p class="text-center text-sm text-gray-400 mt-6">Already have an account? <a href="javascript:void(0)" onclick="showTab('login')" class="text-primary-600 font-semibold hover:text-primary-700">Sign In</a></p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showTab(tab) {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const loginTab = document.getElementById('login-tab');
    const registerTab = document.getElementById('register-tab');

    if (tab === 'login') {
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
        loginTab.classList.add('bg-white', 'text-primary-600', 'shadow-sm');
        loginTab.classList.remove('text-gray-500');
        registerTab.classList.remove('bg-white', 'text-primary-600', 'shadow-sm');
        registerTab.classList.add('text-gray-500');
    } else {
        loginForm.classList.add('hidden');
        registerForm.classList.remove('hidden');
        registerTab.classList.add('bg-white', 'text-primary-600', 'shadow-sm');
        registerTab.classList.remove('text-gray-500');
        loginTab.classList.remove('bg-white', 'text-primary-600', 'shadow-sm');
        loginTab.classList.add('text-gray-500');
    }
}
@if($errors->has('name') || $errors->has('email') || $errors->has('password_confirmation'))
    showTab('register');
@elseif($errors->has('mobile_no') || $errors->has('password'))
    showTab('login');
@endif
</script>
@endpush
