<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proveedor;

class ProveedorController extends Controller
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

        $proveedores = Proveedor::where('estado', true)
                            ->orderBy('id', 'desc')
                            ->paginate(10);
        return view('seguridad.proveedores', ['proveedores'=> $proveedores, 
                                              'datos'      => $datos]);                            
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
        $ruc = $request->get('ruc');
        $nombre = $request->get('nombre');
        $direccion = $request->get('direccion');
        $correo = $request->get('correo');
        $telefono1 = $request->get('telefono1');
        $telefono2 = $request->get('telefono2');

        $existeProveedor = Proveedor::where('ruc', $ruc)->exists();
        $response = array();

        if($existeProveedor){
            $proveedorActivo = Proveedor::where([['ruc', $ruc], ['estado', true]])->exists();
            if($proveedorActivo){
                $response["estado"] = false;
                $response["mensaje"] = "El RUC ya se encuentra registrado";

            }else{
                $proveedor = Proveedor::where('ruc', $ruc)->first();
                $proveedor->estado = true;
                $proveedor->save();
                $response["estado"] = true;
                $response["mensaje"] = "";
            }

        }else{
            $proveedor = new Proveedor();
            $proveedor->ruc = $ruc;
            $proveedor->nombre = $nombre;
            $proveedor->direccion = $direccion;
            $proveedor->correo = $correo;
            $proveedor->telefono1 = $telefono1;
            $proveedor->telefono2 = $telefono2;
            $proveedor->estado = true;
            $proveedor->save();
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
        $proveedor = Proveedor::where([['id', $id], ['estado', true]])->first();

        return json_encode($proveedor);
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
        $ruc = $request->get('ruc');
        $nombre = $request->get('nombre');
        $direccion = $request->get('direccion');
        $correo = $request->get('correo');
        $telefono1 = $request->get('telefono1');
        $telefono2 = $request->get('telefono2');
        
        $response = array();

        $proveedor = Proveedor::where([['id', $id], ['estado', true]])->first();
        $proveedor->ruc = $ruc;
        $proveedor->nombre = $nombre;
        $proveedor->direccion = $direccion;
        $proveedor->correo = $correo;
        $proveedor->telefono1 = $telefono1;
        $proveedor->telefono2 = $telefono2;
        $proveedor->save();
        $response['estado'] = true;
        $response['mensaje'] = "";

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
        $proveedor = Proveedor::where('id', $id)->first();
        $proveedor->estado = false;
        $proveedor->save();
    }
}
