@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-white">Usuarios del Sistema</h1>
            <p class="text-sm text-slate-400 mt-1">Administración de cuentas de usuario de MobileBridge</p>
        </div>
        <a href="{{ route('users.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-xl text-sm shadow-lg shadow-indigo-600/30 transition-all flex items-center space-x-2 self-start">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Crear Usuario</span>
        </a>
    </div>

    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs uppercase bg-slate-950/70 text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="py-3.5 px-4">#</th>
                        <th class="py-3.5 px-4">Nombre</th>
                        <th class="py-3.5 px-4">Correo</th>
                        <th class="py-3.5 px-4">Computadores</th>
                        <th class="py-3.5 px-4">Teléfonos</th>
                        <th class="py-3.5 px-4">Creado</th>
                        <th class="py-3.5 px-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($users as $u)
                    <tr class="hover:bg-slate-800/40 transition-colors {{ $u->id === auth()->id() ? 'bg-indigo-900/10 border-l-2 border-indigo-500' : '' }}">
                        <td class="py-3.5 px-4 text-slate-500 text-xs">{{ $u->id }}</td>
                        <td class="py-3.5 px-4">
                            <div class="font-semibold text-white flex items-center space-x-2">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-indigo-600 to-cyan-400 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <span>{{ $u->name }}</span>
                                @if($u->id === auth()->id())
                                    <span class="px-1.5 py-0.5 text-[10px] rounded-md bg-indigo-600/20 text-indigo-300 border border-indigo-500/30 font-medium">Tú</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3.5 px-4 text-slate-300">{{ $u->email }}</td>
                        <td class="py-3.5 px-4">
                            <span class="px-2 py-0.5 rounded-full text-xs bg-slate-800 text-slate-300 font-medium">{{ $u->computers_count }} PCs</span>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="px-2 py-0.5 rounded-full text-xs bg-slate-800 text-slate-300 font-medium">{{ $u->phones_count }} teléfonos</span>
                        </td>
                        <td class="py-3.5 px-4 text-xs text-slate-400">
                            {{ $u->created_at->format('d/m/Y') }}
                        </td>
                        <td class="py-3.5 px-4 text-right space-x-2">
                            <a href="{{ route('users.edit', $u) }}" class="px-2.5 py-1 text-xs font-medium rounded-lg bg-indigo-500/10 text-indigo-400 hover:bg-indigo-500/20 border border-indigo-500/30 transition-colors">Editar</a>
                            @if($u->id !== auth()->id())
                            <form action="{{ route('users.destroy', $u) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1 text-xs font-medium rounded-lg bg-rose-500/10 text-rose-400 hover:bg-rose-500/20 border border-rose-500/30 transition-colors">Eliminar</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-500">No hay usuarios registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
