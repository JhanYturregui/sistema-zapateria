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
        $documentos = DocumentoAlmacen::where('estado', true)->orderBy('created_at', 'desc')->paginate(10);
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
        $numeroDoc = $request->get('numeroDoc');
        $tipoDoc = $request->get('tipoDoc');
        $suc = $request->get('sucursal');
        $comentario = $request->get('comentario');
        $productos = $request->get('productos');
        $dataProd = array();
        $cantProd = array();
        $response = array();

        // INGRESO
        if($tipoDoc == 'ingreso'){
            foreach ($productos as $key => $value) {
                $codigo = $value["codigo"];
                $cantidad = $value["cantidad"];

                array_push($dataProd, $codigo);
                array_push($cantProd, $cantidad);

                // Tabla Inventario
                $inventario = Inventario::where('codigo_producto', $codigo)->first();
                $cantProducto = $inventario->cantidad;
                $cantProducto = $cantProducto + $cantidad;
                $inventario->cantidad = $cantProducto;
                $inventario->save();
            }

        // SALIDA    
        }else{
            // Verificar stock disponible
            foreach ($productos as $key => $value) {
                $codigo = $value["codigo"];
                $cantidad = $value["cantidad"];

                // Tabla Inventario
                $inventario = Inventario::where('codigo_producto', $codigo)->first();
                $cantProducto = $inventario->cantidad;
                $cantProducto = $cantProducto - $cantidad;

                if($cantProducto < 0){
                    $response["estado"] = false;
                    $response["mensaje"] = "El producto con código ".$codigo." no tiene el stock suficiente para realizar esta operación";

                    return json_encode($response);
                }
            }

            foreach ($productos as $key => $value) {
                $codigo = $value["codigo"];
                $cantidad = $value["cantidad"];

                array_push($dataProd, $codigo);
                array_push($cantProd, $cantidad);

                // Tabla Inventario
                $inventario = Inventario::where('codigo_producto', $codigo)->first();
                $cantProducto = $inventario->cantidad;
                $cantProducto = $cantProducto - $cantidad;
                $inventario->cantidad = $cantProducto;
                $inventario->save();
            }
        }
        $dataProd = json_encode($dataProd);
        $cantProd = json_encode($cantProd);

        // Tabla DOCUMENTOS_ALMACEN
        $documento = new DocumentoAlmacen();
        $documento->numero = $numeroDoc;
        $documento->tipo = $tipoDoc;
        if($tipoDoc == 'ingreso'){
            $documento->origen = $suc;
        }else{
            $documento->destino = $suc;
        }
        $documento->usuario = Auth::user()->id;
        $documento->productos = $dataProd;
        $documento->cantidades = $cantProd;
        $documento->comentario = $comentario;
        $documento->estado = true;
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

        $productos = $documento->productos;
        $productos = json_decode($productos);
        $cantidades = $documento->cantidades;
        $cantidades = json_decode($cantidades);
        $tipo = $documento->tipo;

        $response = array();
        
        if($tipo == 'salida'){
            for($i=0; $i<count($productos); $i++){
                $inventario = Inventario::where('codigo_producto', $productos[$i])->first();
                $cantidadActual = $inventario->cantidad;
                $nuevaCantidad = $cantidadActual + $cantidades[$i];

                $inventario->cantidad = $nuevaCantidad;
                $inventario->save();  
            }
            $response["estado"] = true;
            $response["mensaje"] = "";

        }else{
            for($i=0; $i<count($productos); $i++){
                $inventario = Inventario::where('codigo_producto', $productos[$i])->first();
                $cantidadActual = $inventario->cantidad;
                $nuevaCantidad = $cantidadActual - $cantidades[$i];

                if($nuevaCantidad<0){
                    $response["estado"] = false;
                    $response["mensaje"] = "El documento de almacén ".$numeroDoc." no puede ser anulado";

                    return json_encode($response);
                }  
            }

            for($i=0; $i<count($productos); $i++){
                $inventario = Inventario::where('codigo_producto', $productos[$i])->first();
                $cantidadActual = $inventario->cantidad;
                $nuevaCantidad = $cantidadActual - $cantidades[$i];
                
                $inventario->cantidad = $nuevaCantidad;
                $inventario->save();
            }
        }

        $documento->usuario_anulacion = Auth::user()->id;
        $documento->estado = false;
        $documento->save();

        $response["estado"] = false;
        $response["mensaje"] = "";

        return json_encode($response);

    }

}
