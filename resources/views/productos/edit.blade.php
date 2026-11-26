<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ✏️ Editar Producto: {{ $producto->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-8 border-t-4 border-blue-600">
                
                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                        <strong class="font-bold">Revisa lo siguiente:</strong>
                        <ul class="list-disc ml-5 mt-2 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('productos.update', $producto) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <x-input-label for="nombre" value="Nombre del Producto" />
                            <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" :value="old('nombre', $producto->nombre)" required />
                        </div>

                        <div>
                            <x-input-label for="marca" value="Marca" />
                            <x-text-input id="marca" name="marca" type="text" class="mt-1 block w-full" :value="old('marca', $producto->marca)" required />
                        </div>

                        <div>
                            <x-input-label for="unidad_medida" value="Unidad de Medida" />
                            @php $unidadActual = old('unidad_medida', $producto->unidad_medida ?? 'Pieza'); @endphp
                            <select id="unidad_medida" name="unidad_medida" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="Pieza" {{ $unidadActual == 'Pieza' ? 'selected' : '' }}>Pieza</option>
                                <option value="Paquete" {{ $unidadActual == 'Paquete' ? 'selected' : '' }}>Paquete</option>
                                <option value="Caja" {{ $unidadActual == 'Caja' ? 'selected' : '' }}>Caja</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="stock" value="Stock Actual" />
                            <x-text-input id="stock" name="stock" type="number" class="mt-1 block w-full" :value="old('stock', $producto->stock)" required />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="precio" value="Precio de Venta ($)" />
                            <x-text-input id="precio" name="precio" type="number" step="0.01" class="mt-1 block w-full" :value="old('precio', $producto->precio)" required />
                        </div>

                        <div>
                            <x-input-label for="stock_minimo" value="Alerta Stock Mínimo" />
                            <x-text-input id="stock_minimo" name="stock_minimo" type="number" class="mt-1 block w-full" :value="old('stock_minimo', $producto->stock_minimo)" required />
                        </div>

                        <div>
                            <x-input-label for="stock_maximo" value="Alerta Stock Máximo" />
                            <x-text-input id="stock_maximo" name="stock_maximo" type="number" class="mt-1 block w-full" :value="old('stock_maximo', $producto->stock_maximo)" required />
                        </div>
                    </div>

                    <div class="mb-8 p-4 bg-gray-50 rounded-lg border">
                        <x-input-label value="Imagen del Producto" class="mb-2" />
                        <div class="flex items-center gap-4">
                            @if($producto->imagen_url)
                                <img src="{{ asset($producto->imagen_url) }}" class="w-20 h-20 object-cover rounded shadow">
                            @endif
                            <div class="flex-1">
                                <input type="file" name="imagen" accept="image/*" capture="environment" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                                <p class="text-xs text-gray-400 mt-1 italic">Sube una nueva foto solo si deseas cambiar la actual.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 border-t pt-6">
                        <a href="{{ route('productos.index') }}" class="text-gray-600 hover:underline font-medium">
                            Cancelar
                        </a>
                        
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition transform hover:scale-105">
                            💾 Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>