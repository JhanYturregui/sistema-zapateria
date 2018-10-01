<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rol;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accesoController = new AccesoController();
        $datos = $accesoController->obtenerMenus();

        $roles = Rol::where('estado', true)
                      ->orderBy('id', 'desc')
                      ->paginate(10);  
        return view('base.roles', ['roles'=>$roles, 'datos'=>$datos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nombre = $request->get('nombre');
        $existeRol = Rol::where('nombre', $nombre)->exists();
        $response = array();

        if($existeRol){
            $rolActivo = Rol::where([['nombre', $nombre], ['estado', true]])->exists();
            if($rolActivo){
                $response["estado"] = false;
                $response["mensaje"] = "El rol ya se encuentra activo";

            }else{
                $rol = Rol::where('nombre', $nombre)->first();
                $rol->estado = true;
                $rol->save();
                $response["estado"] = true;
                $response["mensaje"] = "";
            }

        }else{
            $rol = new Rol();
            $rol->nombre = $nombre;
            $rol->estado = true;
            $rol->save();
            $response["estado"] = true;
            $response["mensaje"] = "";
        }   
        print_r(json_encode($response));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $id = $request->get('id');
        $rol = Rol::where([['id', $id], ['estado', true]])->first();
        print_r(json_encode($rol));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->get('id');
        $nombre = $request->get('nombre');
    
        $rol = Rol::where([['id', $id], ['estado', true]])->first();
        $rol->nombre = $nombre;
        $rol->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->get('id');

        $rol = Rol::where([['id', $id], ['estado', true]])->first();
        $rol->estado = false;
        $rol->save();
    }

}
