@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-8 shadow-2xl">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800">
            <div>
                <h1 class="text-xl font-bold text-white">Editar Teléfono</h1>
                <p class="text-xs text-slate-400 mt-1">{{ $phone->name }}</p>
            </div>
            <a href="{{ route('phones.index') }}" class="text-xs text-slate-400 hover:text-white">&larr; Volver</a>
        </div>

        <form action="{{ route('phones.update', $phone) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">UUID (Inmutable)</label>
                <input type="text" value="{{ $phone->device_uuid }}" disabled
                    class="w-full px-4 py-2.5 bg-slate-950/50 border border-slate-800 rounded-xl text-slate-400 text-sm font-mono cursor-not-allowed">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Nombre del Teléfono</label>
                <input type="text" name="name" value="{{ old('name', $phone->name) }}" required
                    class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Modelo</label>
                <input type="text" name="model" value="{{ old('model', $phone->model) }}"
                    class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Versión Android</label>
                <input type="text" name="android_version" value="{{ old('android_version', $phone->android_version) }}"
                    class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Estado Forzado</label>
                <select name="status" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
                    <option value="online" {{ $phone->status === 'online' ? 'selected' : '' }}>ONLINE</option>
                    <option value="offline" {{ $phone->status === 'offline' ? 'selected' : '' }}>OFFLINE</option>
                </select>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <a href="{{ route('phones.index') }}" class="px-4 py-2.5 rounded-xl text-sm text-slate-400 hover:text-white bg-slate-800 transition-colors">Cancelar</a>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition-all">Actualizar Teléfono</button>
            </div>
        </form>
    </div>
</div>
@endsection
