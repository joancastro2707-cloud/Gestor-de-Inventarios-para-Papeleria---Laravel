<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Venta #{{ $venta->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace; /* Fuente de ticket real */
            font-size: 12px;
            margin: 0;
            padding: 10px;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .ticket-header { margin-bottom: 15px; border-bottom: 1px dashed #000; padding-bottom: 10px; }
        .ticket-table { margin-bottom: 15px; border-collapse: collapse; width: 100%; }
        .ticket-table th { border-bottom: 1px dashed #000; padding-bottom: 5px; text-align: left; }
        .ticket-table td { padding: 3px 0; }
        .ticket-footer { border-top: 1px dashed #000; padding-top: 10px; margin-top: 15px; }
        .totals { margin-top: 10px; }
    </style>
</head>
<body>

    <div class="ticket-header text-center">
        <h2 style="margin: 0;">Papelería Vanguard-IA</h2>
        <p style="margin: 5px 0 0 0; font-size: 10px;"><i>"Toda una papelería al mejor precio"</i></p>
        <p style="margin: 10px 0 0 0;">Ticket #: <b>{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</b></p>
        <p style="margin: 2px 0;">Fecha: {{ $venta->created_at->format('d/m/Y H:i') }}</p>
        <p style="margin: 2px 0;">Le atendió: {{ $venta->user->name ?? 'Cajero' }}</p>
    </div>

    <table class="ticket-table">
        <thead>
            <tr>
                <th>CANT</th>
                <th>DESCRIPCIÓN</th>
                <th class="text-right">IMPORTE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->detalles as $detalle)
            <tr>
                <td style="vertical-align: top;">{{ $detalle->cantidad }}</td>
                <td>
                    {{ $detalle->producto->nombre }} <br>
                    <small>{{ $detalle->producto->marca }}</small>
                </td>
                <td class="text-right" style="vertical-align: top;">
                    ${{ number_format($detalle->cantidad * $detalle->producto->precio, 2) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals text-right font-bold">
        <p style="font-size: 16px; margin: 0;">TOTAL: ${{ number_format($venta->total, 2) }}</p>
    </div>

    <div class="ticket-footer text-center">
        <p class="font-bold">¡Muchas gracias por tu compra!</p>
        <p>Nos vemos pronto.</p>
    </div>

</body>
</html>