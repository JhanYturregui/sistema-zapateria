<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\DocumentoVenta;
use App\Caja;
use App\Inventario;
use App\Persona;

class DocumentoVentaController extends Controller
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
        $caja = Caja::where([['estado', true], ['numero', 'like', $cajaSuc.'%']])->orderBy('id', 'desc')->first();
        
        if($caja == null){
            $aperturado = false;
            $ventas = DocumentoVenta::where('numero_caja', 'x')->orderBy('id', 'desc')->paginate(10);
            $numeroDoc = '';

        }else{
            $aperturado = true;
            $ventas = DocumentoVenta::where([['numero_caja', $caja->numero], ['estado', true]])->orderBy('id', 'desc')->paginate(10);

            $numeroDoc = DB::table('documentos_venta')
                        ->select('numero')
                        ->where('numero_caja', 'like', $cajaSuc.'%')
                        ->orderBy('created_at', 'desc')->first();
                    
            if($numeroDoc == null){
                $numeroDoc = "00".$sucursal." - 1";

            }else{
                $aux = explode("-", $numeroDoc->numero);
                $aux2 = $aux[1] + 1;
                $numeroDoc = "00".$sucursal." - ".$aux2;
            }
        }

        return view('ventas.documentos_venta', ['numeroDoc' => $numeroDoc,
                                                'datos'     => $datos,
                                                'aperturado'=> $aperturado,
                                                'ventas'    => $ventas]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sucursal = Auth::user()->sucursal;
        $cajaSuc = '00'.$sucursal.' -';
        $caja = Caja::where([['numero', 'like', $cajaSuc.'%'], ['estado', true]])->orderBy('id', 'desc')->first();
        $numeroCaja = $caja->numero;
        $numeroDoc = $request->get('numeroDoc');
        $docPersona = $request->get('docPersona');
        $metodosPago = $request->get('metodosPago');
        $montosPago = $request->get('montosPago');
        $productos = $request->get('productos');
        $cantTotal = $request->get('cantTotal');

        $persona = Persona::where('numero_documento', $docPersona)->first();
        $roles = $persona->roles;
        $roles = json_decode($roles);
        $esCliente = false;
        foreach ($roles as $key => $value) {
            if($value == 2){
                $esCliente = true;
            }
        }
        if(!$esCliente){
            array_push($roles, "2");
            $roles = json_encode($roles);
            $persona->roles = $roles;
            $persona->save();
        }
        
        $jsonMetodosPago = [];
        foreach($metodosPago as $key => $value){
            array_push($jsonMetodosPago, $value);
        }
        $jsonMetodosPago = json_encode($jsonMetodosPago);
        
        $jsonMontosPago = [];
        foreach($montosPago as $key => $value){
            array_push($jsonMontosPago, $value);
        }
        $jsonMontosPago = json_encode($jsonMontosPago);
        
        $response = array();
        foreach ($productos as $key => $value) {
            $codigo = $value["codigo"];
            $cantidad = $value["cantidad"];
            
            $inventario = Inventario::where([['codigo_producto', $codigo], ['sucursal', $sucursal]])->first();
            $cantInv = $inventario->cantidad;
            if($cantidad > $cantInv){
                $response["estado"] = false;
                $response["mensaje"] = "La cantidad del producto ".$codigo." excede a nuestro stock";
                
                return json_encode($response);
            }
        }

        $arrCodigos = [];
        $arrCantidades = [];
        $arrDescuentos = [];
        foreach($productos as $key => $value){
            $codigo = $value["codigo"];
            $cantidad = $value["cantidad"];
            $descuento = $value["descuento"];
            array_push($arrCodigos, $codigo);
            array_push($arrCantidades, $cantidad);
            array_push($arrDescuentos, $descuento);

            $inventario = Inventario::where([['codigo_producto', $codigo], ['sucursal', $sucursal]])->first();
            $cantInv = $inventario->cantidad;
            $nuevaCantidad = $cantInv - $cantidad;
            $inventario->cantidad = $nuevaCantidad;
            $inventario->save();
            
        }
        $jsonCodigos = json_encode($arrCodigos);
        $jsonCantidades = json_encode($arrCantidades);
        $jsonDescuentos = json_encode($arrDescuentos);

        $existeDoc = DocumentoVenta::where('numero', $numeroDoc)->exists();
        if($existeDoc){
            $doc = DocumentoVenta::where('numero_caja', $numeroCaja)->orderBy('id', 'desc')->first();
            $aux = $doc->numero;
            $aux = explode(" - ", $aux);
            $aux2 = $aux[1] + 1;
            $numeroDoc = $aux[0]." - ".$aux2;
        }

        $venta = new DocumentoVenta();
        $venta->numero = $numeroDoc;
        $venta->usuario = Auth::user()->id;
        $venta->cliente = $docPersona;
        $venta->productos = $jsonCodigos;
        $venta->cantidades = $jsonCantidades;
        $venta->descuentos = $jsonDescuentos;
        $venta->monto_total = $cantTotal;
        $venta->modos_pago = $jsonMetodosPago;
        $venta->montos = $jsonMontosPago;
        $venta->numero_caja = $numeroCaja;
        $venta->estado = true;
        $venta->save();

        $response["estado"] = true;

        return json_encode($response);

    }

    /**
     * Anular un documento de venta.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function anularDocumento(Request $request)
    {
        $numeroDoc = $request->get('numeroDoc');
        $docVenta = DocumentoVenta::where('numero', $numeroDoc)->first();
        $productos = $docVenta->productos;
        $productos = json_decode($productos);
        $cantidades = $docVenta->cantidades;
        $cantidades = json_decode($cantidades);
        
        for($i=0; $i<count($productos); $i++){
            $codProd = $productos[$i];
            $cantidad = $cantidades[$i];
            $inventario = Inventario::where('codigo_producto', $codProd)->first();
            $cantActual = $inventario->cantidad;
            $nuevaCantidad = $cantActual + $cantidad;
            $inventario->cantidad = $nuevaCantidad;
            $inventario->save();    
        }
        
        $docVenta->usuario_anulacion = Auth::user()->id;
        $docVenta->estado = false;
        $docVenta->save();

        $response = array();
        $response["estado"] = true;
        return json_encode($response);

    }
}
