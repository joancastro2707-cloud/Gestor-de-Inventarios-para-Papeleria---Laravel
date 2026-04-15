<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                👥 Gestión de Personal
            </h2>
            <a href="{{ route('usuarios.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-bold text-sm shadow-md transition duration-200">
                + Nuevo Empleado
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 shadow-sm">
                    <strong>¡Éxito!</strong> {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 mt-4">
                <input type="text" id="buscador-usuarios" placeholder="🔍 Buscar por nombre, apellido, correo o código..." class="w-full border-2 border-gray-300 rounded-lg p-4 text-lg focus:outline-none focus:border-indigo-600 transition shadow-sm font-bold text-gray-700">
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="overflow-x-auto p-6">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 border-b-2 border-gray-200">
                                <th class="p-3 font-bold">Código</th>
                                <th class="p-3 font-bold">Nombre Completo</th>
                                <th class="p-3 font-bold">Rol</th>
                                <th class="p-3 font-bold">Correo</th>
                                <th class="p-3 font-bold text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-usuarios">
                            @foreach($usuarios as $user)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition duration-150">
                                    <td class="p-3">
                                        <span class="font-mono text-indigo-600 font-bold bg-indigo-50 px-2 py-1 rounded text-xs border border-indigo-100">
                                            #{{ $user->codigo_empleado }}
                                        </span>
                                    </td>

                                    <td class="p-3">
                                        <p class="font-bold text-gray-800">{{ $user->name }} {{ $user->apellido }}</p>
                                        @if($user->id === auth()->id())
                                            <span class="text-[10px] bg-green-100 text-green-700 px-1 rounded font-black uppercase">Tú</span>
                                        @endif
                                    </td>

                                    <td class="p-3">
                                        <span class="{{ $user->rol == 'Administrador' ? 'bg-purple-100 text-purple-700 border-purple-200' : 'bg-blue-100 text-blue-700 border-blue-200' }} px-3 py-1 rounded-full text-xs font-black border uppercase">
                                            {{ $user->rol }}
                                        </span>
                                    </td>

                                    <td class="p-3 text-gray-600 text-sm">
                                        {{ $user->email }}
                                    </td>

                                    <td class="p-3 text-center">
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('usuarios.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas revocar el acceso a este empleado?');">
                                                @csrf 
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 font-bold py-1 px-3 rounded-md text-xs transition border border-red-200">
                                                    🗑️ Eliminar
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 text-xs italic">Protegido</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($usuarios->isEmpty())
                        <div class="text-center py-10 text-gray-500">
                            No hay personal registrado.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buscador = document.getElementById('buscador-usuarios');
            const filas = document.querySelectorAll('#tabla-usuarios tr');

            buscador.addEventListener('input', function(e) {
                let palabraEscrita = e.target.value.toLowerCase().trim();

                filas.forEach(fila => {
                    let textoFila = fila.innerText.toLowerCase();
                    if(textoFila.includes(palabraEscrita)) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                });
            });
        });
    </script>
</x-app-layout>