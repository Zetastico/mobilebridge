@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-8 shadow-2xl">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800">
            <div>
                <h1 class="text-xl font-bold text-white">Registrar Computador (PC Agent)</h1>
                <p class="text-xs text-slate-400 mt-1">Ingresa los datos para registrar un nuevo PC en tu cuenta</p>
            </div>
            <a href="{{ route('computers.index') }}" class="text-xs text-slate-400 hover:text-white">&larr; Volver</a>
        </div>

        <form action="{{ route('computers.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Nombre del Dispositivo</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Ej: PC Oficina Principal" required
                    class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Descripción (Opcional)</label>
                <textarea name="description" rows="2" placeholder="Ej: Computador de escritorio con Windows 11"
                    class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">IP Local (Opcional)</label>
                    <input type="text" name="local_ip" value="{{ old('local_ip', '192.168.1.100') }}" placeholder="192.168.1.x"
                        class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Puerto TCP</label>
                    <input type="number" name="local_port" value="{{ old('local_port', 5050) }}" required
                        class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm font-mono">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">UUID Personalizado (Opcional)</label>
                <input type="text" name="device_uuid" value="{{ old('device_uuid') }}" placeholder="Dejar vacío para autogenerar UUID"
                    class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm font-mono">
                <p class="text-xs text-slate-500 mt-1">El PC Agent autogenerará su propio UUID de forma persistente si se registra por la API.</p>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <a href="{{ route('computers.index') }}" class="px-4 py-2.5 rounded-xl text-sm text-slate-400 hover:text-white bg-slate-800 transition-colors">Cancelar</a>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition-all">Guardar Computador</button>
            </div>
        </form>
    </div>
</div>
@endsection
