<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Caja;
use App\DocumentoVenta;
use App\MovimientoCaja;

class CajaController extends Controller
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

        $cajas = Caja::where('estado', false)->orderBy('id', 'desc')->paginate(10);

        $sucursal = Auth::user()->sucursal;
        $caja = Caja::orderBy('id', 'desc')->first();
        
        if($caja == null){
            $aperturado = false;
            $numeroCaja = "00".$sucursal." - 1";
            $montoCierre = 0;

        }else{
            $cajaAbierta = Caja::where('estado', true)->orderBy('id', 'desc')->first();
            if($cajaAbierta == null){
                $aux = explode("-", $caja->numero);
                $aux2 = $aux[1] + 1;
                $numeroCaja = "00".$sucursal." - ".$aux2;
                $aperturado = false;
                $montoCierre = 0;

            }else{
                $numeroCaja = $cajaAbierta->numero;
                $aperturado = true;
                $ventas = DocumentoVenta::where('numero_caja', $numeroCaja)->get(); 
                $montoCierre = 0;
                foreach ($ventas as $key => $value) {
                    $montoCierre = $montoCierre + $value->monto_total;
                }
            }
        }

        $movimientoCaja = MovimientoCaja::orderBy('id', 'desc')->first();
        if($movimientoCaja == null){
            $numeroMovimiento = "00".$sucursal." - 1";

        }else{
            $aux = explode("-", $movimientoCaja->numero);
            $aux2 = $aux[1] + 1;
            $numeroMovimiento = "00".$sucursal." - ".$aux2;
        }

        return view('ventas.caja', ['datos' => $datos,
                                    'cajas' => $cajas,
                                    'aperturado' => $aperturado,
                                    'numeroCaja' => $numeroCaja,
                                    'montoCierre' => $montoCierre,
                                    'numeroMovimiento' => $numeroMovimiento]);
    }

    /**
     * Aperturar caja
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function aperturarCaja(Request $request)
    {
        date_default_timezone_set('America/Lima');
        $numeroCaja = $request->get('numeroCaja');
        $montoApertura = $request->get('montoApertura');

        $response = array();
        
        $caja = new Caja();
        $caja->numero = $numeroCaja;
        $caja->monto_apertura = $montoApertura;
        $caja->estado = true;
        $caja->save();

        $response["estado"] = true;
        return json_encode($response);

    }

    /**
     * Cerrar caja
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cerrarCaja(Request $request)
    {
        date_default_timezone_set('America/Lima');
        $numeroCaja = $request->get('numeroCaja');
        $montoCierre = $request->get('montoCierre');
        $montoReal = $request->get('montoReal');
        $comentario = $request->get('comentario');

        $response = array();
        
        $caja = Caja::where('numero', $numeroCaja)->first();
        $caja->monto_cierre = $montoCierre;
        $caja->monto_real = $montoReal;
        $caja->comentario = $comentario;
        $caja->estado = false;
        $caja->save();

        $response["estado"] = true;
        return json_encode($response);

    }

    /**
     * Listar conceptos para movimiento de cajas
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function listarConceptos(Request $request){
        $tipo = $request->get('tipo');
        $conceptos = DB::table('conceptos')
                     ->where('tipo', $tipo)
                     ->where('estado', true)
                     ->get();
        
        return json_encode($conceptos);
    }

}
