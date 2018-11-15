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
use App\Linea;
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
        $modelos = Modelo::where('estado', true)->orderBy('id', 'asc')->get();
        $colores = Color::where('estado', true)->orderBy('id', 'asc')->get();
        $tallas  = Talla::where('estado', true)->orderBy('id', 'asc')->get();
        $lineas  = Linea::where('estado', true)->orderBy('id', 'asc')->get();
                            
        return view('base.productos', ['productos'=> $productos, 
                                        'datos'   => $datos,
                                        'marcas'  => $marcas,
                                        'modelos' => $modelos,
                                        'colores' => $colores,
                                        'tallas'  => $tallas,
                                        'lineas'  => $lineas]);                            
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
        //$codigo = $this->generarCodigo(8);
        $existeCodigo = Producto::where('codigo', $codigo)->first();
        
        while ($existeCodigo) {
            $codigo = $this->generarCodigo(8);
        }

        $existeCodigo = Producto::where('codigo', $codigo)->exists();
        if($existeCodigo){
            $response["estado"] = false;
            $response["mensaje"] = "Este código ya se encuentra registrado";

        }else{
            $existeProducto = Producto::where([['marca', $marca],
                                            ['modelo', $modelo],
                                            ['color', $color],
                                            ['talla', $talla],
                                            ['linea', $linea]])->exists();

            if($existeProducto){
                $productoActivo = Producto::where([['marca', $marca],
                                                ['modelo', $modelo],
                                                ['color', $color],
                                                ['talla', $talla],
                                                ['linea', $linea],
                                                ['estado', true]])->exists();
                if($productoActivo){
                    $response["estado"] = false;
                    $response["mensaje"] = "El producto ya se encuentra registrado";

                }else{
                    $producto = Producto::where([['marca', $marca],
                                                ['modelo', $modelo],
                                                ['color', $color],
                                                ['talla', $talla],
                                                ['linea', $linea]])->first();
                    $producto->estado = true;
                    $response["estado"] = true;
                    $response["mensaje"] = "";                                             
                }                                                                 

            }else{
                /** Tabla PRODUCTOS */
                $producto = new Producto();
                $producto->codigo = $codigo;
                $producto->descripcion = $descripcion;
                $producto->marca = $marca;
                $producto->modelo = $modelo;
                $producto->color = $color;
                $producto->talla = $talla;
                $producto->linea = $linea;
                $producto->precio_compra = $precioCompra;
                $producto->precio_venta = $precioVenta;
                $producto->estado = true;
                $producto->save();

                $response["estado"] = true;
                $response["mensaje"] = "";

                // Tabla INVENTARIO 
                $inventario = new Inventario();
                $inventario->codigo_producto = $codigo;
                //$inventario->sucursal = Auth::user()->sucursal;
                $inventario->sucursal = 1;
                $inventario->cantidad= 0;
                $inventario->estado = true;
                $inventario->save();
                
                $response["estado"] = true;
                $response["mensaje"] = "";
            }
        }
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
        //$productos = Producto::where([['codigo', 'LIKE', '%'.$codigo.'%'], ['estado', true]])->get();
        $productos = DB::table('productos')
                     ->join('inventario', 'inventario.codigo_producto', 'productos.codigo')
                     ->select('productos.*', 'inventario.cantidad as cantidad')
                     ->where('productos.codigo', 'LIKE', $codigo.'%')
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
        
        //$productos = Producto::where([['codigo', 'LIKE', '%'.$codigo.'%'], ['estado', true]])->get();
        $productos = DB::table('productos')
                     ->join('inventario', 'inventario.codigo_producto', 'productos.codigo')
                     ->select('productos.*', 'inventario.cantidad as cantidad')
                     ->where('productos.codigo', 'LIKE', $codigo.'%')
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
                    ->join('marcas', 'marcas.id', '=', 'productos.marca')
                    ->join('modelos', 'modelos.id', '=', 'productos.modelo')
                    ->join('colores', 'colores.id', '=', 'productos.color')
                    ->join('tallas', 'tallas.id', '=', 'productos.talla')
                    ->join('lineas', 'lineas.id', '=', 'productos.linea')
                    ->select('productos.codigo', 'productos.precio_compra', 'productos.precio_venta',
                             'marcas.nombre as marca', 'modelos.nombre as modelo',
                             'colores.nombre as color', 'tallas.nombre as talla',
                             'lineas.nombre as linea')
                    ->where('productos.codigo', $codigo)         
                    ->first();

        return json_encode($producto);
    }

}
