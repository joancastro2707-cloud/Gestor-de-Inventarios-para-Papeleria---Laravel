<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🏪 Punto de Venta (Caja)
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6">
            
            <div class="md:w-2/3 bg-white shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-6 relative">
                    <input type="text" id="buscador-pos" placeholder="🔍 Escribe para buscar por nombre, marca o #código..." class="w-full border-2 border-indigo-200 rounded-lg p-3 text-base focus:outline-none focus:border-indigo-600 transition shadow-sm font-bold text-gray-700">
                </div>

                @if ($errors->has('stock'))
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm font-bold">
                        ⚠️ {{ $errors->first('stock') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="catalogo-productos">
                    @foreach($productos as $producto)
                        <div class="producto-card border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition bg-white flex flex-col" 
                             data-nombre="{{ strtolower($producto->nombre) }}" 
                             data-marca="{{ strtolower($producto->marca) }}" 
                             data-codigo="{{ strtolower($producto->codigo_producto) }}">
                            
                            <div class="h-28 bg-white border-b flex items-center justify-center p-2 relative">
                                <span class="absolute top-2 left-2 bg-indigo-600 text-white text-[9px] font-black px-2 py-1 rounded shadow-sm">
                                    #{{ $producto->codigo_producto }}
                                </span>

                                @if($producto->imagen_url)
                                    <img src="{{ asset($producto->imagen_url) }}" alt="{{ $producto->nombre }}" class="h-full w-full object-contain">
                                @else
                                    <span class="text-gray-300 font-bold text-xs">Sin Imagen</span>
                                @endif
                            </div>

                            <div class="p-3 flex-1 flex flex-col justify-between">
                                <div>
                                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-1">{{ $producto->marca }}</p>
                                    <h4 class="font-bold text-gray-800 leading-tight mb-2 text-xs h-8 overflow-hidden">{{ $producto->nombre }}</h4>
                                    
                                    <div class="flex justify-between items-center mb-3">
                                        <p class="text-lg font-black text-green-600">${{ number_format($producto->precio, 2) }}</p>
                                        <p class="text-[9px] text-gray-500 font-bold bg-gray-100 px-2 py-1 rounded">Stock: {{ $producto->stock }}</p>
                                    </div>
                                </div>
                                
                                <form action="{{ route('pos.agregar') }}" method="POST" class="flex gap-2">
                                    @csrf
                                    <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                                    <input type="number" name="cantidad" value="1" min="1" step="1" class="w-14 h-8 text-sm border-gray-300 rounded shadow-sm text-center font-bold px-1" required>
                                    <button type="submit" class="flex-1 h-8 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded transition shadow-sm flex items-center justify-center gap-1">
                                        🛒 Agregar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div id="mensaje-vacio" class="hidden text-center py-10 text-gray-500 font-bold text-lg">
                    No se encontraron productos con esa búsqueda. 🕵️‍♂️
                </div>
            </div>

            <div class="md:w-1/3 bg-white shadow-sm sm:rounded-lg p-6 flex flex-col h-fit sticky top-6 border-t-4 border-indigo-600">
                <h3 class="text-lg font-bold mb-4 text-gray-700 border-b pb-2">Ticket Actual</h3>

                @if(session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm font-bold text-center">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                @if ($errors->has('carrito'))
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm font-bold text-center">
                        {{ $errors->first('carrito') }}
                    </div>
                @endif

                <div class="flex-1 overflow-y-auto min-h-[300px] max-h-[500px] mb-4">
                    @if(count($carrito) > 0)
                        <ul class="divide-y divide-gray-200">
                            @foreach($carrito as $id => $item)
                                <li class="py-3 flex justify-between items-start">
                                    <div class="pr-2">
                                        <p class="text-sm font-bold text-gray-800 leading-tight">{{ $item['nombre'] }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $item['cantidad'] }}x a ${{ number_format($item['precio'], 2) }}</p>
                                    </div>
                                    <div class="text-right flex flex-col items-end">
                                        <p class="text-sm font-black text-gray-800">${{ number_format($item['precio'] * $item['cantidad'], 2) }}</p>
                                        <form action="{{ route('pos.quitar', $id) }}" method="POST" class="mt-1">
                                            @csrf
                                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-bold underline px-1 rounded">Quitar</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="h-full flex items-center justify-center text-gray-400 italic text-sm text-center">
                            Aún no hay productos en el ticket.<br>Usa el buscador para empezar a cobrar.
                        </div>
                    @endif
                </div>

                <div class="border-t-2 border-dashed border-gray-300 pt-4 mb-6">
                    <div class="flex justify-between items-center mb-2 text-xl">
                        <span class="font-bold text-gray-700">TOTAL:</span>
                        <span class="font-black text-green-600">${{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <form action="{{ route('pos.cobrar') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-black py-4 rounded-lg shadow-lg text-lg uppercase tracking-wide transition duration-200 transform hover:scale-105 disabled:opacity-50" {{ count($carrito) == 0 ? 'disabled' : '' }}>
                        💵 Cobrar Venta
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buscador = document.getElementById('buscador-pos');
            const tarjetas = document.querySelectorAll('.producto-card');
            const mensajeVacio = document.getElementById('mensaje-vacio');

            buscador.addEventListener('input', function(e) {
                let palabraEscrita = e.target.value.toLowerCase().trim();
                let tarjetasVisibles = 0;

                tarjetas.forEach(tarjeta => {
                    let nombre = tarjeta.getAttribute('data-nombre');
                    let marca = tarjeta.getAttribute('data-marca');
                    let codigo = tarjeta.getAttribute('data-codigo');

                    if(nombre.includes(palabraEscrita) || marca.includes(palabraEscrita) || codigo.includes(palabraEscrita)) {
                        tarjeta.style.display = 'flex';
                        tarjetasVisibles++;
                    } else {
                        tarjeta.style.display = 'none';
                    }
                });

                if(tarjetasVisibles === 0) {
                    mensajeVacio.classList.remove('hidden');
                } else {
                    mensajeVacio.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>