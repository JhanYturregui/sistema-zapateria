<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $descripcion = $request->get('descripcion');
        $marca = $request->get('marca');
        $modelo = $request->get('modelo');
        $color = $request->get('color');
        $talla = $request->get('talla');
        $linea = $request->get('linea');
        $precioCompra = $request->get('precioCompra');
        $precioVenta = $request->get('precioVenta');
     
        $response = array();
        $codigo = $this->generarCodigo(8);
        $existeCodigo = Producto::where('codigo', $codigo)->first();
        
        while ($existeCodigo) {
            $codigo = $this->generarCodigo(8);
        }

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

            /* Tabla INVENTARIO 
            $inventario = new Inventario();
            $inventario->codigo_producto = $codigo;
            $inventario->sucursal = 1;
            $inventario->cantidad= 0;
            $inventario->estado = true;
            $inventario->save();
            
            $response["estado"] = true;
            $response["mensaje"] = "";*/
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
        $descripcion = $request->get('descripcion');
        $marca = $request->get('marca');
        $modelo = $request->get('modelo');
        $color = $request->get('color');
        $talla = $request->get('talla');
        $linea = $request->get('linea');
     
        $response = array();

        $existeProducto = Producto::where([['marca', $marca],
                                           ['modelo', $modelo],
                                           ['color', $color],
                                           ['talla', $talla],
                                           ['linea', $linea],
                                           ['id', $id]])->exists();

        if($existeProducto){
            $response["estado"] = false;
            $response["mensaje"] = "Los datos del producto son los mismos";                                                                                                              

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
                $producto = Producto::where('id', $id)->first();
                $producto->descripcion = $descripcion;
                $producto->marca = $marca;
                $producto->modelo = $modelo;
                $producto->color = $color;
                $producto->talla = $talla;
                $producto->linea = $linea;
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
}
