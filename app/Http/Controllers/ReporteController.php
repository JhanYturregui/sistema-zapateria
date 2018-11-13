<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Caja;
use App\DocumentoVenta;
use App\MovimientoCaja;

class ReporteController extends Controller
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
        $auxSuc = '00'.$sucursal;
        $reportes = Caja::where([['numero', 'like', $auxSuc.'%'], ['estado', false]])->orderBy('created_at', 'desc')->paginate(10);


        return view('reportes.index', ['datos' => $datos,
                                    'reportes' => $reportes]);
    }

    /**
     * Ver detalles de reporte.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detallesReporte(Request $request)
    {
        $numReporte = $request->get('numeroReporte');
        $fechaRep = DB::table('caja')->where('numero', $numReporte)->value('updated_at');
        $montoTotal = DB::table('caja')->where('numero', $numReporte)->value('monto_cierre');
        $montoReal = DB::table('caja')->where('numero', $numReporte)->value('monto_real');
        $aux = explode(" ", $fechaRep);
        $fecha = $aux[0];
        $hora = $aux[1];
        
        $ventas = DocumentoVenta::where([['numero_caja', $numReporte], ['estado', true]])->get();
        $contVentas = DocumentoVenta::where([['numero_caja', $numReporte], ['estado', true]])->count();

        $movimientos = MovimientoCaja::where([['numero_caja', $numReporte], ['estado', true]])->get();
        $contMovimientos = MovimientoCaja::where([['numero_caja', $numReporte], ['estado', true]])->count();
        //print_r($contVentas);exit;
        $response = array();
        $response["fecha"] = $fecha;
        $response["hora"] = $hora;
        $response["ventas"] = $ventas;
        $response["contVentas"] = $contVentas;
        $response["movimientos"] = $movimientos;
        $response["contMovimientos"] = $contMovimientos;
        $response["montoTotal"] = $montoTotal;
        $response["montoReal"] = $montoReal;
        
        return json_encode($response);
    }
}
