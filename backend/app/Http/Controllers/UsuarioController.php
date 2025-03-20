<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $usuario = Usuario::all();
        return response()->json([
            "result" => $usuario
        ], Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $usuario = new Usuario();
        $usuario->usuario = $request->usuario;
        $usuario->nombre = $request->nombre;
        $usuario->telefono = $request->telefono;
        $usuario->perfil = $request->perfil;
        $usuario->password = $request->password;
        $usuario->foto = $request->foto;
        $usuario->save();
        return response()->json([
            "result" => $usuario,
            Response::HTTP_CREATED
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuario $usuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usuario $usuario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuario $usuario)
    {
        //
        $usuario = Usuario::findOrFail($request->id);
        $usuario->usuario = $request->usuario;
        $usuario->nombre = $request->nombre;
        $usuario->telefono = $request->telefono;
        $usuario->perfil = $request->perfil;
        $usuario->password = $request->password;
        $usuario->foto = $request->foto;
        $usuario->save();
        return response()->json([
            "result" => $usuario,
            Response::HTTP_CREATED
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        //
        Usuario::destroy($usuario->id);
        return response()->json([
            "result" => "Usuario eliminado",
            Response::HTTP_OK
        ]);
    }
}
