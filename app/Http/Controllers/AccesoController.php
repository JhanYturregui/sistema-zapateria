<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Acceso;
use App\Categoria;
use App\Opcion;

class AccesoController extends Controller
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
     * Configurar accesos para cada tipo de usuario.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function configurarAccesos(Request $request){
        $idTipoUsuario = $request->get('idTipoUsuario');
        $seleccionados = $request->get('seleccionados');
        $noSeleccionados = $request->get('noSeleccionados');

        foreach ($seleccionados as $idSeleccionado) {
            $acceso = Acceso::where([['tipo_usuario', $idTipoUsuario], 
                                     ['opcion', $idSeleccionado]])->exists();
            
            if($acceso){
                $actAcceso = Acceso::where([['tipo_usuario', $idTipoUsuario], 
                                            ['opcion', $idSeleccionado]])->first();
                $actAcceso->estado = true;
                $actAcceso->save();

            }else{
                $nuevoAcceso = new Acceso();
                $nuevoAcceso->tipo_usuario = $idTipoUsuario;
                $nuevoAcceso->opcion = $idSeleccionado;
                $nuevoAcceso->estado = true;
                $nuevoAcceso->save();
            }                         
        }

        foreach ($noSeleccionados as $idNoSeleccionado) {
            $acceso = Acceso::where([['tipo_usuario', $idTipoUsuario], 
                                     ['opcion', $idNoSeleccionado],   
                                     ['estado', true]])->exists();
            
            if($acceso){
                $actAcceso = Acceso::where([['tipo_usuario', $idTipoUsuario], 
                                            ['opcion', $idNoSeleccionado],   
                                            ['estado', true]])->first();
                $actAcceso->estado = false;
                $actAcceso->save();
            }                        
        }

    }

    /**
     * Obtener accesos para cada tipo de usuario
     * @return Array
     */
    public function obtenerAccesos(Request $request){
        $datos = array(); 
        $idTipoUsuario = $request->get('idTipoUsuario');
        $accesos = Acceso::where([['tipo_usuario', $idTipoUsuario],['estado', true]])->get();
        
        return json_encode($accesos);
    }

    /**
     * Obtener accesos para cada tipo de usuario
     * @return Array
     */
    public function obtenerMenus(){
        $datos = array();
        $opciones = array(); 
        $usuario = Auth::user();
        $tipoUsuario = $usuario->tipo;
        $accesos = Acceso::where([['tipo_usuario', $tipoUsuario], ['estado', true]])->get();

        foreach ($accesos as $acceso) {
            $idOpcion = $acceso->opcion;
            $idCategoria = Opcion::where('id', $idOpcion)->value('categoria');
            $nombreOpcion = Opcion::where('id', $idOpcion)->value('nombre');
            $nombreCategoria = Categoria::where('id', $idCategoria)->value('nombre');

            $ruta = "";
            switch ($idOpcion) {
                case 1:
                    $ruta = "categorias";
                    break;
                case 2:
                    $ruta = "opciones";
                    break;
                case 3:
                    $ruta = "tipos_usuario";
                    break;
                case 4:
                    $ruta = "roles";
                    break;
                case 5:
                    $ruta = "ventas";
                    break;
                case 6:
                    $ruta = "personas";
                    break;    
                case 7:
                    $ruta = "usuarios";
                    break;    
                case 8:
                    $ruta = "colores";
                    break;    
                case 9:
                    $ruta = "tallas";
                    break;    
                case 10:
                    $ruta = "marcas";
                    break;    
                case 11:
                    $ruta = "modelos";
                    break;    
                case 12:
                    $ruta = "lineas";
                    break;    
                case 13:
                    $ruta = "proveedores";
                    break;    
                case 14:
                    $ruta = "productos";
                    break;    
                
                default:
                
                    break;
            }
            
            $opciones[$ruta] = $nombreOpcion;

            if(array_key_exists($nombreCategoria, $datos)){
                array_push($datos[$nombreCategoria], $opciones);

            }else{
                $datos[$nombreCategoria] = array();
                array_push($datos[$nombreCategoria], $opciones);                   
            }   

            $opciones = array();
        }
        
        return $datos;
    }


}
