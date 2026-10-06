@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-white">Computadores (PC Agents)</h1>
            <p class="text-sm text-slate-400 mt-1">Gestión de agentes de escritorio registrados en tu cuenta</p>
        </div>
        <a href="{{ route('computers.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-xl text-sm shadow-lg shadow-indigo-600/30 transition-all flex items-center space-x-2 self-start">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Registrar Nuevo PC</span>
        </a>
    </div>

    <!-- Filters -->
    <div class="flex items-center space-x-2 text-xs">
        <span class="text-slate-400 font-medium">Filtrar:</span>
        <a href="{{ route('computers.index') }}" class="px-3 py-1 rounded-lg {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">Todos</a>
        <a href="{{ route('computers.index', ['status' => 'online']) }}" class="px-3 py-1 rounded-lg {{ request('status') === 'online' ? 'bg-emerald-600 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">Solo Online</a>
        <a href="{{ route('computers.index', ['status' => 'offline']) }}" class="px-3 py-1 rounded-lg {{ request('status') === 'offline' ? 'bg-rose-600 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">Solo Offline</a>
    </div>

    <!-- Table -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs uppercase bg-slate-950/70 text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="py-3.5 px-4">Nombre / Descripción</th>
                        <th class="py-3.5 px-4">UUID del Dispositivo</th>
                        <th class="py-3.5 px-4">IP Local : Puerto</th>
                        <th class="py-3.5 px-4">Estado</th>
                        <th class="py-3.5 px-4">Último Heartbeat</th>
                        <th class="py-3.5 px-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($computers as $pc)
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="py-3.5 px-4">
                            <div class="font-semibold text-white">{{ $pc->name }}</div>
                            <div class="text-xs text-slate-400">{{ $pc->description ?? 'Sin descripción' }}</div>
                        </td>
                        <td class="py-3.5 px-4 font-mono text-xs text-slate-300">
                            {{ $pc->device_uuid }}
                        </td>
                        <td class="py-3.5 px-4 font-mono text-xs">
                            {{ $pc->local_ip ?? '127.0.0.1' }}:{{ $pc->local_port }}
                        </td>
                        <td class="py-3.5 px-4">
                            @if($pc->is_online)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/40">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
                                    ONLINE
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-800 text-slate-400 border border-slate-700">
                                    OFFLINE
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-xs text-slate-400">
                            {{ $pc->last_seen_at ? $pc->last_seen_at->format('d/m/Y H:i:s') : 'Nunca' }}
                        </td>
                        <td class="py-3.5 px-4 text-right space-x-2">
                            <a href="{{ route('computers.edit', $pc) }}" class="px-2.5 py-1 text-xs font-medium rounded-lg bg-indigo-500/10 text-indigo-400 hover:bg-indigo-500/20 border border-indigo-500/30 transition-colors">Editar</a>
                            <form action="{{ route('computers.destroy', $pc) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este PC Agent?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1 text-xs font-medium rounded-lg bg-rose-500/10 text-rose-400 hover:bg-rose-500/20 border border-rose-500/30 transition-colors">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-500">No hay computadores registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($computers->hasPages())
        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            {{ $computers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
