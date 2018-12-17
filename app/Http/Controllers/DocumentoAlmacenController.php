<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\DocumentoAlmacen;
use App\Inventario;
use App\Sucursal;

class DocumentoAlmacenController extends Controller
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
        $sucursales = Sucursal::where([['estado', true], ['id', '!=', $sucursal ]])->orderBy('id', 'asc')->get();
        $documentos = DocumentoAlmacen::where('origen', $sucursal)->orWhere('destino', $sucursal)->orderBy('created_at', 'desc')->paginate(10);
        $numeroDoc = DB::table('documentos_almacen')
                     ->select('numero')
                     ->orderBy('created_at', 'desc')->first();
        
        if($numeroDoc === null){
            $numeroDoc = "00".$sucursal." - 1";
        }else{
            $aux = explode("-", $numeroDoc->numero);
            $aux2 = $aux[1] + 1;
            $numeroDoc = "00".$sucursal." - ".$aux2;
        }

        return view('ventas.documentos_almacen', ['documentos'=> $documentos, 
                                                  'numeroDoc' => $numeroDoc,
                                                  'datos'     => $datos,
                                                  'sucursales'=> $sucursales]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $numeroDoc = $request->get('numeroDoc');
        $suc = $request->get('sucursal');
        $comentario = $request->get('comentario');
        $productos = $request->get('productos');
        $dataTallas = $request->get("dataTallas");
        $sucursal = Auth::user()->sucursal;

        $dataProd = array();
        $cantProd = array();
        $response = array();

        // Verificar stock
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
        
        $arrayCodigos = array();
        $arrayCantidades = array();
        foreach ($productos as $key => $value) {
            $codigo = $value["codigo"];
            $cantidad = $value["cantidad"];
            array_push($arrayCodigos, $codigo);
            array_push($arrayCantidades, $cantidad);
        }
        $jsonCodigos = json_encode($arrayCodigos);
        $jsonCantidades = json_encode($arrayCantidades);

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
        //print_r($arrayNTallas);exit;

        // Tabla DOCUMENTOS_ALMACEN
        $documento = new DocumentoAlmacen();
        $documento->numero = $numeroDoc;
        $documento->origen = Auth::user()->sucursal;
        $documento->destino = $suc;
        $documento->usuario = Auth::user()->id;
        $documento->productos = $jsonCodigos;
        $documento->cantidades = $jsonCantidades;
        $documento->tallas = $jsonTallas;
        $documento->cantidad_talla = $jsonCantds;
        $documento->comentario = $comentario;
        $documento->estado = 1;
        $documento->save();
        
        $response["estado"] = true;
        $response["mensaje"] = "";

        return json_encode($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $numeroDoc = $request->get('numeroDoc');
        $response = array();

        $documento = DocumentoAlmacen::where('numero', $numeroDoc)->first();
        $origen = $documento->origen;
        $destino = $documento->destino;
        $productos = json_decode($documento->productos);
        $cantidades = json_decode($documento->cantidades);
        $tallas = json_decode($documento->tallas);
        $cantidadTalla = json_decode($documento->cantidad_talla);

        foreach ($productos as $key => $value) {
            $existe = Inventario::where([['codigo_producto', $value], ['sucursal', $destino]])->exists();
            if(!$existe){
                $inv = new Inventario();
                $inv->codigo_producto = $value;
                $inv->sucursal = $destino;
                $inv->cantidad = 0;
                $inv->tallas = json_encode(array());
                $inv->cantidad_talla = json_encode(array());
                $inv->estado = true;
                $inv->save();
            }
        }

        // Inventario de quien envía
        foreach ($productos as $key => $value) {
            $inv = Inventario::where([['codigo_producto', $value], ['sucursal', $origen]])->first();
            $auxTallas = json_decode($inv->tallas);
            $auxCantidades = json_decode($inv->cantidad_talla);
            for($i=0; $i<count($tallas); $i++) {
                $aux = explode("*", $tallas[$i]);
                $cod = $aux[0];
                $tall = $aux[1];
                if($cod == $value){
                    $ind = array_search($tall, $auxTallas);
                    $nuevaCant = $auxCantidades[$ind] - $cantidadTalla[$i];
                    $auxCantidades[$ind] = $nuevaCant;
                }
            }
            
            $jsonTallas = json_encode($auxTallas);
            $jsonCantidades = json_encode($auxCantidades);
            $aux2 = $inv->cantidad - $cantidades[$key];

            $inv->cantidad = $aux2;
            $inv->tallas = $jsonTallas;
            $inv->cantidad_talla = $jsonCantidades;
            $inv->save();
        }
        
        // Inventario de quien acepta
        foreach ($productos as $key => $value) {
            $inv = Inventario::where([['codigo_producto', $value], ['sucursal', $destino]])->first();
            $auxTallas = json_decode($inv->tallas);
            $auxCantidades = json_decode($inv->cantidad_talla);
            for($i=0; $i<count($tallas); $i++) {
                $aux = explode("*", $tallas[$i]);
                $cod = $aux[0];
                $tall = $aux[1];
                if($cod == $value){
                    if(in_array($tall, $auxTallas)){
                        $ind = array_search($tall, $auxTallas);
                        $nuevaCant = $auxCantidades[$ind] + $cantidadTalla[$i];
                        $auxCantidades[$ind] = $nuevaCant;

                    }else{
                        array_push($auxTallas, $tall);
                        array_push($auxCantidades, $cantidadTalla[$i]);
                    }
                }
            }
            $jsonTallas = json_encode($auxTallas);
            $jsonCantidades = json_encode($auxCantidades);
            $aux2 = $inv->cantidad + $cantidades[$key];

            $inv->cantidad = $aux2;
            $inv->tallas = $jsonTallas;
            $inv->cantidad_talla = $jsonCantidades;
            $inv->save();
        }

        $documento->estado = 2;
        $documento->save();

        $response["estado"] = true;
        $response["mensaje"] = "";

        return json_encode($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function anularDocumento(Request $request)
    {
        $numeroDoc = $request->get('numeroDoc');
        $documento = DocumentoAlmacen::where('numero', $numeroDoc)->first();
        $response = array();

        if($documento->estado == 2){
            $productos = $documento->productos;
            $productos = json_decode($productos);
            $cantidades = $documento->cantidades;
            $cantidades = json_decode($cantidades);
            $origen = $documento->origen;
            $destino = $documento->destino;

            for($i=0; $i<count($productos); $i++){
                $invOri = Inventario::where([['codigo_producto', $productos[$i], ['sucursal', $origen]]])->first();
                $cant = $invOri->cantidad;
                $cant = $cant + $cantidades[$i];
                $invOri->cantidad = $cant;
                $invOri->save();

                $invDest = Inventario::where([['codigo_producto', $productos[$i], ['sucursal', $destino]]])->first();
                $cant = $invDest->cantidad;
                $cant = $cant - $cantidades[$i];
                if($cant<0){
                    $response["estado"] = false;
                    $response["mensaje"] = "No hay stock suficiente para realizar esta operación";

                    return json_encode($response);
                }
                $invDest->cantidad = $cant;
                $invDest->save();
            }
        }

        $documento->estado = 3;
        $documento->usuario_anulacion = Auth::user()->id;
        $documento->save();

        $response["estado"] = true;
        $response["mensaje"] = "";

        return json_encode($response);
    }

}
