<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sucursal;

class SucursalController extends Controller
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

        $sucursales = Sucursal::where('estado', true)->orderBy('id', 'desc')->paginate(10);

        return view('seguridad.sucursales', ['sucursales'=> $sucursales, 
                                           'datos'     => $datos]);  
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
        $direccion = $request->get('direccion');

        $existeSucursal = Sucursal::where('nombre', $nombre)->exists();
        $response = array();

        if($existeSucursal){
            $sucursalActivo = Sucursal::where([['nombre', $nombre], ['estado', true]])->exists();
            if($sucursalActivo){
                $response["estado"] = false;
                $response["mensaje"] = "La sucursal ya se encuentra registrada";

            }else{
                $sucursal = Sucursal::where('nombre', $nombre)->first();
                $sucursal->direccion = $direccion;
                $sucursal->estado = true;
                $sucursal->save();

                $response["estado"] = true;
                $response["mensaje"] = "";
            }

        }else{
            $sucursal = new Sucursal();
            $sucursal->nombre = $nombre;
            $sucursal->direccion = $direccion;
            $sucursal->estado = true;
            $sucursal->save();

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
        $sucursal = Sucursal::where('id', $id)->first();

        return json_encode($sucursal);
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
        $direccion = $request->get('direccion');

        $response = array();

        $existeSucursal = Sucursal::where([['nombre', $nombre], ['id', '!=', $id]])->exists();
        
        if($existeSucursal){
            $response["estado"] = false;
            $response["mensaje"] = "La sucursal ya se encuentra registrada";

        }else{
            $sucursal = Sucursal::where('id', $id)->first();
            $sucursal->nombre = $nombre;
            $sucursal->direccion = $direccion;
            $sucursal->save();

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

        $sucursal = Sucursal::where('id', $id)->first();
        $sucursal->estado = false;
        $sucursal->save();
    }
}
