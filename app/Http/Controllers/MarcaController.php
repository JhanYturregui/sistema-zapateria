<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Marca;

class MarcaController extends Controller
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
        $marcas = Marca::where('estado', true)->orderBy('id', 'desc')->paginate(10);

        return view('base.marcas', ['marcas' => $marcas, 
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
        $existeMarca = Marca::where('nombre', $nombre)->exists();
        $response = array();

        if($existeMarca){
            $marcaActivo = Marca::where([['nombre', $nombre], ['estado', true]])->exists();
            if($marcaActivo){
                $response["estado"] = false;
                $response["mensaje"] = "La marca ya se encuentra registrada";

            }else{
                $marca = Marca::where('nombre', $nombre)->first();
                $marca->estado = true;
                $marca->save();
                $response["estado"] = true;
                $response["mensaje"] = "";
            }

        }else{
            $marca = new Marca();
            $marca->nombre = $nombre;
            $marca->estado = true;
            $marca->save();
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
        $marca = Marca::where([['id', $id], ['estado', true]])->first();

        return json_encode($marca);
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

        $existeMarca = Marca::where('nombre', $nombre)->exists();
        if($existeMarca){
            $marcaActivo = Marca::where([['nombre', $nombre], ['estado', true]])->exists();
            if($marcaActivo){
                $response['estado'] = false;
                $response['mensaje'] = "La marca ya se encuentra registrada";

            }else{
                $marca = Marca::where('nombre', $nombre)->first();
                $marca->estado = true;
                $marca->save();
                $response['estado'] = true;
                $response['mensaje'] = "";
            }

        }else{
            $marca = Marca::where([['id', $id], ['estado', true]])->first();
            $marca->nombre = $nombre;
            $marca->save();
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

        $marca = Marca::where([['id', $id], ['estado', true]])->first();
        $marca->estado = false;
        $marca->save();
    }
}
