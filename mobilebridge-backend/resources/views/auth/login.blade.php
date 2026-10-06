@extends('layouts.app')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center">
    <div class="w-full max-w-md bg-slate-900/90 border border-slate-800 rounded-2xl p-8 shadow-2xl backdrop-blur-xl">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-extrabold tracking-tight text-white">Iniciar Sesión</h2>
            <p class="text-sm text-slate-400 mt-2">Accede al panel de control de MobileBridge</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">Correo Electrónico</label>
                <input type="email" name="email" id="email" value="{{ old('email', 'admin@mobilebridge.com') }}" required autofocus
                    class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5">Contraseña</label>
                <input type="password" name="password" id="password" value="password123" required
                    class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center text-sm text-slate-400">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-950 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2">Recordarme</span>
                </label>
            </div>

            <button type="submit"
                class="w-full py-3 px-4 bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-400 text-white font-semibold rounded-xl shadow-lg shadow-indigo-600/30 transition-all hover:scale-[1.01] active:scale-[0.99]">
                Ingresar al Dashboard
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-slate-400">
            ¿No tienes cuenta? <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 font-medium underline">Regístrate aquí</a>
        </div>
    </div>
</div>
@endsection
