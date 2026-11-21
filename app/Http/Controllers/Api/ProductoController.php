<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProductoRequest;
use App\Http\Requests\Api\UpdateProductoRequest;
use App\Models\Historial;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
    public function index(): JsonResponse
    {
        $productos = Producto::orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => 'Listado de productos obtenido correctamente.',
            'total' => $productos->count(),
            'data' => $productos,
        ], 200);
    }

    public function store(StoreProductoRequest $request): JsonResponse
    {
        $producto = Producto::create($request->validated());

        Historial::create([
            'user_id' => Auth::id(),
            'tipo_accion' => 'Registro API',
            'descripcion' => 'Registró vía API el producto: ' . $producto->nombre . ' (Stock: ' . $producto->stock . ')',
        ]);

        return response()->json([
            'message' => 'Producto creado exitosamente.',
            'data' => $producto,
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $producto = Producto::find($id);

        if (! $producto) {
            return response()->json([
                'message' => 'Producto no encontrado.',
            ], 404);
        }

        return response()->json([
            'message' => 'Detalle del producto.',
            'data' => $producto,
        ], 200);
    }

    public function update(UpdateProductoRequest $request, int $id): JsonResponse
    {
        $producto = Producto::find($id);

        if (! $producto) {
            return response()->json([
                'message' => 'Producto no encontrado.',
            ], 404);
        }

        $producto->update($request->validated());

        Historial::create([
            'user_id' => Auth::id(),
            'tipo_accion' => 'Edición API',
            'descripcion' => 'Actualizó vía API (' . $request->method() . ') el producto: ' . $producto->nombre,
        ]);

        return response()->json([
            'message' => 'Producto actualizado correctamente.',
            'data' => $producto->fresh(),
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $producto = Producto::find($id);

        if (! $producto) {
            return response()->json([
                'message' => 'Producto no encontrado.',
            ], 404);
        }

        $nombre = $producto->nombre;
        $producto->delete();

        Historial::create([
            'user_id' => Auth::id(),
            'tipo_accion' => 'Eliminación API',
            'descripcion' => 'Eliminó vía API el producto: ' . $nombre,
        ]);

        return response()->json([
            'message' => 'Producto eliminado correctamente.',
        ], 200);
    }
}