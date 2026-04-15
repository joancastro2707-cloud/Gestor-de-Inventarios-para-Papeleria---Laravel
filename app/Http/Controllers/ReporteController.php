<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReporteController extends Controller
{
    public function index()
    {
        // Seguridad: Solo el Admin ve dinero
        if (Auth::user()->rol !== 'Administrador') {
            return redirect()->route('dashboard');
        }

        $hoy = Carbon::today();
        $estaSemana = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
        $esteMes = Carbon::now()->month;

        // 1. Cálculo de Dinero Generado
        $ventasHoy = Venta::whereDate('created_at', $hoy)->sum('total');
        $ventasSemana = Venta::whereBetween('created_at', $estaSemana)->sum('total');
        $ventasMes = Venta::whereMonth('created_at', $esteMes)->sum('total');

        // 2. Los 5 Productos Estrella (Top 5)
        // Agrupamos por producto_id y sumamos la cantidad vendida
        $productosEstrella = DetalleVenta::select('producto_id', DB::raw('SUM(cantidad) as total_vendido'))
            ->with('producto') // Traemos el nombre y marca del producto
            ->groupBy('producto_id')
            ->orderBy('total_vendido', 'desc')
            ->take(5)
            ->get();

        return view('reportes.index', compact(
            'ventasHoy', 
            'ventasSemana', 
            'ventasMes', 
            'productosEstrella'
        ));
    }
}