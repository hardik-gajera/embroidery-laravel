<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $appSettings['company_name'] ?? 'Embroidery' }} - @yield('title', 'Home')</title>
    @if($appSettings['logo'] ?? null)
        <link rel="icon" href="{{ asset('storage/' . $appSettings['logo']) }}" type="image/png">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'], heading: ['Poppins', 'sans-serif'] },
                    colors: {
                        primary: { 50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe', 300: '#a5b4fc', 400: '#818cf8', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca', 800: '#3730a3', 900: '#312e81' },
                        accent: { 50: '#fdf4ff', 100: '#fae8ff', 200: '#f5d0fe', 300: '#f0abfc', 400: '#e879f9', 500: '#d946ef', 600: '#c026d3', 700: '#a21caf' }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 40px;
            cursor: pointer;
        }
        select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        .animate-slide-up { animation: slideUp 0.6s ease-out; }
        .animate-scale-in { animation: scaleIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes scaleIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        .gradient-text { background: linear-gradient(135deg, #6366f1, #d946ef); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .glass { background: rgba(255,255,255,0.8); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
        .card-hover { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 4px 20px -2px rgba(0,0,0,0.08), 0 2px 8px -2px rgba(0,0,0,0.04); }
        .card-hover:hover { transform: translateY(-8px); box-shadow: 0 25px 50px -12px rgba(99, 102, 241, 0.2), 0 12px 24px -8px rgba(0,0,0,0.08); }
        .btn-glow { position: relative; overflow: hidden; }
        .btn-glow::after { content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 60%); opacity: 0; transition: opacity 0.3s; }
        .btn-glow:hover::after { opacity: 1; }
        .shimmer { background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent); background-size: 200% 100%; animation: shimmer 2s infinite; }
        @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
        .scroll-reveal { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
        .scroll-reveal.revealed { opacity: 1; transform: translateY(0); }
        @keyframes cartBounce { 0% { transform: scale(1); } 30% { transform: scale(1.3); } 60% { transform: scale(0.9); } 100% { transform: scale(1); } }
        .cart-bounce { animation: cartBounce 0.5s ease; }
        @keyframes flyToCart { 0% { opacity: 1; transform: translate(0, 0) scale(1); } 100% { opacity: 0; transform: translate(var(--fly-x), var(--fly-y)) scale(0.3); } }
        .fly-to-cart { animation: flyToCart 0.7s cubic-bezier(0.2, 0, 0.2, 1) forwards; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navbar -->
    <nav class="glass border-b border-gray-200/50 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                    @if($appSettings['logo'])
                        <img src="{{ asset('storage/' . $appSettings['logo']) }}" alt="{{ $appSettings['company_name'] }}" class="w-10 h-10 object-contain rounded-xl">
                    @else
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-accent-500 rounded-xl flex items-center justify-center text-white text-sm font-bold shadow-lg shadow-primary-200 group-hover:shadow-primary-300 transition-shadow">EM</div>
                    @endif
                    <span class="text-lg font-heading font-bold text-gray-800">{{ $appSettings['company_name'] }}</span>
                </a>

                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">Home</a>
                    <a href="{{ route('frontend.all-designs') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">All Designs</a>
                    <a href="{{ route('home') }}#categories" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">Categories</a>
                    <a href="{{ route('frontend.packages') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">Packages</a>
                    <a href="{{ route('frontend.about') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">About Us</a>
                    <a href="{{ route('frontend.contact') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">Contact</a>
                </div>

                <div class="flex items-center gap-3">
                    @if(session('customer_id'))
                        <a href="{{ route('frontend.cart') }}" id="cart-icon" class="relative w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-primary-50 hover:text-primary-600 transition-all hover:scale-105">
                            <i class="fas fa-shopping-cart text-sm"></i>
                            @if(session('cart_count', 0) > 0)
                                <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-sm">{{ session('cart_count') }}</span>
                            @endif
                        </a>
                        <!-- Profile Dropdown -->
                        <div class="relative" id="profile-dropdown">
                            <button onclick="document.getElementById('profile-menu').classList.toggle('hidden')" class="flex items-center gap-2 px-3 py-1.5 bg-primary-50 rounded-xl hover:bg-primary-100 transition-all">
                                <div class="w-7 h-7 bg-primary-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-[10px] text-primary-600"></i>
                                </div>
                                <span class="text-sm font-medium text-primary-700 hidden sm:inline">{{ session('customer_name') }}</span>
                                <i class="fas fa-chevron-down text-[10px] text-primary-400"></i>
                            </button>
                            <div id="profile-menu" class="hidden absolute right-0 top-full mt-2 w-64 bg-white rounded-xl border border-gray-100 shadow-xl shadow-gray-200/50 py-2 z-50">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-semibold text-gray-800">{{ session('customer_name') }}</p>
                                </div>
                                {{-- Active Package Status --}}
                                @if(session('customer_id'))
                                    @php $navCustomer = app('App\Models\Customer')->with('package')->find(session('customer_id')); @endphp
                                    @if($navCustomer && $navCustomer->package && $navCustomer->package_end_date && $navCustomer->package_end_date->isFuture())
                                        <div class="mx-3 my-2 p-3 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-100 rounded-xl">
                                            <div class="flex items-center justify-between mb-1.5">
                                                <span class="text-[10px] font-bold text-green-700 uppercase tracking-wide flex items-center gap-1">
                                                    <i class="fas fa-bolt text-yellow-500"></i> Active Package
                                                </span>
                                                <span class="text-[10px] text-green-600 font-semibold">{{ $navCustomer->package_end_date->format('d M Y') }}</span>
                                            </div>
                                            <p class="text-xs font-semibold text-gray-800 truncate">{{ $navCustomer->package->name }}</p>
                                            <div class="mt-1.5">
                                                <div class="flex justify-between text-[10px] text-gray-500 mb-1">
                                                    <span>{{ $navCustomer->downloaded_design }} used</span>
                                                    <span class="font-semibold text-green-600">{{ $navCustomer->total_design - $navCustomer->downloaded_design }} left</span>
                                                </div>
                                                <div class="h-1.5 bg-green-200 rounded-full overflow-hidden">
                                                    <div class="h-full bg-green-500 rounded-full" style="width: {{ $navCustomer->total_design > 0 ? min(100, $navCustomer->downloaded_design / $navCustomer->total_design * 100) : 0 }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mx-3 my-2 p-3 bg-gray-50 border border-gray-100 rounded-xl">
                                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide mb-1">No Active Package</p>
                                            <a href="{{ route('frontend.packages') }}" class="text-xs text-primary-600 font-semibold hover:underline">Browse packages →</a>
                                        </div>
                                    @endif
                                @endif
                                <a href="{{ route('frontend.my-designs') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 transition-all">
                                    <i class="fas fa-swatchbook w-4 text-center text-xs"></i>My Designs
                                </a>
                                <a href="{{ route('frontend.my-packages') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 transition-all">
                                    <i class="fas fa-box w-4 text-center text-xs"></i>My Packages
                                </a>
                                <div class="border-t border-gray-100 mt-1 pt-1">
                                    <form action="{{ route('frontend.logout') }}" method="POST">
                                        @csrf
                                        <button class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-all w-full text-left">
                                            <i class="fas fa-sign-out-alt w-4 text-center text-xs"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('frontend.login') }}" class="px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-xl text-sm font-semibold hover:from-primary-700 hover:to-primary-800 transition-all shadow-lg shadow-primary-200 hover:shadow-primary-300 hover:scale-105">
                            <i class="fas fa-user mr-1.5"></i>Login
                        </a>
                    @endif

                    <!-- Mobile menu button -->
                    <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="md:hidden w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-600">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-gray-100 mt-2 pt-3">
                <div class="flex flex-col gap-1">
                    <a href="{{ route('home') }}" class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">Home</a>
                    <a href="{{ route('frontend.all-designs') }}" class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">All Designs</a>
                    <a href="{{ route('home') }}#categories" class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">Categories</a>
                    <a href="{{ route('frontend.packages') }}" class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">Packages</a>
                    <a href="{{ route('frontend.about') }}" class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">About Us</a>
                    <a href="{{ route('frontend.contact') }}" class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">Contact</a>
                    @if(session('customer_id'))
                    <a href="{{ route('frontend.my-designs') }}" class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">My Designs</a>
                    <a href="{{ route('frontend.my-packages') }}" class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">My Packages</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="animate-fade-in">@yield('content')</main>

    <!-- Footer -->
    <footer class="bg-gradient-to-b from-gray-900 to-gray-950 text-gray-400 py-16 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2.5 mb-4">
                        @if($appSettings['logo'])
                            <img src="{{ asset('storage/' . $appSettings['logo']) }}" alt="{{ $appSettings['company_name'] }}" class="w-10 h-10 object-contain rounded-xl">
                        @else
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-accent-500 rounded-xl flex items-center justify-center text-white text-sm font-bold">EM</div>
                        @endif
                        <span class="text-white font-heading font-bold text-lg">{{ $appSettings['company_name'] }}</span>
                    </div>
                    <p class="text-sm leading-relaxed max-w-sm">Premium embroidery designs for your creative projects. Download instantly and bring your ideas to life.</p>
                    <div class="flex gap-3 mt-5">
                        <a href="{{ $appSettings['facebook_url'] }}" class="w-9 h-9 bg-gray-800 rounded-lg flex items-center justify-center text-gray-400 hover:text-white hover:bg-primary-600 transition-all"><i class="fab fa-facebook-f text-sm"></i></a>
                        <a href="{{ $appSettings['instagram_url'] }}" class="w-9 h-9 bg-gray-800 rounded-lg flex items-center justify-center text-gray-400 hover:text-white hover:bg-primary-600 transition-all"><i class="fab fa-instagram text-sm"></i></a>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $appSettings['whatsapp']) }}" class="w-9 h-9 bg-gray-800 rounded-lg flex items-center justify-center text-gray-400 hover:text-white hover:bg-primary-600 transition-all"><i class="fab fa-whatsapp text-sm"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Quick Links</h4>
                    <div class="space-y-2.5 text-sm">
                        <a href="{{ route('home') }}" class="block hover:text-white hover:translate-x-1 transition-all">Home</a>
                        <a href="{{ route('frontend.all-designs') }}" class="block hover:text-white hover:translate-x-1 transition-all">All Designs</a>
                        <a href="{{ route('home') }}#categories" class="block hover:text-white hover:translate-x-1 transition-all">Categories</a>
                        <a href="{{ route('frontend.packages') }}" class="block hover:text-white hover:translate-x-1 transition-all">Packages</a>
                        <a href="{{ route('frontend.about') }}" class="block hover:text-white hover:translate-x-1 transition-all">About Us</a>
                        <a href="{{ route('frontend.contact') }}" class="block hover:text-white hover:translate-x-1 transition-all">Contact Us</a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Contact</h4>
                    <div class="space-y-2.5 text-sm">
                        <p><i class="fas fa-envelope mr-2 text-primary-400"></i>{{ $appSettings['email'] }}</p>
                        <p><i class="fas fa-phone mr-2 text-primary-400"></i>{{ $appSettings['mobile'] }}</p>
                        <p><i class="fas fa-map-marker-alt mr-2 text-primary-400"></i>{{ $appSettings['address'] }}</p>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-10 pt-6 text-center text-xs text-gray-500">© {{ date('Y') }} {{ $appSettings['company_name'] }}. All rights reserved.</div>
        </div>
    </footer>

    <!-- Toast Notifications -->
    @if(session('success'))
        <div class="fixed top-20 right-5 z-50 animate-slide-up" id="toast">
            <div class="bg-white border {{ session('success') === 'Added to cart!' ? 'border-primary-200' : 'border-green-200' }} px-5 py-3.5 rounded-xl shadow-2xl {{ session('success') === 'Added to cart!' ? 'shadow-primary-100' : 'shadow-green-100' }} flex items-center gap-3">
                <div class="w-8 h-8 {{ session('success') === 'Added to cart!' ? 'bg-primary-100' : 'bg-green-100' }} rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas {{ session('success') === 'Added to cart!' ? 'fa-cart-plus text-primary-500' : 'fa-check text-green-500' }} text-sm"></i>
                </div>
                <p class="text-sm font-medium text-gray-700">{{ session('success') }}</p>
            </div>
        </div>
        @if(session('success') === 'Added to cart!')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const cartIcon = document.getElementById('cart-icon');
                if (cartIcon) {
                    cartIcon.classList.add('cart-bounce');
                    setTimeout(() => cartIcon.classList.remove('cart-bounce'), 600);
                }
            });
        </script>
        @endif
        <script>setTimeout(() => { const t = document.getElementById('toast'); if(t) { t.style.opacity = '0'; t.style.transform = 'translateX(100px)'; t.style.transition = 'all 0.4s'; setTimeout(() => t.remove(), 400); } }, 3000);</script>
    @endif
    @if(session('error'))
        <div class="fixed top-20 right-5 z-50 animate-slide-up" id="toast">
            <div class="bg-white border border-red-200 px-5 py-3.5 rounded-xl shadow-2xl shadow-red-100 flex items-center gap-3">
                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation text-red-500 text-sm"></i>
                </div>
                <p class="text-sm font-medium text-gray-700">{{ session('error') }}</p>
            </div>
        </div>
        <script>setTimeout(() => { const t = document.getElementById('toast'); t.style.opacity = '0'; t.style.transform = 'translateX(100px)'; t.style.transition = 'all 0.4s'; setTimeout(() => t.remove(), 400); }, 3000);</script>
    @endif

    <!-- Scroll Reveal Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                    }
                });
            }, { threshold: 0.1 });
            document.querySelectorAll('.scroll-reveal').forEach(el => observer.observe(el));

            // Close profile dropdown on outside click
            document.addEventListener('click', function(e) {
                const dropdown = document.getElementById('profile-dropdown');
                const menu = document.getElementById('profile-menu');
                if (dropdown && menu && !dropdown.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });
        });

        // CSRF Token Refresh and Form Error Handling
        function refreshCSRFToken() {
            return fetch('{{ route("csrf-token") }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.token) {
                    // Update meta tag
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.token);
                    // Update all CSRF input fields
                    document.querySelectorAll('input[name="_token"]').forEach(input => {
                        input.value = data.token;
                    });
                    return data.token;
                }
                throw new Error('Failed to get new token');
            })
            .catch(error => {
                console.log('CSRF token refresh failed:', error);
                return null;
            });
        }

        // Enhanced form submission with CSRF error handling
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.tagName === 'FORM' && form.method.toLowerCase() === 'post') {
                e.preventDefault();
                
                // Show loading state
                const submitButton = form.querySelector('button[type="submit"]');
                const originalText = submitButton ? submitButton.innerHTML : '';
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
                }

                const formData = new FormData(form);
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (response.status === 419) {
                        // Token expired, refresh and retry
                        return refreshCSRFToken().then(newToken => {
                            if (newToken) {
                                formData.set('_token', newToken);
                                return fetch(form.action, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': newToken
                                    }
                                });
                            } else {
                                throw new Error('Failed to refresh token');
                            }
                        });
                    }
                    return response;
                })
                .then(response => {
                    if (response.ok || response.status === 302) {
                        // Success - let the form submit normally
                        form.submit();
                    } else {
                        return response.text().then(text => {
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        });
                    }
                })
                .catch(error => {
                    console.log('Form submission error:', error);
                    
                    // Reset button state
                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                    }
                    
                    // Show error message
                    if (error.message.includes('419') || error.message.includes('token')) {
                        showToast('Session expired. Please refresh the page and try again.', 'error');
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        showToast('An error occurred. Please try again.', 'error');
                    }
                });
            }
        });

        // Auto-refresh CSRF token every 30 minutes
        setInterval(() => {
            refreshCSRFToken();
        }, 30 * 60 * 1000); // 30 minutes

        // Refresh token on page focus (when user returns to tab)
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                refreshCSRFToken();
            }
        });

        // Toast notification function
        function showToast(message, type = 'success') {
            // Remove existing toasts
            document.querySelectorAll('#dynamic-toast').forEach(toast => toast.remove());
            
            const colors = {
                success: { bg: 'bg-green-100', border: 'border-green-200', icon: 'fa-check text-green-500', shadow: 'shadow-green-100' },
                error: { bg: 'bg-red-100', border: 'border-red-200', icon: 'fa-exclamation text-red-500', shadow: 'shadow-red-100' },
                info: { bg: 'bg-blue-100', border: 'border-blue-200', icon: 'fa-info text-blue-500', shadow: 'shadow-blue-100' }
            };
            
            const color = colors[type] || colors.info;
            
            const toast = document.createElement('div');
            toast.id = 'dynamic-toast';
            toast.className = 'fixed top-20 right-5 z-50 animate-slide-up';
            toast.innerHTML = `
                <div class="bg-white border ${color.border} px-5 py-3.5 rounded-xl shadow-2xl ${color.shadow} flex items-center gap-3">
                    <div class="w-8 h-8 ${color.bg} rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas ${color.icon} text-sm"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-700">${message}</p>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100px)';
                toast.style.transition = 'all 0.4s';
                setTimeout(() => toast.remove(), 400);
            }, 4000);
        }
    </script>

    @stack('scripts')
</body>
</html>
