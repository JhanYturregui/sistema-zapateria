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
        $documentos = DocumentoVenta::where('estado', true)->orderBy('created_at', 'desc')->paginate(10);
        $numeroDoc = DB::table('documentos_venta')
                     ->select('numero')
                     ->orderBy('created_at', 'desc')->first();
        
        if($numeroDoc === null){
            $numeroDoc = "00".$sucursal." - 1";

        }else{
            $aux = explode("-", $numeroDoc->numero);
            $aux2 = $aux[1] + 1;
            $numeroDoc = "00".$sucursal." - ".$aux2;
        }

        $caja = Caja::where('estado', true)->first();
        if($caja == null){
            $aperturado = false;
            $ventas = [];
        }else{
            $aperturado = true;
            $ventas = DocumentoVenta::where('numero_caja', $caja->numero)->orderBy('id', 'desc')->paginate(10);
        }

        return view('ventas.documentos_venta', ['documentos'=> $documentos, 
                                                'numeroDoc' => $numeroDoc,
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
        $caja = Caja::where('estado', true)->first();
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
        foreach($productos as $key => $value){
            $codigo = $value["codigo"];
            $cantidad = $value["cantidad"];
            array_push($arrCodigos, $codigo);
            array_push($arrCantidades, $cantidad);

            $inventario = Inventario::where([['codigo_producto', $codigo], ['sucursal', $sucursal]])->first();
            $cantInv = $inventario->cantidad;
            $nuevaCantidad = $cantInv - $cantidad;
            $inventario->cantidad = $nuevaCantidad;
            $inventario->save();
            
        }
        $jsonCodigos = json_encode($arrCodigos);
        $jsonCantidades = json_encode($arrCantidades);

        $venta = new DocumentoVenta();
        $venta->numero = $numeroDoc;
        $venta->usuario = Auth::user()->id;
        $venta->cliente = $docPersona;
        $venta->productos = $jsonCodigos;
        $venta->cantidades = $jsonCantidades;
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
