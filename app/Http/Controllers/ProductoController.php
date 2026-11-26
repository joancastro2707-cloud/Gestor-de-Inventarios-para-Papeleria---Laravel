<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Historial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class ProductoController extends Controller
{
    public function create()
    {
        return view('productos.create');
    }

    public function index()
    {
        $productos = Producto::orderBy('created_at', 'desc')->get();
        return view('productos.index', compact('productos'));
    }

    public function store(Request $request)
    {
        $datosLimpios = $request->validate([
            'nombre' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'unidad_medida' => 'nullable|string|max:50',
            'stock' => 'required|integer|gt:0',
            'precio' => 'required|numeric|gt:0',
            'stock_minimo' => 'required|integer|min:0',
            'stock_maximo' => 'required|integer|gt:stock_minimo',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096'
        ]);

        $unidad = $datosLimpios['unidad_medida'] ?? 'Pieza';

        if (Schema::hasColumn('productos', 'unidad_medida')) {
            $datosLimpios['unidad_medida'] = $unidad;
        } else {
            unset($datosLimpios['unidad_medida']);
        }

        if ($request->hasFile('imagen')) {
            $directorio = public_path('img/productos');
            if (!File::exists($directorio)) {
                File::makeDirectory($directorio, 0755, true);
            }
            $archivo = $request->file('imagen');
            $nombreFoto = time() . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '_', $archivo->getClientOriginalName());
            $archivo->move($directorio, $nombreFoto);
            $datosLimpios['imagen_url'] = 'img/productos/' . $nombreFoto;
        }

        Producto::create($datosLimpios);

        Historial::create([
            'user_id' => Auth::id(),
            'tipo_accion' => 'Registro',
            'descripcion' => 'Registró el nuevo producto: ' . $datosLimpios['nombre'] . ' (' . $unidad . ', Stock inicial: ' . $datosLimpios['stock'] . ')'
        ]);

        return redirect()->route('productos.index')->with('success', '¡Producto registrado con éxito! El sistema le asignó un ID único.');
    }

    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $datosLimpios = $request->validate([
            'nombre' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'unidad_medida' => 'nullable|string|max:50',
            'stock' => 'required|integer|gt:0',
            'precio' => 'required|numeric|gt:0',
            'stock_minimo' => 'required|integer|min:0',
            'stock_maximo' => 'required|integer|gt:stock_minimo',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096'
        ]);

        if (Schema::hasColumn('productos', 'unidad_medida')) {
            $datosLimpios['unidad_medida'] = $datosLimpios['unidad_medida'] ?? ($producto->unidad_medida ?? 'Pieza');
        } else {
            unset($datosLimpios['unidad_medida']);
        }

        if ($request->hasFile('imagen')) {
            $directorio = public_path('img/productos');
            if (!File::exists($directorio)) {
                File::makeDirectory($directorio, 0755, true);
            }
            $archivo = $request->file('imagen');
            $nombreFoto = time() . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '_', $archivo->getClientOriginalName());
            $archivo->move($directorio, $nombreFoto);
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