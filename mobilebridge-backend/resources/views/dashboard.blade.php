@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header Summary -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-white">Dashboard Central</h1>
            <p class="text-sm text-slate-400 mt-1">Control Plane &bull; Estado en tiempo real del ecosistema MobileBridge</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('computers.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-xl text-sm shadow-lg shadow-indigo-600/30 transition-all flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Nuevo PC</span>
            </a>
            <a href="{{ route('phones.create') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium rounded-xl text-sm border border-slate-700 transition-all flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Nuevo Teléfono</span>
            </a>
        </div>
    </div>

    <!-- Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Computers Card -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Computadores</span>
                <span class="p-2 rounded-xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <div class="text-3xl font-extrabold text-white">{{ $totalComputers }}</div>
                <div class="flex items-center space-x-2 text-xs">
                    <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 font-medium">{{ $onlineComputers }} Online</span>
                    <span class="px-2 py-0.5 rounded-full bg-slate-800 text-slate-400 font-medium">{{ $offlineComputers }} Offline</span>
                </div>
            </div>
        </div>

        <!-- Phones Card -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Teléfonos</span>
                <span class="p-2 rounded-xl bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <div class="text-3xl font-extrabold text-white">{{ $totalPhones }}</div>
                <div class="flex items-center space-x-2 text-xs">
                    <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 font-medium">{{ $onlinePhones }} Online</span>
                    <span class="px-2 py-0.5 rounded-full bg-slate-800 text-slate-400 font-medium">{{ $offlinePhones }} Offline</span>
                </div>
            </div>
        </div>

        <!-- Active Connections Card -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Conexiones Activas</span>
                <span class="p-2 rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <div class="text-3xl font-extrabold text-white">{{ $activeConnections }}</div>
                <div class="text-xs text-amber-400/80 font-medium">Sesiones en vivo</div>
            </div>
        </div>

        <!-- Registered Users Card -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Usuarios Totales</span>
                <span class="p-2 rounded-xl bg-purple-500/10 text-purple-400 border border-purple-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </span>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <div class="text-3xl font-extrabold text-white">{{ $totalUsers }}</div>
                <div class="text-xs text-slate-400">En la base de datos</div>
            </div>
        </div>
    </div>

    <!-- Dual List: Computers & Phones -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Computers Status Table -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-white flex items-center space-x-2">
                    <span>Estado de Computadores (PC Agents)</span>
                </h2>
                <a href="{{ route('computers.index') }}" class="text-xs text-indigo-400 hover:text-indigo-300 font-medium">Ver todos &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="text-xs uppercase bg-slate-950/60 text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="py-3 px-3">Nombre / UUID</th>
                            <th class="py-3 px-3">IP : Puerto</th>
                            <th class="py-3 px-3">Estado</th>
                            <th class="py-3 px-3">Último Heartbeat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($recentComputers as $pc)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-3">
                                <div class="font-semibold text-white">{{ $pc->name }}</div>
                                <div class="text-xs text-slate-500 font-mono truncate max-w-[150px]">{{ $pc->device_uuid }}</div>
                            </td>
                            <td class="py-3 px-3 font-mono text-xs">
                                {{ $pc->local_ip ?? 'N/A' }}:{{ $pc->local_port }}
                            </td>
                            <td class="py-3 px-3">
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
                            <td class="py-3 px-3 text-xs text-slate-400">
                                {{ $pc->last_seen_at ? $pc->last_seen_at->diffForHumans() : 'Nunca' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-500 text-sm">No hay computadores registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Phones Status Table -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-white flex items-center space-x-2">
                    <span>Estado de Teléfonos Android</span>
                </h2>
                <a href="{{ route('phones.index') }}" class="text-xs text-indigo-400 hover:text-indigo-300 font-medium">Ver todos &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="text-xs uppercase bg-slate-950/60 text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="py-3 px-3">Dispositivo / Modelo</th>
                            <th class="py-3 px-3">Versión Android</th>
                            <th class="py-3 px-3">Estado</th>
                            <th class="py-3 px-3">Último Heartbeat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($recentPhones as $ph)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-3">
                                <div class="font-semibold text-white">{{ $ph->name }}</div>
                                <div class="text-xs text-slate-500 truncate max-w-[150px]">{{ $ph->model ?? 'Genérico' }}</div>
                            </td>
                            <td class="py-3 px-3 text-xs text-slate-300">
                                {{ $ph->android_version ?? 'N/A' }}
                            </td>
                            <td class="py-3 px-3">
                                @if($ph->is_online)
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
                            <td class="py-3 px-3 text-xs text-slate-400">
                                {{ $ph->last_seen_at ? $ph->last_seen_at->diffForHumans() : 'Nunca' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-500 text-sm">No hay teléfonos registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Active & Recent Connections -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 shadow-xl">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-white">Sesiones de Conexión Directa Recientes</h2>
            <a href="{{ route('connections.index') }}" class="text-xs text-indigo-400 hover:text-indigo-300 font-medium">Ver historial completo &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs uppercase bg-slate-950/60 text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="py-3 px-4">Teléfono</th>
                        <th class="py-3 px-4">Computador</th>
                        <th class="py-3 px-4">Estado</th>
                        <th class="py-3 px-4">Inicio</th>
                        <th class="py-3 px-4">Fin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($recentConnections as $c)
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="py-3 px-4 font-medium text-white">{{ $c->phone->name ?? 'Teléfono desconocido' }}</td>
                        <td class="py-3 px-4 text-slate-300">{{ $c->computer->name ?? 'PC desconocido' }}</td>
                        <td class="py-3 px-4">
                            @if($c->status === 'active')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">ACTIVA</span>
                            @elseif($c->status === 'closed')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-800 text-slate-400">FINALIZADA</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-500/20 text-rose-400">FALLIDA</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-xs text-slate-400">{{ $c->started_at ? $c->started_at->format('d/m/Y H:i:s') : '-' }}</td>
                        <td class="py-3 px-4 text-xs text-slate-400">{{ $c->ended_at ? $c->ended_at->format('d/m/Y H:i:s') : 'En curso' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-slate-500 text-sm">No hay registros de conexión aún.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
