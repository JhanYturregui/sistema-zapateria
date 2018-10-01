<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoUsuario;
use App\Categoria;
use App\Opcion;
use App\Acceso;

class TipoUsuarioController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
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

        $categorias = Categoria::where('estado', true)->get();
        $opciones = Opcion::where('estado', true)->get();
        $tiposUsuario = TipoUsuario::where('estado', true)
                                    ->orderBy('id', 'desc')
                                    ->paginate(10);
        return view('base.tipos_usuario', ['tiposUsuario' => $tiposUsuario, 
                                           'categorias'   => $categorias,
                                           'opciones'     => $opciones,
                                           'datos'        => $datos]);
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
        $existeTipo = TipoUsuario::where('nombre', $nombre)->exists();
        $response = array();

        if($existeTipo){
            $tipoActivo = TipoUsuario::where([['nombre', $nombre], ['estado', true]])->exists();
            if($tipoActivo){
                $response["estado"] = false;
                $response["mensaje"] = "El tipo de usuario ya se encuentra activo";

            }else{
                $tipoUsuario = TipoUsuario::where('nombre', $nombre)->first();
                $tipoUsuario->estado = true;
                $tipoUsuario->save();
                $response["estado"] = true;
                $response["mensaje"] = "";
            }

        }else{
            $tipoUsuario = new TipoUsuario();
            $tipoUsuario->nombre = $nombre;
            $tipoUsuario->estado = true;
            $tipoUsuario->save();
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
        $tipo_usuario = TipoUsuario::where([['id', $id], ['estado', true]])->first();
        print_r(json_encode($tipo_usuario));
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
    
        $tipoUsuario = TipoUsuario::where([['id', $id], ['estado', true]])->first();
        $tipoUsuario->nombre = $nombre;
        $tipoUsuario->save();
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

        $tipoUsuario = TipoUsuario::where([['id', $id], ['estado', true]])->first();
        $tipoUsuario->estado = false;
        $tipoUsuario->save();
    }

}
