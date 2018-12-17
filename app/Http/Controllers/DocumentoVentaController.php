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
            $ventas = DocumentoVenta::where('numero_caja', $caja->numero)->orderBy('id', 'desc')->paginate(10);

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
        $dataTallas = $request->get('dataTallas');

        $response = array();

        // VERIFICAR STOCK
        foreach ($dataTallas as $k => $v) {
            for($i=0; $i<count($v); $i++){
                $cod = $v[$i]["codigo"];
                $tall = $v[$i]["talla"];
                $cantid = $v[$i]["cantidad"];
                $inv = Inventario::where([['codigo_producto', $cod], ['sucursal', $sucursal]])->first(); 
                $tempTallas = json_decode($inv->tallas);
                $tempCantidades = json_decode($inv->cantidad_talla);
                $ind = array_search($tall, $tempTallas);
                if($cantid > $tempCantidades[$ind]){
                    $response["estado"] = false;
                    $response["mensaje"] = "El producto con código: ".$cod." y talla ".$tall." excede el nivel de stock";
                    return json_encode($response);
                }
            }
        }

        // ROL CLIENTE
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
        
        // MÉTODOS DE PAGO
        $jsonMetodosPago = [];
        foreach($metodosPago as $key => $value){
            array_push($jsonMetodosPago, $value);
        }
        $jsonMetodosPago = json_encode($jsonMetodosPago);
        
        // MONTOS DE PAGO
        $jsonMontosPago = [];
        foreach($montosPago as $key => $value){
            array_push($jsonMontosPago, $value);
        }
        $jsonMontosPago = json_encode($jsonMontosPago);

        // INVENTARIO
        $arrCod = array();
        $arrCant = array();
        $arrDesc = array();
        foreach ($productos as $llave => $valor) {
            $codigo = $valor["codigo"];
            $cantidadTotal = $valor["cantidad"];
            $descuento = $valor["descuento"];

            array_push($arrCod, $codigo);
            array_push($arrCant, $cantidadTotal);
            array_push($arrDesc, $descuento);

            $inv = Inventario::where([['codigo_producto', $codigo], ['sucursal', $sucursal]])->first();
            $auxTallas = json_decode($inv->tallas);
            $auxCantidades = json_decode($inv->cantidad_talla);
            $nCantidad = $inv->cantidad;
            $nCantidad = $nCantidad - $cantidadTotal;
            foreach ($dataTallas as $k => $v) {
                $auxTall = array();
                $auxCantid = array();
                for($j=0; $j<count($v); $j++){
                    $cod = $v[$j]["codigo"];
                    
                    if($cod == $codigo){
                        $tall = $v[$j]["talla"];
                        $cantid = $v[$j]["cantidad"];
                        array_push($auxTall, $tall);
                        array_push($auxCantid, $cantid);
                    }
                }
                if(count($auxTall)>0){
                    $arrayNTallas = array();
                    $arrayNCantidades = array();
                    for($i=0; $i<count($auxTallas); $i++){
                        $talla = $auxTallas[$i];
                        $cantidad = $auxCantidades[$i];
                        if(in_array($talla, $auxTall)){
                            $ind = array_search($talla, $auxTall);
                            $nuevaCant = $cantidad - $auxCantid[$ind];
                            array_push($arrayNTallas, $talla);
                            array_push($arrayNCantidades, $nuevaCant);

                        }else{
                            array_push($arrayNTallas, $talla);
                            array_push($arrayNCantidades, $cantidad);
                        }
                    }
                }
            }
            $jsonTallas = json_encode($arrayNTallas);
            $jsonCantds = json_encode($arrayNCantidades);

            $inv->tallas = $jsonTallas;
            $inv->cantidad_talla = $jsonCantds;
            $inv->cantidad = $nCantidad;
            $inv->save();
        }
        $jsonCodigos = json_encode($arrCod);
        $jsonCantidades = json_encode($arrCant);
        $jsonDescuentos = json_encode($arrDesc);

        // TALLAS
        $arrayNTallas = array();
        $arrayNCantidades = array();
        foreach ($dataTallas as $k => $v) {
            for($i=0; $i<count($v); $i++){
                $cod = $v[$i]["codigo"];
                $tall = $v[$i]["talla"];
                $cantid = $v[$i]["cantidad"];
                $aux = $cod."*".$tall;
                array_push($arrayNTallas, $aux);
                array_push($arrayNCantidades, $cantid);
            }
        }
        $jsonTallas = json_encode($arrayNTallas);
        $jsonCantds = json_encode($arrayNCantidades);

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
        $venta->tallas = $jsonTallas;
        $venta->cantidad_talla = $jsonCantds;
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
        $productos = json_decode($docVenta->productos);
        $cantidades = json_decode($docVenta->cantidades);
        $tallas = json_decode($docVenta->tallas);
        $cantidadesTalla = json_decode($docVenta->cantidad_talla);
        $suc = Auth::user()->sucursal;
        
        for($i=0; $i<count($productos); $i++){
            $codProd = $productos[$i];
            $cantidad = $cantidades[$i];
            $inventario = Inventario::where([['codigo_producto', $codProd], ['sucursal', $suc]])->first();
            $auxTallas = json_decode($inventario->tallas);
            $auxCantidades = json_decode($inventario->cantidad_talla);
            for($j=0; $j<count($tallas); $j++){
                $aux = explode("*", $tallas[$j]);
                $cod = $aux[0];
                $tall = $aux[1];
                if($cod == $codProd){
                    $ind = array_search($tall, $auxTallas);
                    $nuevaCant = $auxCantidades[$ind] + $cantidadesTalla[$j];
                    $auxCantidades[$ind] = $nuevaCant;
                }
            }
            $jsonTallas = json_encode($auxTallas);
            $jsonCantds = json_encode($auxCantidades);

            $nuevaCantidad = $inventario->cantidad + $cantidad;
            $inventario->tallas = $jsonTallas;
            $inventario->cantidad_talla = $jsonCantds;
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

    /**

    */
    public function listarVendidos(Request $request){
        $numDoc = $request->get('numDoc');
        $numCaja = $request->get('numCaja');
        $response = array();

        $venta = DocumentoVenta::where([['numero', $numDoc], ['numero_caja', $numCaja]])->first();
        $tallas = json_decode($venta->tallas);
        $cantidades = json_decode($venta->cantidad_talla);
        $response["tallas"] = $tallas;
        $response["cantidades"] = $cantidades;

        return json_encode($response);
    }

    /**

    **/
    public function cambiarTalla(Request $request){
        $numDoc = $request->get('numDoc');
        $numCaja = $request->get('numCaja');
        $nuevasTallas = $request->get('nuevasTallas');
        $response = array();

        $venta = DocumentoVenta::where([['numero', $numDoc], ['numero_caja', $numCaja]])->first();
        $tallas = json_decode($venta->tallas);
        $cantidades = json_decode($venta->cantidad_talla);
        
        for($i=0; $i<count($nuevasTallas); $i++){
            if($nuevasTallas[$i] != ""){
                $aux = explode("*", $tallas[$i]);
                $cod = $aux[0];
                $tallAnt = $aux[1];

                $inv = Inventario::where('codigo_producto', $cod)->first();
                $invTallas = json_decode($inv->tallas);
                $invCantidades = json_decode($inv->cantidad_talla);

                // Regresar a stock talla anterior
                $ind = array_search($tallAnt, $invTallas);
                $nuevaCant = $invCantidades[$ind] + $cantidades[$i];
                $invCantidades[$ind] = $nuevaCant;

                // Reducir de stock nueva talla
                $ind2 = array_search($nuevasTallas[$i], $invTallas);
                $nuevaCant2 = $invCantidades[$ind2] - $cantidades[$i];
                $invCantidades[$ind2] = $nuevaCant2;

                $jsonTallas = json_encode($invTallas);
                $jsonCantidades = json_encode($invCantidades);

                $inv->tallas = $jsonTallas;
                $inv->cantidad_talla = $jsonCantidades;
                $inv->save();

                // VENTA
                $tallas[$i] = $cod."*".$nuevasTallas[$i];
            }
        }

        $jsonTallasVentas = json_encode($tallas);
        $venta->tallas = $jsonTallasVentas;
        $venta->save();

        $response["estado"] = true;
        $response["mensaje"] = "";

        return json_encode($response);
    } 
}
