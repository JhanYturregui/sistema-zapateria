<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Categoria;

class CategoriaController extends Controller
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

        $categorias = Categoria::where('estado', true)
                                ->orderBy('id', 'desc')
                                ->paginate(10);
        return view('seguridad.categorias', ['categorias' => $categorias, 'datos'=>$datos]);
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
        $orden = $request->get('orden');

        $existeCategoria = Categoria::where('nombre', $nombre)->exists();
        $response = array();

        if($existeCategoria){
            $categoriaActivo = Categoria::where([['nombre', $nombre], ['estado', true]])->exists();
            if($categoriaActivo){
                $response["estado"] = false;
                $response["mensaje"] = "La categoría ya se encuentra registrada";

            }else{
                $categoria = Categoria::where('nombre', $nombre)->first();
                $categoria->estado = true;
                $categoria->save();
                
                $response["estado"] = true;
                $response["mensaje"] = "";
            }

        }else{
            $categoria = new Categoria();
            $categoria->nombre = $nombre;
            $categoria->orden = $orden;
            $categoria->estado = true;
            $categoria->save();
            
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
        $categoria = Categoria::where([['id', $id], ['estado', true]])->first();
        print_r(json_encode($categoria));
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
        $orden = $request->get('orden');
        $icono = $request->get('icono');

        $response = array();

        $existeCategoria = Categoria::where([['nombre', $nombre], ['id', '!=', $id]])->exists();
        
        if($existeCategoria){
            $response["estado"] = false;
            $response["mensaje"] = "Esta categoría ya se encuentra registrada";

        }else{
            $categoria = Categoria::where([['id', $id], ['estado', true]])->first();
            $categoria->nombre = $nombre;
            $categoria->orden = $orden;
            $categoria->icono = $icono;
            $categoria->save();

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

        $categoria = Categoria::where([['id', $id], ['estado', true]])->first();
        $categoria->estado = false;
        $categoria->save();
    }

}
