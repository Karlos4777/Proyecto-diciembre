<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\UserRequest;

class PerfilController extends Controller
{
    public function edit(){
        $registro=Auth::user();
        return view('autenticacion.perfil', compact('registro'));
    }
    public function update(UserRequest $request){
        $registro=Auth::user();
        $registro->name = $request->name;
        $registro->email = $request->email;
        // Guardar teléfono y dirección si vienen en la petición
        if ($request->filled('telefono')) {
            $registro->telefono = $request->telefono;
        } else {
            $registro->telefono = $request->telefono ?? $registro->telefono;
        }

        if ($request->filled('direccion')) {
            $registro->direccion = $request->direccion;
        } else {
            $registro->direccion = $request->direccion ?? $registro->direccion;
        }
        if ($request->filled('password')) {
            $registro->password = Hash::make($request->password);
        }
        $registro->save();

        return redirect()->route('perfil.edit')->with('mensaje', 'Datos actualizados correctamente.');
    }
}
