@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-8 shadow-2xl">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800">
            <div>
                <h1 class="text-xl font-bold text-white">Editar Usuario</h1>
                <p class="text-xs text-slate-400 mt-1">{{ $user->email }}</p>
            </div>
            <a href="{{ route('users.index') }}" class="text-xs text-slate-400 hover:text-white">&larr; Volver</a>
        </div>

        <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Nombre Completo</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Correo Electrónico</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Nueva Contraseña <span class="text-slate-500 normal-case font-normal">(dejar vacío para no cambiar)</span></label>
                <input type="password" name="password"
                    class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
            </div>
            <div class="pt-4 flex items-center justify-end space-x-3">
                <a href="{{ route('users.index') }}" class="px-4 py-2.5 rounded-xl text-sm text-slate-400 hover:text-white bg-slate-800 transition-colors">Cancelar</a>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition-all">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
@endsection
