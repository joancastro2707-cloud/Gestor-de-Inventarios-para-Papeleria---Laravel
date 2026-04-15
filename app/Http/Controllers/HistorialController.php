<?php

namespace App\Http\Controllers;

use App\Models\Historial;
use Illuminate\Http\Request;

class HistorialController extends Controller
{
    public function index()
    {
        // Traemos los movimientos, el más reciente primero (latest)
        // Usamos 'with' para traer también el nombre del usuario de un solo golpe
        $movimientos = Historial::with('user')->latest()->get();
        
        return view('historial.index', compact('movimientos'));
    }
}