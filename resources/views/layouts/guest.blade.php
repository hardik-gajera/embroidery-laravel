<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appSettings['company_name'] ?? 'Embroidery' }} - @yield('title')</title>
    @if($appSettings['logo'] ?? null)
        <link rel="icon" href="{{ asset('storage/' . $appSettings['logo']) }}" type="image/png">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'], heading: ['Poppins', 'sans-serif'] },
                    colors: {
                        primary: { 50: '#e8f4fd', 100: '#d1e9fb', 200: '#a3d3f7', 300: '#5fb3f0', 400: '#2196f3', 500: '#1976d2', 600: '#1565c0', 700: '#0d47a1', 800: '#0a3d8f', 900: '#072e6f' }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-[#eef4fb] flex items-center justify-center p-4 font-sans">
    @yield('content')

    @if(session('success'))
        <div class="fixed top-5 right-5 bg-white border border-green-200 px-5 py-3 rounded-xl shadow-lg flex items-center gap-3" id="toast">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check text-green-500 text-sm"></i>
            </div>
            <p class="text-sm font-medium text-gray-700">{{ session('success') }}</p>
        </div>
        <script>setTimeout(() => { const t = document.getElementById('toast'); if(t) t.remove(); }, 3000);</script>
    @endif
</body>
</html>
