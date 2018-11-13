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
        
        $sucursal = Auth::user()->sucursal;
        $cajaSuc = '00'.$sucursal.' -';
        $contador = Caja::where('numero', 'like', $cajaSuc.'%')->orderBy('id', 'desc')->count();
        
        if($contador == 0){
            $aperturado = false;
            $numeroCaja = "00".$sucursal." - 1";
            $montoCierre = 0;

        }else{
            $cajaAbierta = Caja::where([['estado', true], ['numero', 'like', $cajaSuc.'%']])->orderBy('id', 'desc')->first();

            if($cajaAbierta == null){
                $caja = Caja::orderBy('created_at', 'desc')->first();
                $aux = explode("-", $caja->numero);
                $aux2 = $aux[1] + 1;
                $numeroCaja = "00".$sucursal." - ".$aux2;
                $aperturado = false;
                $montoCierre = 0;

            }else{
                $numeroCaja = $cajaAbierta->numero;
                $aperturado = true;
                $montoCierre = $cajaAbierta->monto_apertura;

                $ventas = DocumentoVenta::where([['numero_caja', $numeroCaja], ['estado', true]])->get(); 
                foreach ($ventas as $key => $value) {
                    $montoCierre = $montoCierre + $value->monto_total;
                }

                $movimientos = MovimientoCaja::where([['numero_caja', $numeroCaja], ['estado', true]])->get();
                foreach ($movimientos as $key => $value) {
                    $tipo = $value->tipo;
                    if($tipo == 'ingreso'){
                        $montoCierre = $montoCierre + $value->monto;
                    }else{
                        $montoCierre = $montoCierre - $value->monto;
                    }
                }
            }
        }
        
        $movimientos = MovimientoCaja::where([['numero_caja', $numeroCaja], ['estado', true]])->paginate(10);

        $movimientoCaja = MovimientoCaja::where('numero_caja', $numeroCaja)->orderBy('id', 'desc')->first();
        if($movimientoCaja == null){
            $numeroMovimiento = "00".$sucursal." - 1";

        }else{
            $aux = explode("-", $movimientoCaja->numero);
            $aux2 = $aux[1] + 1;
            $numeroMovimiento = "00".$sucursal." - ".$aux2;
        }

        return view('ventas.caja', ['datos' => $datos,
                                    'movimientos' => $movimientos,
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
        $numeroCaja = $request->get('numeroCaja');
        $montoApertura = $request->get('montoApertura');

        $response = array();
        
        $caja = new Caja();
        $caja->numero = $numeroCaja;
        $caja->monto_apertura = $montoApertura;
        $caja->usuario_apertura = Auth::user()->id;
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
        $numeroCaja = $request->get('numeroCaja');
        $montoCierre = $request->get('montoCierre');
        $montoReal = $request->get('montoReal');
        $comentario = $request->get('comentario');

        $response = array();
        
        $caja = Caja::where('numero', $numeroCaja)->first();
        $caja->monto_cierre = $montoCierre;
        $caja->monto_real = $montoReal;
        $caja->comentario = $comentario;
        $caja->usuario_cierre = Auth::user()->id;
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

    /**
     * Generar movimiento de caja
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generarMovimiento(Request $request){
        $numero = $request->get('numero');
        $tipo = $request->get('tipo');
        $concepto = $request->get('concepto');
        $persona = $request->get('persona');
        $monto = $request->get('monto');
        $comentario = $request->get('comentario');
        
        $sucursal = Auth::user()->sucursal;
        $cajaSuc = '00'.$sucursal.' -';
        $caja = Caja::where([['numero', 'like', $cajaSuc.'%'], ['estado', true]])->orderBy('id', 'desc')->first();
        $numeroCaja = $caja->numero;

        $existeMov = MovimientoCaja::where([['numero', $numero], ['numero_caja', $numeroCaja]])->exists();
        if($existeMov){
            $mov = MovimientoCaja::where('numero', 'like', $cajaSuc.' -')->orderBy('id', 'desc')->first();
            $aux = explode(" - ", $mov->numero);
            $aux2 = $aux[1] + 1;
            $numero = '00'.$sucursal.' - '.$aux2;
        }

        $movimiento = new MovimientoCaja();
        $movimiento->numero = $numero;
        $movimiento->tipo = $tipo;
        $movimiento->concepto = $concepto;
        $movimiento->doc_persona = $persona;
        $movimiento->monto = $monto;
        $movimiento->usuario = Auth::user()->id;
        $movimiento->comentario = $comentario;
        $movimiento->numero_caja = $numeroCaja;
        $movimiento->estado = true;
        $movimiento->save();

        $response = array();
        $response["estado"] = true;
        
        return json_encode($response);
    }

    /**
     * 
     */
    public function anularMovimiento(Request $request){
        $numeroMovimiento = $request->get('numeroMov');
        $movimiento = MovimientoCaja::where('numero', $numeroMovimiento)->first();
        $movimiento->estado = false;
        $movimiento->save();

        $response = array();
        $response["estado"] = true;
        return json_encode($response);
    }

}
