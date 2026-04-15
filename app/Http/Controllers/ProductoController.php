<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Historial;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
    // 1. Mostrar la pantalla del formulario
    public function create()
    {
        return view('productos.create');
    }

    // Mostrar todos los productos en una tabla
    public function index()
    {
        // Traemos todos los productos ordenados por el más reciente
        $productos = Producto::orderBy('created_at', 'desc')->get();
        return view('productos.index', compact('productos'));
    }

    // 2. Revisar y guardar el producto nuevo
    public function store(Request $request)
    {
        // EL GUARDIA DE SEGURIDAD: Revisa que todo cumpla tus reglas exactas
        $datosLimpios = $request->validate([
            'nombre' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'stock' => 'required|integer|gt:0', // 'gt:0' significa Greater Than (Mayor a) cero.
            'precio' => 'required|numeric|gt:0', // Mayor a cero, acepta decimales.
            'stock_minimo' => 'required|integer|min:0',
            'stock_maximo' => 'required|integer|gt:stock_minimo', // El máximo debe ser mayor al mínimo
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Solo acepta .jpg y .png
        ]);

        // Guardamos la foto en la carpeta public/img (si es que subieron una)
        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $nombreFoto = time() . '_' . $archivo->getClientOriginalName();
            $archivo->move(public_path('img/productos'), $nombreFoto);
            $datosLimpios['imagen_url'] = 'img/productos/' . $nombreFoto;
        }

        // Creamos el producto (El ID de 7 dígitos se generará solo por la magia que hicimos en la Misión 2)
        Producto::create($datosLimpios);

        Historial::create([
            'user_id' => Auth::id(),
            'tipo_accion' => 'Registro',
            'descripcion' => 'Registró el nuevo producto: ' . $datosLimpios['nombre'] . ' (Stock inicial: ' . $datosLimpios['stock'] . ')'
        ]);

        // Lo regresamos al panel de control con un mensaje de éxito
        return redirect()->route('dashboard')->with('success', '¡Producto registrado con éxito! El sistema le asignó un ID único.');
    }

    // 1. Mostrar el formulario con los datos ya llenos
    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    // 2. Recibir los cambios y guardarlos
    public function update(Request $request, Producto $producto)
    {
        $datosLimpios = $request->validate([
            'nombre' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'stock' => 'required|integer|gt:0',
            'precio' => 'required|numeric|gt:0',
            'stock_minimo' => 'required|integer|min:0',
            'stock_maximo' => 'required|integer|gt:stock_minimo',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $nombreFoto = time() . '_' . $archivo->getClientOriginalName();
            $archivo->move(public_path('img/productos'), $nombreFoto);
            $datosLimpios['imagen_url'] = 'img/productos/' . $nombreFoto;
        }

        $producto->update($datosLimpios);

        Historial::create([
            'user_id' => Auth::id(),
            'tipo_accion' => 'Edición',
            'descripcion' => 'Editó el producto: ' . $producto->nombre
        ]);

        return redirect()->route('productos.index')->with('success', '¡Producto actualizado correctamente!');
    }

    // 3. Eliminar el producto para siempre
    public function destroy(Producto $producto)
    {
        Historial::create([
            'user_id' => Auth::id(),
            'tipo_accion' => 'Eliminación',
            'descripcion' => 'Eliminó permanentemente el producto: ' . $producto->nombre
        ]);
        
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado del inventario.');
    }
}