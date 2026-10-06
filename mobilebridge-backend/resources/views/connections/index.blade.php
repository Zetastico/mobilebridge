@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-white">Historial de Conexiones</h1>
        <p class="text-sm text-slate-400 mt-1">Registro completo de sesiones de comunicación Android &harr; PC</p>
    </div>

    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs uppercase bg-slate-950/70 text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="py-3.5 px-4">#</th>
                        <th class="py-3.5 px-4">Teléfono</th>
                        <th class="py-3.5 px-4">Computador</th>
                        <th class="py-3.5 px-4">IP Local Destino</th>
                        <th class="py-3.5 px-4">Estado</th>
                        <th class="py-3.5 px-4">Inicio</th>
                        <th class="py-3.5 px-4">Fin</th>
                        <th class="py-3.5 px-4">Duración</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($connections as $c)
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="py-3.5 px-4 text-xs text-slate-500">{{ $c->id }}</td>
                        <td class="py-3.5 px-4">
                            <div class="font-medium text-white">{{ $c->phone->name ?? 'Desconocido' }}</div>
                            <div class="text-xs text-slate-500">{{ $c->phone->model ?? '' }}</div>
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="font-medium text-slate-200">{{ $c->computer->name ?? 'Desconocido' }}</div>
                            <div class="text-xs text-slate-500 font-mono">{{ $c->computer->local_ip ?? '' }}:{{ $c->computer->local_port ?? '' }}</div>
                        </td>
                        <td class="py-3.5 px-4 font-mono text-xs text-indigo-300">
                            {{ $c->computer->local_ip ?? 'N/A' }}:{{ $c->computer->local_port ?? 5050 }}
                        </td>
                        <td class="py-3.5 px-4">
                            @if($c->status === 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/40">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
                                    ACTIVA
                                </span>
                            @elseif($c->status === 'closed')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-800 text-slate-400">FINALIZADA</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-500/20 text-rose-400">FALLIDA</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-xs text-slate-400">
                            {{ $c->started_at ? $c->started_at->format('d/m/Y H:i:s') : '-' }}
                        </td>
                        <td class="py-3.5 px-4 text-xs text-slate-400">
                            {{ $c->ended_at ? $c->ended_at->format('d/m/Y H:i:s') : 'En curso...' }}
                        </td>
                        <td class="py-3.5 px-4 text-xs text-slate-400">
                            @if($c->started_at && $c->ended_at)
                                {{ $c->started_at->diffForHumans($c->ended_at, true) }}
                            @elseif($c->started_at)
                                <span class="text-emerald-400">{{ $c->started_at->diffForHumans() }}</span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-500">No hay registros de conexión aún. Las sesiones se generan cuando un teléfono se conecta directamente a un PC por TCP.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($connections->hasPages())
        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            {{ $connections->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
