<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page Expired - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
            <!-- Icon -->
            <div class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-clock text-orange-500 text-4xl"></i>
            </div>
            
            <!-- Title -->
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Page Expired</h1>
            
            <!-- Message -->
            <p class="text-gray-600 mb-8 leading-relaxed">
                Your session has expired for security reasons. This usually happens when you've been inactive for a while or when there's been a security token mismatch.
            </p>
            
            <!-- Actions -->
            <div class="space-y-4">
                <button onclick="refreshPage()" class="w-full px-6 py-3 bg-primary-600 text-white rounded-xl font-semibold hover:bg-primary-700 transition-all hover:scale-105 shadow-lg">
                    <i class="fas fa-refresh mr-2"></i>Refresh Page
                </button>
                
                <button onclick="goBack()" class="w-full px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>Go Back
                </button>
                
                <a href="{{ route('home') }}" class="w-full inline-block px-6 py-3 bg-gray-50 text-gray-600 rounded-xl font-medium hover:bg-gray-100 transition-all text-decoration-none">
                    <i class="fas fa-home mr-2"></i>Return to Home
                </a>
            </div>
            
            <!-- Help Text -->
            <div class="mt-8 pt-6 border-t border-gray-100">
                <p class="text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    If this keeps happening, try clearing your browser cache or contact support.
                </p>
            </div>
        </div>
        
        <!-- Additional Info -->
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-400">
                Error Code: 419 • Session Token Expired
            </p>
        </div>
    </div>

    <script>
        function refreshPage() {
            // Add a small delay for better UX
            setTimeout(() => {
                window.location.reload(true);
            }, 300);
        }
        
        function goBack() {
            // Check if there's history to go back to
            if (window.history.length > 1) {
                setTimeout(() => {
                    window.history.back();
                }, 300);
            } else {
                // Fallback to home page
                setTimeout(() => {
                    window.location.href = '{{ route("home") }}';
                }, 300);
            }
        }
        
        // Auto-refresh after 30 seconds if user doesn't take action
        let autoRefreshTimer = setTimeout(() => {
            console.log('Auto-refreshing due to inactivity...');
            refreshPage();
        }, 30000);
        
        // Clear timer if user takes any action
        document.addEventListener('click', () => {
            clearTimeout(autoRefreshTimer);
        });
        
        // Handle browser back button
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                // Page was loaded from cache, refresh it
                refreshPage();
            }
        });
    </script>
</body>
</html>