<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📦 Registrar Nuevo Producto
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                
                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                        <strong class="font-bold">¡Alto ahí! Revisa lo siguiente:</strong>
                        <ul class="list-disc ml-5 mt-2 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Nombre del Producto *</label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Ej. Cuaderno Profesional Raya">
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Marca *</label>
                            <input type="text" name="marca" value="{{ old('marca') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Ej. Scribe">
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Unidad de Medida *</label>
                            <select name="unidad_medida" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="Pieza" {{ old('unidad_medida') == 'Pieza' ? 'selected' : '' }}>Pieza</option>
                                <option value="Paquete" {{ old('unidad_medida') == 'Paquete' ? 'selected' : '' }}>Paquete</option>
                                <option value="Caja" {{ old('unidad_medida') == 'Caja' ? 'selected' : '' }}>Caja</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Stock Entrante *</label>
                            <input type="number" name="stock" value="{{ old('stock') }}" min="1" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Mínimo 1">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-gray-700 font-bold mb-2">Precio de Venta ($) *</label>
                            <input type="number" step="0.01" name="precio" value="{{ old('precio') }}" min="0.01" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Ej. 25.50">
                        </div>

                        <div class="bg-orange-50 p-4 rounded-md border border-orange-200">
                            <label class="block text-orange-800 font-bold mb-2">Alerta: Stock Mínimo *</label>
                            <input type="number" name="stock_minimo" value="{{ old('stock_minimo', 5) }}" min="0" class="w-full border-orange-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500" required>
                            <p class="text-xs text-orange-600 mt-1">Avisar si quedan menos de esta cantidad.</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-md border border-blue-200">
                            <label class="block text-blue-800 font-bold mb-2">Alerta: Stock Máximo *</label>
                            <input type="number" name="stock_maximo" value="{{ old('stock_maximo', 100) }}" min="1" class="w-full border-blue-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <p class="text-xs text-blue-600 mt-1">Límite máximo que cabe en bodega.</p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block text-gray-700 font-bold mb-2">Fotografía del Producto (Opcional)</label>
                        <input type="file" name="imagen" accept="image/*" capture="environment" class="w-full border border-gray-300 p-2 rounded-md bg-gray-50">
                        <p class="text-xs text-gray-500 mt-1">Solo formatos .jpg o .png permitidos.</p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('productos.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 px-6 rounded-lg transition">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition">
                            💾 Registrar Producto
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>