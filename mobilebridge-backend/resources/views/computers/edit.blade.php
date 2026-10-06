@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-8 shadow-2xl">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800">
            <div>
                <h1 class="text-xl font-bold text-white">Editar Computador</h1>
                <p class="text-xs text-slate-400 mt-1">Modificar datos del PC Agent: {{ $computer->name }}</p>
            </div>
            <a href="{{ route('computers.index') }}" class="text-xs text-slate-400 hover:text-white">&larr; Volver</a>
        </div>

        <form action="{{ route('computers.update', $computer) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">UUID (Inmutable)</label>
                <input type="text" value="{{ $computer->device_uuid }}" disabled
                    class="w-full px-4 py-2.5 bg-slate-950/50 border border-slate-800 rounded-xl text-slate-400 text-sm font-mono cursor-not-allowed">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Nombre del Dispositivo</label>
                <input type="text" name="name" value="{{ old('name', $computer->name) }}" required
                    class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Descripción</label>
                <textarea name="description" rows="2"
                    class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">{{ old('description', $computer->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">IP Local</label>
                    <input type="text" name="local_ip" value="{{ old('local_ip', $computer->local_ip) }}"
                        class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Puerto TCP</label>
                    <input type="number" name="local_port" value="{{ old('local_port', $computer->local_port) }}" required
                        class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm font-mono">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Estado Forzado</label>
                <select name="status" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
                    <option value="online" {{ $computer->status === 'online' ? 'selected' : '' }}>ONLINE</option>
                    <option value="offline" {{ $computer->status === 'offline' ? 'selected' : '' }}>OFFLINE</option>
                </select>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <a href="{{ route('computers.index') }}" class="px-4 py-2.5 rounded-xl text-sm text-slate-400 hover:text-white bg-slate-800 transition-colors">Cancelar</a>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition-all">Actualizar Computador</button>
            </div>
        </form>
    </div>
</div>
@endsection
