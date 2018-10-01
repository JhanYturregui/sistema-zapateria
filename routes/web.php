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