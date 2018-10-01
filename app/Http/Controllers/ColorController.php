<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Color;

class ColorController extends Controller
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
        $colores = Color::where('estado', true)->orderBy('id', 'desc')->paginate(10);

        return view('base.colores', ['colores' => $colores, 
                                      'datos' => $datos]);
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
        $existeColor = Color::where('nombre', $nombre)->exists();
        $response = array();

        if($existeColor){
            $colorActivo = Color::where([['nombre', $nombre], ['estado', true]])->exists();
            if($colorActivo){
                $response["estado"] = false;
                $response["mensaje"] = "El color ya se encuentra activo";

            }else{
                $color = Color::where('nombre', $nombre)->first();
                $color->estado = true;
                $color->save();
                $response["estado"] = true;
                $response["mensaje"] = "";
            }

        }else{
            $color = new Color();
            $color->nombre = $nombre;
            $color->estado = true;
            $color->save();
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
        $color = Color::where([['id', $id], ['estado', true]])->first();
        return json_encode($color);
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
        $response = array();

        $existeColor = Color::where('nombre', $nombre)->exists();
        if($existeColor){
            $colorActivo = Color::where([['nombre', $nombre], ['estado', true]])->exists();
            if($colorActivo){
                $response['estado'] = false;
                $response['mensaje'] = "El color ya se encuentra activo";

            }else{
                $color = Color::where('nombre', $nombre)->first();
                $color->estado = true;
                $color->save();
                $response['estado'] = true;
                $response['mensaje'] = "";
            }

        }else{
            $color = Color::where([['id', $id], ['estado', true]])->first();
            $color->nombre = $nombre;
            $color->save();
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

        $color = Color::where([['id', $id], ['estado', true]])->first();
        $color->estado = false;
        $color->save();
    }
}
