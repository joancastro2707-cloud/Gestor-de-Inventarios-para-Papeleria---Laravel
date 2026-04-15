<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🗃️ Inventario de Papelería
            </h2>
            <a href="{{ route('productos.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow transition duration-200">
                + Nuevo Producto
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
                <input type="text" id="buscador-inventario" placeholder="🔍 Buscar por nombre, marca o código de barras..." class="w-full border-2 border-gray-300 rounded-lg p-4 text-lg focus:outline-none focus:border-indigo-600 transition shadow-sm font-bold text-gray-700">
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto p-6">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 border-b-2 border-gray-200">
                                <th class="p-3 font-bold">ID / Foto</th>
                                <th class="p-3 font-bold">Producto</th>
                                <th class="p-3 font-bold">Precio</th>
                                <th class="p-3 font-bold text-center">Estado del Stock</th>
                                <th class="p-3 font-bold text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productos as $producto)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition duration-150">
                                    
                                    <td class="p-3 flex items-center gap-3">
                                        @if($producto->imagen_url)
                                            <img src="{{ asset($producto->imagen_url) }}" alt="{{ $producto->nombre }}" class="w-16 h-16 object-cover rounded-lg border-2 border-gray-200 shadow-sm">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 text-[10px] shadow-sm border">Sin foto</div>
                                        @endif
                                        <span class="font-mono text-indigo-600 font-bold bg-indigo-50 px-2 py-1 rounded text-xs">
                                            #{{ $producto->codigo_producto }}
                                        </span>
                                    </td>

                                    <td class="p-3">
                                        <p class="font-bold text-gray-800">{{ $producto->nombre }}</p>
                                        <p class="text-xs text-gray-500 uppercase font-semibold">{{ $producto->marca }}</p>
                                    </td>

                                    <td class="p-3 font-bold text-green-600 text-lg">
                                        ${{ number_format($producto->precio, 2) }}
                                    </td>

                                    <td class="p-3 text-center">
                                        @if($producto->stock < $producto->stock_minimo)
                                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full font-bold text-xs border border-red-300">
                                                ¡Stock Bajo! ({{ $producto->stock }} pzas)
                                            </span>
                                        @elseif($producto->stock > $producto->stock_maximo)
                                            <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full font-bold text-xs border border-orange-300">
                                                Exceso de Stock ({{ $producto->stock }} pzas)
                                            </span>
                                        @else
                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full font-bold text-xs border border-green-300">
                                                Óptimo ({{ $producto->stock }} pzas)
                                            </span>
                                        @endif
                                        <div class="text-[10px] text-gray-400 mt-1 font-semibold italic">Min: {{ $producto->stock_minimo }} | Max: {{ $producto->stock_maximo }}</div>
                                    </td>

                                    <td class="p-3 text-center">
                                        @if(auth()->user()->rol === 'Administrador')
                                            <div class="flex justify-center gap-3">
                                                <a href="{{ route('productos.edit', $producto) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg text-sm shadow-md transition duration-200 transform hover:scale-105">
                                                    ✏️ Editar
                                                </a>

                                                <form action="{{ route('productos.destroy', $producto) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este producto? Esta acción no se puede deshacer.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg text-sm shadow-md transition duration-200 transform hover:scale-105">
                                                        🗑️ Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="bg-gray-50 border border-gray-200 rounded-md py-2 px-3 inline-flex items-center gap-2 text-gray-400 text-xs italic">
                                                <span>🔒 Sin permisos de edición</span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($productos->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-gray-400 text-lg">Aún no hay productos registrados en la papelería.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buscador = document.getElementById('buscador-inventario');
            const filas = document.querySelectorAll('tbody tr');

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