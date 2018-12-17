<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Talla;
use App\Inventario;

class TallaController extends Controller
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
        $tallas = Talla::where('estado', true)->orderBy('id', 'desc')->paginate(10);

        return view('base.tallas', ['tallas' => $tallas, 
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
        $nombre = mb_strtoupper($request->get('nombre'));
        $existeTalla = Talla::where('nombre', $nombre)->exists();
        $response = array();

        if($existeTalla){
            $tallaActivo = Talla::where([['nombre', $nombre], ['estado', true]])->exists();
            if($tallaActivo){
                $response["estado"] = false;
                $response["mensaje"] = "La talla ya se encuentra registrada";

            }else{
                $talla = Talla::where('nombre', $nombre)->first();
                $talla->estado = true;
                $talla->save();
                $response["estado"] = true;
                $response["mensaje"] = "";
            }

        }else{
            $talla = new Talla();
            $talla->nombre = $nombre;
            $talla->estado = true;
            $talla->save();
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
        $talla = Talla::where([['id', $id], ['estado', true]])->first();
        return json_encode($talla);
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

        $existeTalla = Talla::where('nombre', $nombre)->exists();
        if($existeTalla){
            $tallaActivo = Talla::where([['nombre', $nombre], ['estado', true]])->exists();
            if($tallaActivo){
                $response['estado'] = false;
                $response['mensaje'] = "La talla ya se encuentra registrada";

            }else{
                $talla = Talla::where('nombre', $nombre)->first();
                $talla->estado = true;
                $talla->save();
                $response['estado'] = true;
                $response['mensaje'] = "";
            }

        }else{
            $talla = Talla::where([['id', $id], ['estado', true]])->first();
            $talla->nombre = $nombre;
            $talla->save();
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

        $talla = Talla::where([['id', $id], ['estado', true]])->first();
        $talla->estado = false;
        $talla->save();
    }


    public function listar(Request $request){
        $tallas = Talla::where('estado', true)->get();
        return json_encode($tallas);
    }

    public function listarTallasCodigo(Request $request){
        $codigo = $request->get('codigo');
        $inv = Inventario::where('codigo_producto', $codigo)->first();
        $tallas = json_decode($inv->tallas);
        $cantTallas = json_decode($inv->cantidad_talla);
        $arrTallas = array();

        for($i=0; $i<count($tallas); $i++){
            $tall = $tallas[$i];
            $cant = $cantTallas[$i];
            if($cant > 0){
                array_push($arrTallas, $tall);
            }
        }
        sort($arrTallas);
        
        return json_encode($arrTallas);
    }
}
