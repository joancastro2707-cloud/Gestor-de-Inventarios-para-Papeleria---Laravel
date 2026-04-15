<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 Reporte de Ventas e Inteligencia
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
                    <p class="text-sm text-gray-500 font-bold uppercase">Ventas de Hoy</p>
                    <h3 class="text-3xl font-black text-gray-800">${{ number_format($ventasHoy, 2) }}</h3>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500 font-bold uppercase">Esta Semana</p>
                    <h3 class="text-3xl font-black text-gray-800">${{ number_format($ventasSemana, 2) }}</h3>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-indigo-500">
                    <p class="text-sm text-gray-500 font-bold uppercase">Este Mes (Acumulado)</p>
                    <h3 class="text-3xl font-black text-gray-800">${{ number_format($ventasMes, 2) }}</h3>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-gray-100">
                <h3 class="text-lg font-bold mb-6 text-gray-700 flex items-center gap-2">
                    ⭐ Los 5 Productos más Vendidos (Top Ventas)
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-xs uppercase">
                                <th class="p-4">Posición</th>
                                <th class="p-4">Producto</th>
                                <th class="p-4">Marca</th>
                                <th class="p-4 text-center">Unidades Vendidas</th>
                                <th class="p-4">Estado Stock Actual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productosEstrella as $index => $item)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="p-4">
                                    <span class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="p-4 font-bold text-gray-800">{{ $item->producto->nombre }}</td>
                                <td class="p-4 text-gray-500">{{ $item->producto->marca }}</td>
                                <td class="p-4 text-center font-black text-indigo-600 text-lg">
                                    {{ $item->total_vendido }}
                                </td>
                                <td class="p-4">
                                    @if($item->producto->stock <= $item->producto->stock_minimo)
                                        <span class="text-red-600 font-bold text-xs bg-red-50 px-2 py-1 rounded">⚠️ Reabastecer Pronto</span>
                                    @else
                                        <span class="text-green-600 font-bold text-xs bg-green-50 px-2 py-1 rounded">✅ Suficiente</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($productosEstrella->isEmpty())
                        <div class="text-center py-10 text-gray-400 italic">
                            Aún no hay suficientes ventas para generar un Top 5.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>