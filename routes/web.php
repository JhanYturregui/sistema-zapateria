<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Auth::routes();
Route::get('/registrarSuper', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('/registrarSuper', 'Auth\RegisterController@register')->name('register');
Route::get('/', 'HomeController@index')->name('home');

/****** Rutas AUTENTICACIÓN *****/
Route::get('autenticacion', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');

/****** Rutas CATEGORÍAS *****/
Route::get('/categorias', [
    'uses' => 'CategoriaController@index',
    'as'   => 'categorias'
]);
Route::post('/categorias/crear', [
    'uses' => 'CategoriaController@store',
    'as'   => 'crear_categoria'
]);
Route::post('/categorias/editar', [
    'uses' => 'CategoriaController@edit',
    'as'   => 'editar_categoria'
]);
Route::patch('/categorias/actualizar', [
    'uses' => 'CategoriaController@update',
    'as'   => 'actualizar_categoria'
]);
Route::delete('/categorias/eliminar', [
    'uses' => 'CategoriaController@destroy',
    'as'   => 'eliminar_categoria'
]);

/****** Rutas OPCIONES DE CATEGORÍA *****/
Route::get('/opciones', [
    'uses' => 'OpcionController@index',
    'as'   => 'opciones'
]);
Route::post('/opciones/crear', [
    'uses' => 'OpcionController@store',
    'as'   => 'crear_opcion'
]);
Route::post('/opciones/editar', [
    'uses' => 'OpcionController@edit',
    'as'   => 'editar_opcion'
]);
Route::patch('/opciones/actualizar', [
    'uses' => 'OpcionController@update',
    'as'   => 'actualizar_opcion'
]);
Route::delete('/opciones/eliminar', [
    'uses' => 'OpcionController@destroy',
    'as'   => 'eliminar_opcion'
]);

/****** Rutas TIPOS DE USUARIO *****/
Route::get('/tipos_usuario', [
    'uses' => 'TipoUsuarioController@index',
    'as'   => 'tipos_usuario'
]);
Route::post('/tipos_usuario/crear', [
    'uses' => 'TipoUsuarioController@store',
    'as'   => 'crear_tipo_usuario'
]);
Route::post('/tipos_usuario/editar', [
    'uses' => 'TipoUsuarioController@edit',
    'as'   => 'editar_tipo_usuario'
]);
Route::patch('/tipos_usuario/actualizar', [
    'uses' => 'TipoUsuarioController@update',
    'as'   => 'actualizar_tipo_usuario'
]);
Route::delete('/tipos_usuario/eliminar', [
    'uses' => 'TipoUsuarioController@destroy',
    'as'   => 'eliminar_tipo_usuario'
]);
Route::post('/tipos_usuario/accesos', [
    'uses' => 'TipoUsuarioController@accesos',
    'as'   => 'accesos_tipo_usuario'
]);

/****** Rutas ROLES *****/
Route::get('/roles', [
    'uses' => 'RolController@index',
    'as'   => 'roles'
]);
Route::post('/roles/crear', [
    'uses' => 'RolController@store',
    'as'   => 'crear_tipo_usuario'
]);
Route::post('/roles/editar', [
    'uses' => 'RolController@edit',
    'as'   => 'editar_rol'
]);
Route::patch('/roles/actualizar', [
    'uses' => 'RolController@update',
    'as'   => 'actualizar_rol'
]);
Route::delete('/roles/eliminar', [
    'uses' => 'RolController@destroy',
    'as'   => 'eliminar_rol'
]);

/****** Rutas VENTAS *****/
Route::get('/ventas', [
    'uses' => 'RolController@index',
    'as'   => 'ventas'
]);

/****** Rutas ACCESOS *****/
Route::post('/accesos/configurarAccesos', [
    'uses' => 'AccesoController@configurarAccesos',
    'as'   => 'configurar_accesos'
]);
Route::post('/accesos/obtenerAccesos', [
    'uses' => 'AccesoController@obtenerAccesos',
    'as'   => 'obtener_accesos'
]);

/****** Rutas PERSONAS *****/
Route::get('/personas', [
    'uses' => 'PersonaController@index',
    'as'   => 'personas'
]);
Route::post('/personas/crear', [
    'uses' => 'PersonaController@store',
    'as'   => 'crear_persona'
]);
Route::post('/personas/editar', [
    'uses' => 'PersonaController@edit',
    'as'   => 'editar_persona'
]);
Route::patch('/personas/actualizar', [
    'uses' => 'PersonaController@update',
    'as'   => 'actualizar_persona'
]);
Route::delete('/personas/eliminar', [
    'uses' => 'PersonaController@destroy',
    'as'   => 'eliminar_persona'
]);
Route::post('/personas/buscar', [
    'uses' => 'PersonaController@buscarPersona',
    'as'   => 'buscar_persona'
]);

/****** Rutas USUARIOS *****/
Route::get('/usuarios', [
    'uses' => 'UsuarioController@index',
    'as'   => 'usuarios'
]);
Route::post('/usuarios/crear', [
    'uses' => 'UsuarioController@store',
    'as'   => 'crear_usuario'
]);
Route::post('/usuarios/editar', [
    'uses' => 'UsuarioController@edit',
    'as'   => 'editar_usuario'
]);
Route::patch('/usuarios/actualizar', [
    'uses' => 'UsuarioController@update',
    'as'   => 'actualizar_usuario'
]);
Route::delete('/usuarios/eliminar', [
    'uses' => 'UsuarioController@destroy',
    'as'   => 'eliminar_usuario'
]);

/****** Rutas COLORES *****/
Route::get('/colores', [
    'uses' => 'ColorController@index',
    'as'   => 'colores'
]);
Route::post('/colores/crear', [
    'uses' => 'ColorController@store',
    'as'   => 'crear_color'
]);
Route::post('/colores/editar', [
    'uses' => 'ColorController@edit',
    'as'   => 'editar_color'
]);
Route::patch('/colores/actualizar', [
    'uses' => 'ColorController@update',
    'as'   => 'actualizar_color'
]);
Route::delete('/colores/eliminar', [
    'uses' => 'ColorController@destroy',
    'as'   => 'eliminar_color'
]);

/****** Rutas TALLAS *****/
Route::get('/tallas', [
    'uses' => 'TallaController@index',
    'as'   => 'tallas'
]);
Route::post('/tallas/crear', [
    'uses' => 'TallaController@store',
    'as'   => 'crear_talla'
]);
Route::post('/tallas/editar', [
    'uses' => 'TallaController@edit',
    'as'   => 'editar_talla'
]);
Route::patch('/tallas/actualizar', [
    'uses' => 'TallaController@update',
    'as'   => 'actualizar_talla'
]);
Route::delete('/tallas/eliminar', [
    'uses' => 'TallaController@destroy',
    'as'   => 'eliminar_talla'
]);

/****** Rutas MARCAS *****/
Route::get('/marcas', [
    'uses' => 'MarcaController@index',
    'as'   => 'marcas'
]);
Route::post('/marcas/crear', [
    'uses' => 'MarcaController@store',
    'as'   => 'crear_marca'
]);
Route::post('/marcas/editar', [
    'uses' => 'MarcaController@edit',
    'as'   => 'editar_marca'
]);
Route::patch('/marcas/actualizar', [
    'uses' => 'MarcaController@update',
    'as'   => 'actualizar_marca'
]);
Route::delete('/marcas/eliminar', [
    'uses' => 'MarcaController@destroy',
    'as'   => 'eliminar_marca'
]);

/****** Rutas MODELOS *****/
Route::get('/modelos', [
    'uses' => 'ModeloController@index',
    'as'   => 'modelos'
]);
Route::post('/modelos/crear', [
    'uses' => 'ModeloController@store',
    'as'   => 'crear_modelo'
]);
Route::post('/modelos/editar', [
    'uses' => 'ModeloController@edit',
    'as'   => 'editar_modelo'
]);
Route::patch('/modelos/actualizar', [
    'uses' => 'ModeloController@update',
    'as'   => 'actualizar_modelo'
]);
Route::delete('/modelos/eliminar', [
    'uses' => 'ModeloController@destroy',
    'as'   => 'eliminar_modelo'
]);

/****** Rutas LINEAS *****/
Route::get('/lineas', [
    'uses' => 'LineaController@index',
    'as'   => 'lineas'
]);
Route::post('/lineas/crear', [
    'uses' => 'LineaController@store',
    'as'   => 'crear_linea'
]);
Route::post('/lineas/editar', [
    'uses' => 'LineaController@edit',
    'as'   => 'editar_linea'
]);
Route::patch('/lineas/actualizar', [
    'uses' => 'LineaController@update',
    'as'   => 'actualizar_linea'
]);
Route::delete('/lineas/eliminar', [
    'uses' => 'LineaController@destroy',
    'as'   => 'eliminar_linea'
]);

/****** Rutas PROVEEDORES *****/
Route::get('/proveedores', [
    'uses' => 'ProveedorController@index',
    'as'   => 'proveedores'
]);
Route::post('/proveedores/crear', [
    'uses' => 'ProveedorController@store',
    'as'   => 'crear_proveedor'
]);
Route::post('/proveedores/editar', [
    'uses' => 'ProveedorController@edit',
    'as'   => 'editar_proveedor'
]);
Route::patch('/proveedores/actualizar', [
    'uses' => 'ProveedorController@update',
    'as'   => 'actualizar_proveedor'
]);
Route::delete('/proveedores/eliminar', [
    'uses' => 'ProveedorController@destroy',
    'as'   => 'eliminar_proveedor'
]);

/****** Rutas PRODUCTOS *****/
Route::get('/productos', [
    'uses' => 'ProductoController@index',
    'as'   => 'productos'
]);
Route::post('/productos/crear', [
    'uses' => 'ProductoController@store',
    'as'   => 'crear_producto'
]);
Route::post('/productos/editar', [
    'uses' => 'ProductoController@edit',
    'as'   => 'editar_producto'
]);
Route::patch('/productos/actualizar', [
    'uses' => 'ProductoController@update',
    'as'   => 'actualizar_producto'
]);
Route::delete('/productos/eliminar', [
    'uses' => 'ProductoController@destroy',
    'as'   => 'eliminar_producto'
]);
Route::post('/productos/buscar', [
    'uses' => 'ProductoController@buscarProductos',
    'as'   => 'buscar_producto'
]);
Route::post('/productos/buscar_pro', [
    'uses' => 'ProductoController@buscarProducto',
    'as'   => 'buscar_producto_codigo'
]);
Route::post('/productos/buscar_pro_ventas', [
    'uses' => 'ProductoController@buscarProductosVentas',
    'as'   => 'buscar_producto_codigo'
]);
Route::post('/productos/buscar_pro_compras', [
    'uses' => 'ProductoController@buscarProductosCompras',
    'as'   => 'buscar_producto_codigo_compras'
]);

/****** Rutas SUCURSALES *****/
Route::get('/sucursales', [
    'uses' => 'SucursalController@index',
    'as'   => 'sucursales'
]);
Route::post('/sucursales/crear', [
    'uses' => 'SucursalController@store',
    'as'   => 'crear_sucursal'
]);
Route::post('/sucursales/editar', [
    'uses' => 'SucursalController@edit',
    'as'   => 'editar_sucursal'
]);
Route::patch('/sucursales/actualizar', [
    'uses' => 'SucursalController@update',
    'as'   => 'actualizar_sucursal'
]);
Route::delete('/sucursales/eliminar', [
    'uses' => 'SucursalController@destroy',
    'as'   => 'eliminar_sucursal'
]);

/****** Rutas CONCEPTOS *****/
Route::get('/conceptos', [
    'uses' => 'ConceptoController@index',
    'as'   => 'conceptos'
]);
Route::post('/conceptos/crear', [
    'uses' => 'ConceptoController@store',
    'as'   => 'crear_concepto'
]);
Route::post('/conceptos/editar', [
    'uses' => 'ConceptoController@edit',
    'as'   => 'editar_concepto'
]);
Route::patch('/conceptos/actualizar', [
    'uses' => 'ConceptoController@update',
    'as'   => 'actualizar_concepto'
]);
Route::delete('/conceptos/eliminar', [
    'uses' => 'ConceptoController@destroy',
    'as'   => 'eliminar_concepto'
]);

/****** Rutas DOCUMENTOS DE ALMACÉN *****/
Route::get('/documentos_almacen', [
    'uses' => 'DocumentoAlmacenController@index',
    'as'   => 'documentos_almacen'
]);
Route::post('/documentos_almacen/crear', [
    'uses' => 'DocumentoAlmacenController@store',
    'as'   => 'crear_documento_almacen'
]);
Route::delete('/documentos_almacen/anular', [
    'uses' => 'DocumentoAlmacenController@anularDocumento',
    'as'   => 'anular_documento_almacen'
]);

/****** Rutas DOCUMENTOS DE VENTA *****/
Route::get('/documentos_venta', [
    'uses' => 'DocumentoVentaController@index',
    'as'   => 'documentos_venta'
]);
Route::post('/documentos_venta/crear', [
    'uses' => 'DocumentoVentaController@store',
    'as'   => 'crear_documento_venta'
]);
Route::delete('/documentos_venta/anular', [
    'uses' => 'DocumentoVentaController@anularDocumento',
    'as'   => 'anular_documento_venta'
]);

/****** Rutas DOCUMENTOS DE COMPRA *****/
Route::get('/documentos_compra', [
    'uses' => 'DocumentoCompraController@index',
    'as'   => 'documentos_compra'
]);
Route::post('/documentos_compra/crear', [
    'uses' => 'DocumentoCompraController@store',
    'as'   => 'crear_documento_compra'
]);
Route::delete('/documentos_compra/anular', [
    'uses' => 'DocumentoCompraController@anularDocumento',
    'as'   => 'anular_documento_compra'
]);

/****** Rutas CAJA *****/
Route::get('/caja', [
    'uses' => 'CajaController@index',
    'as'   => 'caja'
]);
Route::post('/caja/aperturar', [
    'uses' => 'CajaController@aperturarCaja',
    'as'   => 'aperturar_caja'
]);
Route::post('/caja/cerrar', [
    'uses' => 'CajaController@cerrarCaja',
    'as'   => 'cerrar_caja'
]);
Route::post('/caja/conceptos', [
    'uses' => 'CajaController@listarConceptos',
    'as'   => 'conceptos_caja'
]);
Route::post('/caja/generar_movimiento', [
    'uses' => 'CajaController@generarMovimiento',
    'as'   => 'movimiento_caja'
]);
Route::post('/caja/anular_movimiento', [
    'uses' => 'CajaController@anularMovimiento',
    'as'   => 'anular_movimiento'
]);

/****** Rutas REPORTES *****/
Route::get('/reportes', [
    'uses' => 'ReporteController@index',
    'as'   => 'reportes'
]);
Route::post('/reportes/detalles', [
    'uses' => 'ReporteController@detallesReporte',
    'as'   => 'detalles_reporte'
]);

/****** Rutas NOTA CRÉDITO *****/
Route::get('/nota_credito', [
    'uses' => 'NotaCreditoController@index',
    'as'   => 'notas_credito'
]);
Route::post('/nota_credito/generar', [
    'uses' => 'NotaCreditoController@generarNotaCredito',
    'as'   => 'generar_nota'
]);