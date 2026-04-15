<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\DetalleVenta;
use Illuminate\Support\Facades\Auth;
use App\Models\Historial;

class PosController extends Controller
{
    // 1. Mostrar la pantalla dividida (Catálogo y Ticket)
    public function index()
    {
        // Solo traemos los productos que tienen stock disponible
        $productos = Producto::where('stock', '>', 0)->get();
        // Leemos la memoria temporal para ver qué hay en el ticket actual
        $carrito = session()->get('carrito', []);
        
        // Calculamos el Gran Total sumando (Precio * Cantidad) de cada artículo
        $total = 0;
        foreach($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        return view('pos.index', compact('productos', 'carrito', 'total'));
    }

    // 2. Agregar un producto al Ticket
    public function agregar(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1' // Regla: Solo números enteros mayores a 0
        ]);

        $producto = Producto::findOrFail($request->producto_id);

        // Regla: No podemos vender más de lo que tenemos en bodega
        if ($producto->stock < $request->cantidad) {
            return back()->withErrors(['stock' => 'No hay suficiente stock de ' . $producto->nombre . '. Solo quedan ' . $producto->stock . ' piezas.']);
        }

        $carrito = session()->get('carrito', []);

        // Si el producto ya está en el ticket, le sumamos la cantidad
        if(isset($carrito[$producto->id])) {
            $nuevaCantidad = $carrito[$producto->id]['cantidad'] + $request->cantidad;
            // Volvemos a verificar el stock con la nueva cantidad total
            if ($producto->stock < $nuevaCantidad) {
                return back()->withErrors(['stock' => 'El total en el ticket supera el stock disponible.']);
            }
            $carrito[$producto->id]['cantidad'] = $nuevaCantidad;
        } else {
            // Si es nuevo, lo registramos en el ticket
            $carrito[$producto->id] = [
                'producto_id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => $request->cantidad
            ];
        }

        session()->put('carrito', $carrito);
        return back();
    }

    // 3. Quitar un producto del Ticket
    public function quitar($id)
    {
        $carrito = session()->get('carrito', []);
        if(isset($carrito[$id])) {
            unset($carrito[$id]);
            session()->put('carrito', $carrito);
        }
        return back();
    }

    // 4. Procesar la Venta (Cobrar)
    public function cobrar()
    {
        $carrito = session()->get('carrito', []);

        if(count($carrito) == 0) {
            return back()->withErrors(['carrito' => 'El ticket está vacío.']);
        }

        $total = 0;
        foreach($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        // Creamos el Ticket General (Venta)
        $venta = Venta::create([
            'user_id' => Auth::id(), // Guardamos QUIÉN hizo la venta (Admin o Vendedor)
            'total' => $total
        ]);

        // Guardamos los Detalles y Descontamos el Stock
        foreach($carrito as $item) {
            DetalleVenta::create([
                'venta_id' => $venta->id,
                'producto_id' => $item['producto_id'],
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $item['precio'],
                'subtotal' => $item['precio'] * $item['cantidad']
            ]);

            // Descontar del inventario
            $producto = Producto::find($item['producto_id']);
            $producto->decrement('stock', $item['cantidad']);
        }

        // Vaciamos el ticket de la memoria temporal
        session()->forget('carrito');

        Historial::create([
            'user_id' => Auth::id(),
            'tipo_accion' => 'Venta',
            'descripcion' => 'Realizó una venta en Caja por un total de $' . number_format($total, 2) . ' (Ticket #' . $venta->id . ')'
        ]);

        return redirect()->route('pos.index')->with('success', '¡Venta procesada con éxito! Ticket #' . $venta->id);
    }
}