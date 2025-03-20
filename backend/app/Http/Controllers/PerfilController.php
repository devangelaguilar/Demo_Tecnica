<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PerfilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $perfil = Perfil::all();
        return response()->json([
            "result" => $perfil
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
        $perfil = new Perfil();
        $perfil->codigo = $request->codigo;
        $perfil->nombre = $request->nombre;
        $perfil->secciones = $request->secciones;
        $perfil->save();
        return response()->json([
            "result" => $perfil,
            Response::HTTP_CREATED
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Perfil $perfil)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Perfil $perfil)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
        $perfil = Perfil::findOrFail($request->id);
        $perfil->codigo = $request->codigo;
        $perfil->nombre = $request->nombre;
        $perfil->secciones = $request->secciones;
        $perfil->save();

        return response()->json([
            "result" => $perfil,
            Response::HTTP_CREATED
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perfil $perfil)
    {
        //
        perfil::destroy($perfil->id);
        return response()->json([
            "result" => "Producto eliminado",
            Response::HTTP_OK
        ]);
    }
}
