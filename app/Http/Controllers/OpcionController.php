<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Opcion;
use App\Categoria;

class OpcionController extends Controller
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

        $categorias = Categoria::where('estado', true)->get();
        /*$opciones = Opcion::where('estado', true)
                            ->orderBy('id', 'desc')
                            ->paginate(10);*/
        $opciones = DB::table('opciones')
                    ->join('categorias', 'categorias.id', '=', 'opciones.categoria')
                    ->select('opciones.*', 'categorias.nombre as categoria')
                    ->orderBy('id', 'desc')
                    ->paginate(10);
        return view('seguridad.opciones', ['opciones'  => $opciones, 
                                           'categorias'=> $categorias,
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
        $nombre = strtoupper($request->get('nombre'));
        $categoria = $request->get('categoria');
        $orden = $request->get('orden');
        //$icono = $request->get('icono');

        $existeOpcion = Opcion::where('nombre', $nombre)->exists();
        $response = array();

        if($existeOpcion){
            $opcionActivo = Opcion::where([['nombre', $nombre], ['estado', true]])->exists();
            if($opcionActivo){
                $response["estado"] = false;
                $response["mensaje"] = "La opción ya se encuentra registrada";

            }else{
                $opcion = Opcion::where('nombre', $nombre)->first();
                $opcion->estado = true;
                $opcion->save();

                $response["estado"] = true;
                $response["mensaje"] = "";
            }

        }else{
            $opcion = new Opcion();
            $opcion->nombre = $nombre;
            $opcion->categoria = $categoria;
            $opcion->orden = $orden;
            //$opcion->icono = $icono;
            $opcion->estado = true;
            $opcion->save();

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
        $opcion = Opcion::where([['id', $id], ['estado', true]])->first();
        print_r(json_encode($opcion));
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
        $categoria = $request->get('categoria');
        $orden = $request->get('orden');
        //$icono = $request->get('icono');

        $response = array();

        $existeOpcion = Opcion::where([['nombre', $nombre], ['id', '!=', $id]])->exists();
        
        if($existeOpcion){
            $response["estado"] = false;
            $response["mensaje"] = "Esta opción ya se encuentra registrada";

        }else{
            $opcion = Opcion::where([['id', $id], ['estado', true]])->first();
            $opcion->nombre = $nombre;
            $opcion->categoria = $categoria;
            $opcion->orden = $orden;
            //$opcion->icono = $icono;
            $opcion->save();

            $reponse["estado"] = true;
            $reponse["mensaje"] = "";
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

        $opcion = Opcion::where([['id', $id], ['estado', true]])->first();
        $opcion->estado = false;
        $opcion->save();
    }
}
