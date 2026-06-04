<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Embroidery - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'], heading: ['Poppins', 'sans-serif'] },
                    colors: {
                        sidebar: { DEFAULT: '#1e1b4b', light: '#2d2a6e' },
                        primary: { 50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe', 300: '#a5b4fc', 400: '#818cf8', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca', 800: '#3730a3', 900: '#312e81' }
                    }
                }
            }
        }
    </script>
    <style>
        body { background: #f4f6fb; }
        .sidebar-transition { transition: transform 0.3s ease; }
        .toast-enter { animation: toastSlide 0.4s ease; }
        @keyframes toastSlide { from { transform: translateX(120%); } to { transform: translateX(0); } }
        .toast-exit { animation: toastExit 0.3s ease forwards; }
        @keyframes toastExit { to { transform: translateX(120%); opacity: 0; } }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar-transition fixed inset-y-0 left-0 z-30 w-60 bg-sidebar text-white transform -translate-x-full md:translate-x-0 md:static md:inset-auto flex flex-col">
            <!-- Brand -->
            <div class="flex items-center gap-3 px-5 py-6">
                <div class="w-10 h-10 bg-primary-500 rounded-xl flex items-center justify-center text-sm font-bold text-white">
                    EM
                </div>
                <div>
                    <h1 class="text-base font-heading font-bold">Embroidery</h1>
                    <p class="text-[10px] text-primary-300 uppercase tracking-widest">Admin Panel</p>
                </div>
                <button onclick="toggleSidebar()" class="md:hidden ml-auto text-white/60 hover:text-white"><i class="fas fa-times"></i></button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 mt-2 space-y-6">
                <div>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold px-3 mb-2">Main</p>
                    <div class="space-y-1">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                            <i class="fas fa-chart-pie w-5 text-center text-sm"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('customers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('customers.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                            <i class="fas fa-users w-5 text-center text-sm"></i>
                            <span>Customers</span>
                        </a>
                    </div>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold px-3 mb-2">Catalogue</p>
                    <div class="space-y-1">
                        <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('categories.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                            <i class="fas fa-folder w-5 text-center text-sm"></i>
                            <span>Categories</span>
                        </a>
                        <a href="{{ route('designs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('designs.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                            <i class="fas fa-swatchbook w-5 text-center text-sm"></i>
                            <span>Designs</span>
                        </a>
                        <a href="{{ route('packages.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('packages.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                            <i class="fas fa-box w-5 text-center text-sm"></i>
                            <span>Packages</span>
                        </a>
                        <a href="{{ route('package-history') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('package-history') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                            <i class="fas fa-receipt w-5 text-center text-sm"></i>
                            <span>Package History</span>
                        </a>
                    </div>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold px-3 mb-2">System</p>
                    <div class="space-y-1">
                        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/10 hover:text-white transition">
                            <i class="fas fa-cog w-5 text-center text-sm"></i>
                            <span>Settings</span>
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-white/10">
                <div class="flex items-center gap-3 px-2">
                    <div class="w-9 h-9 bg-primary-500 rounded-full flex items-center justify-center text-xs font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[11px] text-gray-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="text-gray-400 hover:text-red-400 transition" title="Logout">
                            <i class="fas fa-sign-out-alt text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="md:hidden text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <div>
                        <h2 class="text-xl font-heading font-bold text-gray-800">@yield('title')</h2>
                        <p class="text-xs text-gray-400">@yield('subtitle', 'Manage your embroidery business')</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-1.5 text-sm text-gray-500">
                        <i class="fas fa-calendar text-gray-400 text-xs"></i>
                        {{ now()->format('M d, Y') }}
                    </div>
                    <button class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-gray-400 hover:text-gray-600 transition">
                        <i class="fas fa-bell text-sm"></i>
                    </button>
                    <button class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-gray-400 hover:text-gray-600 transition">
                        <i class="fas fa-cog text-sm"></i>
                    </button>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Overlay for mobile -->
    <div id="overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/40 z-20 hidden md:hidden"></div>

    <!-- Toast Notifications -->
    @if(session('success'))
        <div class="toast-enter fixed top-5 right-5 z-50 bg-white border border-green-200 px-5 py-3 rounded-xl shadow-lg flex items-center gap-3" id="toast">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check text-green-500 text-sm"></i>
            </div>
            <p class="text-sm font-medium text-gray-700">{{ session('success') }}</p>
            <button onclick="this.parentElement.remove()" class="text-gray-300 hover:text-gray-500 ml-2"><i class="fas fa-times text-xs"></i></button>
        </div>
    @endif
    @if(session('error'))
        <div class="toast-enter fixed top-5 right-5 z-50 bg-white border border-red-200 px-5 py-3 rounded-xl shadow-lg flex items-center gap-3" id="toast">
            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation text-red-500 text-sm"></i>
            </div>
            <p class="text-sm font-medium text-gray-700">{{ session('error') }}</p>
            <button onclick="this.parentElement.remove()" class="text-gray-300 hover:text-gray-500 ml-2"><i class="fas fa-times text-xs"></i></button>
        </div>
    @endif

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
            document.getElementById('overlay').classList.toggle('hidden');
        }
        setTimeout(() => { const t = document.getElementById('toast'); if(t) { t.classList.add('toast-exit'); setTimeout(()=>t.remove(), 300); } }, 4000);
    </script>
    @stack('scripts')
</body>
</html>
