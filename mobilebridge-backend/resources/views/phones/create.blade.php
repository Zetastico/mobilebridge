@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-8 shadow-2xl">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800">
            <div>
                <h1 class="text-xl font-bold text-white">Registrar Teléfono Android</h1>
                <p class="text-xs text-slate-400 mt-1">Registrar dispositivo manualmente en tu cuenta</p>
            </div>
            <a href="{{ route('phones.index') }}" class="text-xs text-slate-400 hover:text-white">&larr; Volver</a>
        </div>

        <form action="{{ route('phones.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Nombre del Teléfono</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Ej: Mi Google Pixel 7" required
                    class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Modelo (Opcional)</label>
                <input type="text" name="model" value="{{ old('model') }}" placeholder="Ej: Pixel 7 Pro"
                    class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Versión Android</label>
                <input type="text" name="android_version" value="{{ old('android_version', 'Android 14') }}" placeholder="Ej: Android 14 (API 34)"
                    class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">UUID Personalizado (Opcional)</label>
                <input type="text" name="device_uuid" value="{{ old('device_uuid') }}" placeholder="Dejar vacío para autogenerar UUID"
                    class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm font-mono">
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <a href="{{ route('phones.index') }}" class="px-4 py-2.5 rounded-xl text-sm text-slate-400 hover:text-white bg-slate-800 transition-colors">Cancelar</a>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition-all">Guardar Teléfono</button>
            </div>
        </form>
    </div>
</div>
@endsection
