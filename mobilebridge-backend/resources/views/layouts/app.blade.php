<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-900 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'MobileBridge' }} - Control Plane Dashboard</title>
    <!-- Tailwind CSS CDN for instant, pristine UI rendering -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full flex flex-col bg-slate-950 text-slate-100 antialiased selection:bg-indigo-500 selection:text-white">

    <!-- Top Navigation Bar -->
    <nav class="bg-slate-900/80 backdrop-blur-md border-b border-slate-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-cyan-400 flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2zM16 3H8"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xl font-bold tracking-tight bg-gradient-to-r from-white via-slate-200 to-indigo-300 bg-clip-text text-transparent">MobileBridge</span>
                            <span class="text-xs px-2 py-0.5 ml-1.5 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 font-semibold uppercase tracking-wider">Cloud</span>
                        </div>
                    </a>

                    @auth
                    <div class="hidden md:flex items-center space-x-1 pl-6">
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors">
                            Dashboard
                        </a>
                        <a href="{{ route('computers.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('computers.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors">
                            Computadores
                        </a>
                        <a href="{{ route('phones.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('phones.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors">
                            Teléfonos
                        </a>
                        <a href="{{ route('users.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('users.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors">
                            Usuarios
                        </a>
                        <a href="{{ route('connections.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('connections.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors">
                            Conexiones
                        </a>
                    </div>
                    @endauth
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                    <div class="flex items-center space-x-3">
                        <div class="text-right hidden sm:block">
                            <div class="text-sm font-semibold text-slate-200">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-slate-400">{{ auth()->user()->email }}</div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 rounded-lg text-sm font-medium text-rose-300 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 transition-colors flex items-center space-x-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-200 hover:bg-slate-800 transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition-all">Registro</a>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Notification Toasts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
        @if(session('success'))
            <div class="p-4 mb-4 rounded-xl bg-emerald-950/80 border border-emerald-800/60 text-emerald-300 flex items-center space-x-3 shadow-lg">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 mb-4 rounded-xl bg-rose-950/80 border border-rose-800/60 text-rose-300 flex items-center space-x-3 shadow-lg">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 mb-4 rounded-xl bg-rose-950/80 border border-rose-800/60 text-rose-300 shadow-lg">
                <div class="font-semibold mb-1">Por favor verifica los siguientes errores:</div>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900/60 border-t border-slate-800 py-4 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 space-y-2 sm:space-y-0">
            <div>MobileBridge Control Plane &bull; Tecnologías Emergentes en Internet</div>
            <div>PostgreSQL &bull; Laravel Sanctum &bull; Direct Wi-Fi Data Plane</div>
        </div>
    </footer>

</body>
</html>
