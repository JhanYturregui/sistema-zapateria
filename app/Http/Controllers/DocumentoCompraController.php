<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\DocumentoCompra;
use App\Inventario;
use App\Talla;

class DocumentoCompraController extends Controller
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

        $ultDocumentoCompra = DocumentoCompra::orderBy('id', 'desc')->first();
        if($ultDocumentoCompra == null){
            $numeroDoc = '00001';
        }else{
            $numUlt = $ultDocumentoCompra->numero;
            $numUlt++;
            $numeroDoc = $numUlt;
            $aux = strlen($numeroDoc);
            switch ($aux) {
                case 1:
                    $numeroDoc = '0000'.$numeroDoc;
                    break;
                case 2:
                    $numeroDoc = '000'.$numeroDoc;
                    break;
                case 3:
                    $numeroDoc = '00'.$numeroDoc;
                    break;
                case 4:
                    $numeroDoc = '0'.$numeroDoc;
                    break;
            }
        }
        $compras = DocumentoCompra::orderBy('id', 'desc')->paginate(10);
        $tallas = Talla::where('estado', true)->orderBy('id', 'asc')->get();

        return view('base.documentos_compra', ['numeroDoc' => $numeroDoc,
                                                'datos'    => $datos,
                                                'compras'  => $compras,
                                                'tallas'   => $tallas]);
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
        $numeroDoc = $request->get("numeroDoc");
        $fechaDoc = $request->get("fechaDoc");
        $docProveedor = $request->get("docProveedor");
        $productos = $request->get("productos");
        $montoTotal = $request->get("montoTotal");
        $dataTallas = $request->get("dataTallas");

        $arrCod = array();
        $arrCan = array();
        $response = array();

        foreach ($productos as $key => $value) {
            $codigo = $value["codigo"];
            $cantProd = $value["cantidad"];

            array_push($arrCod, $codigo);
            array_push($arrCan, $cantProd);

            $inv = Inventario::where([['codigo_producto', $codigo], ['sucursal', 1]])->first();
            $auxTallas = json_decode($inv->tallas);
            $auxCantidades = json_decode($inv->cantidad_talla);

            foreach ($dataTallas as $k => $v) {
                $auxTall = array();
                $auxCantid = array();
                for ($j=0; $j<count($v); $j++){ 
                    $aux = $v[$j]["codigo"];
                    if($aux == $codigo){
                        $tall = $v[$j]["talla"];
                        $cantid = $v[$j]["cantidad"];
                        array_push($auxTall, $tall);
                        array_push($auxCantid, $cantid);
                    }
                }

                if(count($auxTall)>0){
                    $arrayNTallas = array();
                    $arrayNCantidades = array();
                    for($i=0; $i<count($auxTall); $i++){
                        $talla = $auxTall[$i];
                        $cantidad = $auxCantid[$i];
                        if(in_array($talla, $auxTallas)){
                            $ind = array_search($talla, $auxTallas);
                            $nuevaCant = $cantidad + $auxCantidades[$ind];
                            //array_push($arrayNTallas, $talla);
                            //array_push($arrayNCantidades, $nuevaCant);
                            $auxCantidades[$ind] = $nuevaCant;

                        }else{
                            array_push($auxTallas, $talla);
                            array_push($auxCantidades, $cantidad);
                        }
                    }
                }
            }
            //print_r($auxTallas);
            //sort($auxTallas);
            //sort($auxCantidades);
            $jsonTallas = json_encode($auxTallas);
            $jsonCantds = json_encode($auxCantidades);

            $cant = $inv->cantidad;
            $cant = $cant + $cantProd;
            $inv->cantidad = $cant;
            $inv->tallas = $jsonTallas;
            $inv->cantidad_talla = $jsonCantds;
            $inv->save();
        }
        $jsonCod = json_encode($arrCod);
        $jsonCan = json_encode($arrCan);

        // TALLAS
        $arrayNT = array();
        $arrayNC = array();
        foreach ($dataTallas as $k => $v) {
            for($i=0; $i<count($v); $i++){
                $cod = $v[$i]["codigo"];
                $tall = $v[$i]["talla"];
                $cantid = $v[$i]["cantidad"];
                $aux = $cod."*".$tall;
                array_push($arrayNT, $aux);
                array_push($arrayNC, $cantid);
            }
        }
        $jsonTallas = json_encode($arrayNT);
        $jsonCantds = json_encode($arrayNC);

        $documento = new DocumentoCompra();
        $documento->numero = $numeroDoc;
        $documento->fecha = $fechaDoc;
        $documento->usuario = Auth::user()->id;
        $documento->proveedor = $docProveedor;
        $documento->productos = $jsonCod;
        $documento->cantidades = $jsonCan;
        $documento->tallas = $jsonTallas;
        $documento->cantidad_talla = $jsonCantds;
        $documento->monto_total = $montoTotal;
        $documento->estado = true;
        $documento->save();

        $response["estado"] = true;
        $response["mensaje"] = "";

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
        $docCompra = DocumentoCompra::where('numero', $numeroDoc)->first();
        $productos = json_decode($docCompra->productos);
        $cantidades = json_decode($docCompra->cantidades);
        $tallas = json_decode($docCompra->tallas);
        $cantidadesTalla = json_decode($docCompra->cantidad_talla);
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
                    $nuevaCant = $auxCantidades[$ind] - $cantidadesTalla[$j];
                    $auxCantidades[$ind] = $nuevaCant;
                }
            }
            $jsonTallas = json_encode($auxTallas);
            $jsonCantds = json_encode($auxCantidades);

            $nuevaCantidad = $inventario->cantidad - $cantidad;
            $inventario->tallas = $jsonTallas;
            $inventario->cantidad_talla = $jsonCantds;
            $inventario->cantidad = $nuevaCantidad;
            $inventario->save();
        }
        
        $docCompra->usuario_anulacion = Auth::user()->id;
        $docCompra->estado = false;
        $docCompra->save();

        $response = array();
        $response["estado"] = true;

        return json_encode($response);
    }

}
