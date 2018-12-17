<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Producto;
use App\Marca;
use App\Modelo;
use App\Color;
use App\Talla;
use App\Taco;
use App\Linea;
use App\Linea2;
use App\Linea3;
use App\Inventario;

class ProductoController extends Controller
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

        $productos = Producto::where('estado', true)
                            ->orderBy('id', 'desc')
                            ->paginate(10);
        
        $marcas  = Marca::where('estado', true)->orderBy('id', 'asc')->get();
        $colores = Color::where('estado', true)->orderBy('id', 'asc')->get();
        $tacos  = Taco::where('estado', true)->orderBy('id', 'asc')->get();
        $lineas  = Linea::where('estado', true)->orderBy('id', 'asc')->get();
        $lineas2  = Linea2::where('estado', true)->orderBy('id', 'asc')->get();
        $lineas3  = Linea3::where('estado', true)->orderBy('id', 'asc')->get();
                            
        return view('base.productos', ['productos'=> $productos, 
                                        'datos'   => $datos,
                                        'marcas'  => $marcas,
                                        'colores' => $colores,
                                        'tacos'  => $tacos,
                                        'lineas'  => $lineas,                            
                                        'lineas2'  => $lineas2,                           
                                        'lineas3'  => $lineas3]);                            
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
        $modelo = mb_strtoupper($request->get('modelo'));
        $descripcion = $request->get('descripcion');
        $marca = $request->get('marca');
        $color = $request->get('color');
        $taco = $request->get('taco');
        $linea = $request->get('linea');
        $linea2 = $request->get('linea2');
        $linea3 = $request->get('linea3');
        $precioCompra = $request->get('precioCompra');
        $precioVenta = $request->get('precioVenta');

        $response = array();
        $añoActual = date('y');
        $mesActual = date('n');

        //Temporada
        if($mesActual==1||$mesActual==2||$mesActual==3||$mesActual==4||$mesActual==5||$mesActual==11){
            $temporada = 1;
        }else{
            $temporada = 2;
        }
        
        $existeProducto = Producto::where([['modelo', $modelo],
                                           ['marca', $marca],
                                           ['color', $color],
                                           ['linea', $linea],
                                           ['linea_2', $linea2],
                                           ['linea_3', $linea3]])->exists();
        if($existeProducto){
            $prod = Producto::where([['modelo', $modelo],
                                     ['marca', $marca],
                                     ['color', $color],
                                     ['linea', $linea],
                                     ['linea_2', $linea2],
                                     ['linea_3', $linea3]])->orderBy('id', 'desc')->first();
            $codProd = $prod->codigo;
            $codigoPr = "";
            $aux = str_split($codProd, 1); 

            for($i=3; $i<count($aux); $i++){
                $codigoPr.= $aux[$i];
            }  
            $correlativoAño = $aux[0].$aux[1];
            $correlativoTemp = $aux[2];

            if($añoActual == $correlativoAño){
                if($correlativoTemp == $temporada){
                    $response["estado"] = false;
                    $response["mensaje"] = "Este producto ya se encuentra registrado. Código: ".$codProd;
                    return json_encode($response);

                }else{
                    $nuevoCodigo = $añoActual.$temporada.$codigoPr;       
                }

            }else{
                $nuevoCodigo = $añoActual.$temporada.$codigoPr;
            } 

        }else{
            $aux = Producto::orderBy('id', 'desc')->first();
            $ultimoId = $aux->id;
            $nuevoId = $ultimoId+1;
            $nuevoCodigo = $añoActual.$temporada.$nuevoId;
        }

        // Tabla PRODUCTOS
        $producto = new Producto();
        $producto->codigo = $nuevoCodigo;
        $producto->modelo = $modelo;
        $producto->descripcion = $descripcion;
        $producto->marca = $marca;
        $producto->color = $color;
        $producto->taco = $taco;
        $producto->linea = $linea;
        $producto->linea_2 = $linea2;
        $producto->linea_3 = $linea3;
        $producto->precio_compra= $precioCompra;
        $producto->precio_venta = $precioVenta;
        $producto->estado = true;
        $producto->save();

        // Tabla INVENTARIO
        $inventario =  new Inventario();
        $inventario->codigo_producto = $nuevoCodigo;
        $inventario->sucursal = 1;
        $inventario->cantidad = 0;
        $inventario->tallas = json_encode(array());
        $inventario->cantidad_talla = json_encode(array());
        $inventario->estado = true;
        $inventario->save();   

        $response["estado"] = true;
        $response["mensaje"] = "";

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
    public function edit(Request $request)
    {
        $id = $request->get('id');
        $producto = Producto::where([['id', $id], ['estado', true]])->first();

        return json_encode($producto);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->get('id');
        $codigo = $request->get('codigo');
        $descripcion = $request->get('descripcion');
        $marca = $request->get('marca');
        $modelo = $request->get('modelo');
        $color = $request->get('color');
        $talla = $request->get('talla');
        $linea = $request->get('linea');
        $precioCompra = $request->get('precioCompra');
        $precioVenta = $request->get('precioVenta');
     
        $response = array();

        $existeCodigo = Producto::where('codigo', $codigo)->exists();
        if($existeCodigo){
            $response["estado"] = false;
            $response["mensaje"] = "Este código ya se encuentra registrado";

        }else{
            $existeProducto2 = Producto::where([['marca', $marca],
                                        ['modelo', $modelo],
                                        ['color', $color],
                                        ['talla', $talla],
                                        ['linea', $linea]])->exists();

            if($existeProducto2){
                $response["estado"] = false;
                $response["mensaje"] = "El producto ya se encuentra registrado";  

            }else{
                $producto = Producto::where('codigo', $codigo)->first();
                $producto->codigo = $codigo;
                $producto->descripcion = $descripcion;
                $producto->marca = $marca;
                $producto->modelo = $modelo;
                $producto->color = $color;
                $producto->talla = $talla;
                $producto->linea = $linea;
                $producto->precio_compra = $precioCompra;
                $producto->precio_venta = $precioVenta;
                $producto->save();
                
                $response["estado"] = true;
                $response["mensaje"] = "";
            }
        }

        return json_encode($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->get('id');
        $producto = Producto::where('id', $id)->first();
        $producto->estado = false;
        $producto->save();

        $response = array();
        $response["estado"] = true;
        
        return json_encode($response);
    }

    /**
     * Obtener código para un producto
     * @param int $tamañoCodigo
     * @return String
     */
    public function generarCodigo($tamañoCodigo) {
        $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $tamaño = strlen($caracteres);
        $codigo = '';
        for ($i = 0; $i<$tamañoCodigo; $i++) {
            $codigo .= $caracteres[rand(0, $tamaño - 1)];
        }
        return $codigo;
    } 

    /**
     * Buscar productos por código
     * @param Request $request
     * @return json $productos
     */
    public function buscarProductos(Request $request){
        $codigo = mb_strtoupper($request->get('codigo'));
        $productos = DB::table('productos')
                     ->join('inventario', 'inventario.codigo_producto', 'productos.codigo')
                     ->select('productos.*', 'inventario.cantidad as cantidad')
                     ->where('productos.codigo', 'LIKE', $codigo.'%')
                     ->where('inventario.sucursal', Auth::user()->sucursal)
                     ->where('inventario.cantidad', '>', '0')
                     ->where('productos.estado', true)
                     ->get();

        return json_encode($productos);
    }

    /**
     * Buscar productos para ventas por código
     * @param Request $request
     * @return json $productos
     */
    public function buscarProductosVentas(Request $request){
        $codigo = mb_strtoupper($request->get('codigo'));
        
        $productos = DB::table('productos')
                     ->join('inventario', 'inventario.codigo_producto', 'productos.codigo')
                     ->select('productos.*', 'inventario.cantidad as cantidad')
                     ->where('productos.codigo', 'LIKE', $codigo.'%')
                     ->where('inventario.sucursal', Auth::user()->sucursal)
                     ->where('inventario.cantidad', '>', 0)
                     ->where('productos.estado', true)
                     ->get();

        return json_encode($productos);
    }

    /**
     * Buscar productos para compras por código
     * @param Request $request
     * @return json $productos
     */
    public function buscarProductosCompras(Request $request){
        $codigo = mb_strtoupper($request->get('codigo'));
        
        //$productos = Producto::where([['codigo', 'LIKE', '%'.$codigo.'%'], ['estado', true]])->get();
        $productos = DB::table('productos')
                     ->join('inventario', 'inventario.codigo_producto', 'productos.codigo')
                     ->select('productos.*', 'inventario.cantidad as cantidad')
                     ->where('productos.codigo', 'LIKE', $codigo.'%')
                     ->where('inventario.sucursal', 1)
                     ->where('productos.estado', true)
                     ->get();

        return json_encode($productos);
    }

    /**
     * Buscar producto por código
     * @param Request $request
     * @return json $producto
     */
    public function buscarProducto(Request $request){
        $codigo = mb_strtoupper($request->get('codigo'));
        $producto = DB::table('productos')
                    ->join('inventario', 'inventario.codigo_producto', '=', 'productos.codigo')
                    ->join('marcas', 'marcas.id', '=', 'productos.marca')
                    ->join('colores', 'colores.id', '=', 'productos.color')
                    ->join('tacos', 'tacos.id', '=', 'productos.taco')
                    ->join('lineas', 'lineas.id', '=', 'productos.linea')
                    ->join('linea2', 'linea2.id', '=', 'productos.linea_2')
                    ->join('linea3', 'linea3.id', '=', 'productos.linea_3')
                    ->select('productos.codigo', 'productos.descripcion', 
                             'productos.precio_compra', 'productos.precio_venta',
                             'inventario.tallas as tallas', 'marcas.nombre as marca', 
                             'colores.nombre as color', 'tacos.numero as taco',
                             'lineas.nombre as linea', 'linea2.nombre as linea2', 
                             'linea3.nombre as linea3')
                    ->where('productos.codigo', $codigo)         
                    ->first();

        return json_encode($producto);
    }

    public function listarxCod(Request $request){
        $codigo = $request->get('codigo');
        print_r($codigo);exit;
    }

}
