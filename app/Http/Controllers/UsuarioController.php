<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Persona;
use App\TipoUsuario;
use App\Sucursal;

class UsuarioController extends Controller
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
        date_default_timezone_set('America/Lima');
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
        $tipoUsu = Auth::user()->tipo;
        $tiposUsuario = TipoUsuario::where([['id', '>', $tipoUsu], ['estado', true]])->orderBy('id', 'asc')->get();
        $sucursales = Sucursal::where([['id', '>', '1'], ['estado', true]])->orderBy('id', 'asc')->get();
        $usuarios = DB::table('usuarios')
                    ->join('tipos_usuario', 'usuarios.tipo', '=', 'tipos_usuario.id')
                    ->select('usuarios.*', 'tipos_usuario.nombre as tipo_usuario')
                    ->where('usuarios.estado', true)
                    ->where('usuarios.tipo', '>', $tipoUsu)
                    ->orderBy('id', 'desc')
                    ->paginate(10);

        return view('seguridad.usuarios', ['usuarios' => $usuarios, 
                                           'tiposUsuario' => $tiposUsuario,
                                           'datos' => $datos,
                                           'sucursales' => $sucursales]);
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
        $numeroDocumento = $request->get('numeroDocumento');
        $usuario = $request->get('usuario');
        $tipoUsuario = (int)$request->get('tipoUsuario');
        $sucursal = $request->get('sucursal');

        $response = array();
        $esUsuario = false;

        $roles = Persona::where('numero_documento', $numeroDocumento)->value('roles');
        $roles = json_decode($roles);
        
        if($roles === null){
            $response["estado"] = false;
            $response["mensaje"] = "El documento no tiene rol usuario asignado";

        }else{
            foreach ($roles as $key => $value) {
                if($value == 1){
                    $esUsuario = true;
                    break;            
                }
            }

            if($esUsuario){
                $existeUsuario = User::where('username', $usuario)->exists();
                if($existeUsuario){
                    $usuarioDoc = User::where([['num_documento', $numeroDocumento], ['username', $usuario], ['estado', true]])->exists();
                    if($usuarioDoc){
                        $response["estado"] = false;
                        $response["mensaje"] = "Ya existe un usuario relacionado a ese documento";

                    }else{
                        $usuarioActivo = User::where([['username', $usuario], ['estado', true]])->exists();
                        if($usuarioActivo){
                            $response["estado"] = false;
                            $response["mensaje"] = "El usuario ya no se encuentra disponible";

                        }else{
                            $usuario = User::where('username', $usuario)->first();
                            $usuario->estado = true;
                            $usuario->save();
                            $response["estado"] = true;
                            $response["mensaje"] = "";
                        }
                    }

                }else{
                    $nuevoUsuario = new User();
                    $nuevoUsuario->num_documento = $numeroDocumento;
                    $nuevoUsuario->username = $usuario;
                    $nuevoUsuario->password = bcrypt("123456");
                    $nuevoUsuario->tipo = $tipoUsuario;
                    $nuevoUsuario->sucursal = $sucursal;
                    $nuevoUsuario->estado = true;
                    $nuevoUsuario->save();
                    $response["estado"] = true;
                    $response["mensaje"] = "";
                }
                
                
            }else{
                $response["estado"] = false;
                $response["mensaje"] = "El número de documento ingresado no tiene rol usuario";
            }
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
        $usuario = User::where([['id', $id], ['estado', true]])->first();

        return json_encode($usuario);
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
        $numeroDocumento = $request->get('numeroDocumento');
        $usuario = $request->get('usuario');
        $tipoUsuario = (int)$request->get('tipoUsuario');
        $sucursal = $request->get('sucursal');

        $response = array();

        $existeUsuario = User::where('username', $usuario)->exists();
        if($existeUsuario){
            $usuarioActivo = User::where([['username', $usuario], ['estado', true]])->exists();
            if($usuarioActivo){
                $response["estado"] = false;
                $response["mensaje"] = "El usuario ya no se encuentra disponible";

            }else{
                $usuario = User::where('username', $usuario)->first();
                $usuario->estado = true;
                $usuario->save();
                $response["estado"] = true;
                $response["mensaje"] = "";
            }

        }else{
            $actUsuario = User::where('num_documento', $numeroDocumento)->first();
            $actUsuario->username = $usuario;
            $actUsuario->tipo = $tipoUsuario;
            $actUsuario->sucursal = $sucursal;
            $actUsuario->estado = true;
            $actUsuario->save();
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

        $usuario = User::where([['id', $id], ['estado', true]])->first();
        $usuario->estado = false;
        $usuario->save();
    }
}
