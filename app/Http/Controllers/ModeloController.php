<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modelo;

class ModeloController extends Controller
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
        $modelos = Modelo::where('estado', true)->orderBy('id', 'desc')->paginate(10);

        return view('base.modelos', ['modelos' => $modelos, 
                                    'datos'  => $datos]);
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
        $nombre = strtoupper($request->get('nombre'));
        $existeModelo = Modelo::where('nombre', $nombre)->exists();
        $response = array();

        if($existeModelo){
            $modeloActivo = Modelo::where([['nombre', $nombre], ['estado', true]])->exists();
            if($modeloActivo){
                $response["estado"] = false;
                $response["mensaje"] = "El modelo ya se encuentra registrado";

            }else{
                $modelo = Modelo::where('nombre', $nombre)->first();
                $modelo->estado = true;
                $modelo->save();
                $response["estado"] = true;
                $response["mensaje"] = "";
            }

        }else{
            $modelo = new Modelo();
            $modelo->nombre = $nombre;
            $modelo->estado = true;
            $modelo->save();
            $response["estado"] = true;
            $response["mensaje"] = "";
        }   

        return json_encode($response);
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
        $modelo = Modelo::where([['id', $id], ['estado', true]])->first();

        return json_encode($modelo);
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
        $nombre = strtoupper($request->get('nombre'));
        $response = array();

        $existeModelo = Modelo::where('nombre', $nombre)->exists();
        if($existeModelo){
            $modeloActivo = Modelo::where([['nombre', $nombre], ['estado', true]])->exists();
            if($modeloActivo){
                $response['estado'] = false;
                $response['mensaje'] = "El modelo ya se encuentra registrado";

            }else{
                $modelo = Modelo::where('nombre', $nombre)->first();
                $modelo->estado = true;
                $modelo->save();
                $response['estado'] = true;
                $response['mensaje'] = "";
            }

        }else{
            $modelo = Modelo::where([['id', $id], ['estado', true]])->first();
            $modelo->nombre = $nombre;
            $modelo->save();
            $response['estado'] = true;
            $response['mensaje'] = "";
        }

        return json_encode($response);
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

        $modelo = Modelo::where([['id', $id], ['estado', true]])->first();
        $modelo->estado = false;
        $modelo->save();
    }
}
