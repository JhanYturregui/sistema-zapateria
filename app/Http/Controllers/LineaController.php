<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Linea;

class LineaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('paginas');
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accesoController = new AccesoController();
        $datos = $accesoController->obtenerMenus();
        $lineas = Linea::where('estado', true)->orderBy('id', 'desc')->paginate(10);

        return view('base.lineas', ['lineas' => $lineas, 
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
        $nombre = mb_strtoupper($request->get('nombre'));
        $existeLinea = Linea::where('nombre', $nombre)->exists();
        $response = array();

        if($existeLinea){
            $lineaActivo = Linea::where([['nombre', $nombre], ['estado', true]])->exists();
            if($lineaActivo){
                $response["estado"] = false;
                $response["mensaje"] = "La línea ya se encuentra registrada";

            }else{
                $linea = Linea::where('nombre', $nombre)->first();
                $linea->estado = true;
                $linea->save();
                $response["estado"] = true;
                $response["mensaje"] = "";
            }

        }else{
            $linea = new Linea();
            $linea->nombre = $nombre;
            $linea->estado = true;
            $linea->save();
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
        $linea = Linea::where([['id', $id], ['estado', true]])->first();

        return json_encode($linea);
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
        $nombre = mb_strtoupper($request->get('nombre'));
        $response = array();

        $existeLinea = Linea::where('nombre', $nombre)->exists();
        if($existeLinea){
            $lineaActivo = Linea::where([['nombre', $nombre], ['estado', true]])->exists();
            if($lineaActivo){
                $response['estado'] = false;
                $response['mensaje'] = "La línea ya se encuentra registrada";

            }else{
                $linea = Linea::where('nombre', $nombre)->first();
                $linea->estado = true;
                $linea->save();
                $response['estado'] = true;
                $response['mensaje'] = "";
            }

        }else{
            $linea = Linea::where([['id', $id], ['estado', true]])->first();
            $linea->nombre = $nombre;
            $linea->save();
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

        $linea = Linea::where([['id', $id], ['estado', true]])->first();
        $linea->estado = false;
        $linea->save();
    }
}
