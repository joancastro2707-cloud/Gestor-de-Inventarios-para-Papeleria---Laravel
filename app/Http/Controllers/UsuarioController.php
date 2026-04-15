<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    /**
     * Ver lista de empleados (Solo Admins)
     */
    public function index()
    {
        // Seguridad extra: Si no es admin, lo mandamos al dashboard
        if (Auth::user()->rol !== 'Administrador') {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos para ver esta sección.');
        }

        $usuarios = User::all();
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Formulario para nuevo empleado
     */
    public function create()
    {
        if (Auth::user()->rol !== 'Administrador') {
            return redirect()->route('dashboard');
        }
        return view('usuarios.create');
    }

    /**
     * Guardar nuevo empleado (Lógica optimizada)
     */
    public function store(Request $request)
    {
        // 1. Validamos que los datos vengan bien
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'rol' => ['required', 'string'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        // 2. Creamos al usuario
        \App\Models\User::create([
            'name' => $request->name,
            'apellido' => $request->apellido,
            'rol' => $request->rol,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'telefono' => $request->telefono ?? 'N/A',
            'direccion' => $request->direccion ?? 'N/A',
        ]);

        // 3. Redirigimos al índice de personal con mensaje de éxito
        return redirect()->route('usuarios.index')->with('success', '¡Empleado dado de alta correctamente!');
    }

    /**
     * Eliminar empleado (Protección para no auto-eliminarse)
     */
    public function destroy($id) {
    $usuario = User::findOrFail($id);

    // Usamos Auth::id() que es más directo y seguro
    if ($usuario->id === \Illuminate\Support\Facades\Auth::id()) {
        return back()->withErrors('No puedes eliminarte a ti mismo.');
    }

    $usuario->delete();
    return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado.');
}
}