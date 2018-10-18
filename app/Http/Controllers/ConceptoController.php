<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Concepto;

class ConceptoController extends Controller
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

        $conceptos = Concepto::where('estado', true)->orderBy('id', 'desc')->paginate(10);

        return view('base.conceptos', ['conceptos'=> $conceptos, 
                                        'datos'   => $datos]); 
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
        $tipo = $request->get('tipo');

        $existeConcepto = Concepto::where('nombre', $nombre)->exists();
        $response = array();

        if($existeConcepto){
            $conceptoActivo = Concepto::where([['nombre', $nombre], ['estado', true]])->exists();
            if($conceptoActivo){
                $response["estado"] = false;
                $response["mensaje"] = "El concepto ya se encuentra registrado";

            }else{
                $concepto = Concepto::where('nombre', $nombre)->first();
                $concepto->estado = true;
                $concepto->save();

                $response["estado"] = true;
                $response["mensaje"] = "";
            }

        }else{
            $concepto = new Concepto();
            $concepto->nombre = $nombre;
            $concepto->tipo = $tipo;
            $concepto->estado = true;
            $concepto->save();

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
        $concepto = Concepto::where('id', $id)->first();

        return json_encode($concepto);
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
        $tipo = $request->get('tipo');

        $response = array();

        $existeConcepto = Concepto::where([['nombre', $nombre], ['id', '!=', $id]])->exists();
        
        if($existeConcepto){
            $response["estado"] = false;
            $response["mensaje"] = "El concepto ya se encuentra registrado";

        }else{
            $concepto = Concepto::where('id', $id)->first();
            $concepto->nombre = $nombre;
            $concepto->tipo = $tipo;
            $concepto->save();

            $response["estado"] = true;
            $response["mensaje"] = "";
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

        $concepto = Concepto::where('id', $id)->first();
        $concepto->estado = false;
        $concepto->save();
    }
}
