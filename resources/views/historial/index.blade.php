<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📜 Historial de Movimientos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b-2 border-gray-100">
                                <th class="p-4 font-bold text-gray-600">Fecha y Hora</th>
                                <th class="p-4 font-bold text-gray-600">Usuario</th>
                                <th class="p-4 font-bold text-gray-600">Acción</th>
                                <th class="p-4 font-bold text-gray-600">Descripción</th>
                                <th class="p-4 font-bold text-gray-600 text-center">Ticket</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($movimientos as $movimiento)
                                <tr class="border-b border-gray-50 hover:bg-indigo-50/30 transition">
                                    <td class="p-4 text-sm text-gray-600">
                                        {{ $movimiento->created_at->timezone('America/Mexico_City')->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="p-4">
                                        <span class="font-bold text-indigo-600">{{ $movimiento->user->name }}</span>
                                        <p class="text-[10px] text-gray-400 uppercase tracking-tighter">{{ $movimiento->user->rol }}</p>
                                    </td>
                                    <td class="p-4">
                                        @php
                                            $color = match($movimiento->tipo_accion) {
                                                'Registro' => 'bg-blue-100 text-blue-700',
                                                'Edición' => 'bg-amber-100 text-amber-700',
                                                'Eliminación' => 'bg-red-100 text-red-700',
                                                'Venta' => 'bg-green-100 text-green-700',
                                                default => 'bg-gray-100 text-gray-700'
                                            };
                                            preg_match('/Ticket #(\d+)/i', $movimiento->descripcion, $ticketMatch);
                                            $ticketId = $ticketMatch[1] ?? null;
                                        @endphp
                                        <span class="{{ $color }} px-2 py-1 rounded-md text-xs font-black uppercase">
                                            {{ $movimiento->tipo_accion }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-gray-700 text-sm">
                                        {{ $movimiento->descripcion }}
                                    </td>
                                    <td class="p-4 text-center">
                                        @if($movimiento->tipo_accion === 'Venta' && $ticketId)
                                            <a href="{{ route('pos.ticket', $ticketId) }}"
                                               class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                                                🧾 Descargar
                                            </a>
                                        @else
                                            <span class="text-gray-300 text-xs">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($movimientos->isEmpty())
                        <div class="text-center py-10 text-gray-400 italic">
                            Aún no hay registros en la bitácora.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout><x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📜 Historial de Movimientos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b-2 border-gray-100">
                                <th class="p-4 font-bold text-gray-600">Fecha y Hora</th>
                                <th class="p-4 font-bold text-gray-600">Usuario</th>
                                <th class="p-4 font-bold text-gray-600">Acción</th>
                                <th class="p-4 font-bold text-gray-600">Descripción</th>
                                <th class="p-4 font-bold text-gray-600 text-center">Ticket</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($movimientos as $movimiento)
                                <tr class="border-b border-gray-50 hover:bg-indigo-50/30 transition">
                                    <td class="p-4 text-sm text-gray-600">
                                        {{ $movimiento->created_at->timezone('America/Mexico_City')->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="p-4">
                                        <span class="font-bold text-indigo-600">{{ $movimiento->user->name }}</span>
                                        <p class="text-[10px] text-gray-400 uppercase tracking-tighter">{{ $movimiento->user->rol }}</p>
                                    </td>
                                    <td class="p-4">
                                        @php
                                            $color = match($movimiento->tipo_accion) {
                                                'Registro' => 'bg-blue-100 text-blue-700',
                                                'Edición' => 'bg-amber-100 text-amber-700',
                                                'Eliminación' => 'bg-red-100 text-red-700',
                                                'Venta' => 'bg-green-100 text-green-700',
                                                default => 'bg-gray-100 text-gray-700'
                                            };
                                            preg_match('/Ticket #(\d+)/i', $movimiento->descripcion, $ticketMatch);
                                            $ticketId = $ticketMatch[1] ?? null;
                                        @endphp
                                        <span class="{{ $color }} px-2 py-1 rounded-md text-xs font-black uppercase">
                                            {{ $movimiento->tipo_accion }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-gray-700 text-sm">
                                        {{ $movimiento->descripcion }}
                                    </td>
                                    <td class="p-4 text-center">
                                        @if($movimiento->tipo_accion === 'Venta' && $ticketId)
                                            <a href="{{ route('pos.ticket', $ticketId) }}"
                                               class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                                                🧾 Descargar
                                            </a>
                                        @else
                                            <span class="text-gray-300 text-xs">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($movimientos->isEmpty())
                        <div class="text-center py-10 text-gray-400 italic">
                            Aún no hay registros en la bitácora.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>