<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;
use App\Rol;

class PersonaController extends Controller
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

        $personas = Persona::where('estado', true)
                      ->orderBy('id', 'desc')
                      ->paginate(10);
        
        $roles = Rol::where('estado', true)->orderBy('id', 'asc')->get();
                      
        return view('base.personas', ['personas'=>$personas, 'datos'=>$datos, 'roles'=>$roles]);
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
        $tipoDocumento = $request->get('tipoDocumento');
        $numeroDocumento = $request->get('numeroDocumento');
        $nombres = $request->get('nombres');
        $apellidos = $request->get('apellidos');
        $razonSocial = $request->get('razonSocial');
        $correo = $request->get('correo');
        $telefono = $request->get('telefono');
        $direccion = $request->get('direccion');
        $roles = json_encode($request->get('roles'));
        
        $existePersona = Persona::where('numero_documento', $numeroDocumento)->exists();
        $response = array();

        if($existePersona){
            $personaActivo = Persona::where([['numero_documento', $numeroDocumento], ['estado', true]])->exists();
            if($personaActivo){
                $response["estado"] = false;
                $response["mensaje"] = "La persona ya se encuentra registrada";

            }else{
                $persona = Persona::where('numero_documento', $numeroDocumento)->first();
                $persona->estado = true;
                $persona->save();
                $response["estado"] = true;
                $response["mensaje"] = "";
            }

        }else{
            $persona = new Persona();
            $persona->tipo_documento = $tipoDocumento;
            $persona->numero_documento = $numeroDocumento;
            $persona->nombres = $nombres;
            $persona->apellidos = $apellidos;
            $persona->razon_social = $razonSocial;
            $persona->correo = $correo;
            $persona->telefono = $telefono;
            $persona->direccion = $direccion;
            $persona->roles = $roles;
            $persona->estado = true;
            $persona->save();
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
        $persona = Persona::where([['id', $id], ['estado', true]])->first();
        return json_encode($persona);
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
        $tipoDocumento = $request->get('tipoDocumento');
        $numeroDocumento = $request->get('numeroDocumento');
        $nombres = $request->get('nombres');
        $apellidos = $request->get('apellidos');
        $razonSocial = $request->get('razonSocial');
        $correo = $request->get('correo');
        $telefono = $request->get('telefono');
        $direccion = $request->get('direccion');
        $roles = json_encode($request->get('roles'));
        
        $persona = Persona::where('numero_documento', $numeroDocumento)->first();
        $persona->tipo_documento = $tipoDocumento;
        $persona->numero_documento = $numeroDocumento;
        $persona->nombres = $nombres;
        $persona->apellidos = $apellidos;
        $persona->razon_social = $razonSocial;
        $persona->correo = $correo;
        $persona->telefono = $telefono;
        $persona->direccion = $direccion;
        $persona->roles = $roles;
        $persona->estado = true;
        $persona->save();
        $response["estado"] = true;
        $response["mensaje"] = "";

        return json_encode($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->get('id');

        $persona = Persona::where([['id', $id], ['estado', true]])->first();
        $persona->estado = false;
        $persona->save();
    }
}
