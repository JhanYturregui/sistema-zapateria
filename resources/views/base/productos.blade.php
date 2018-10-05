@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/estilos/app/crud.css') }}" rel="stylesheet" >
@endsection

@section('contenido-principal')

<div class="crud-small">

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

    <div class="botones">
        <button class="btn btn-primary crear" onclick="crearProducto()"><i class="fas fa-plus"></i>Crear</button>
        <input type="search" class="form-control buscar" placeholder="Buscar">
    </div>

    <div class="datos">
        <div class="card text-center">
            <div class="card-header">
                <h3>Lista de Productos</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th colspan="2"><i class="fas fa-wrench"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $producto)
                            <tr>
                                <td>{{ $producto->codigo }}</td>
                                <td>{{ $producto->descripcion }}</td>
                                <td><i class="fas fa-pen" title="Editar" onclick="editarProducto({{$producto->id}})"></i></td>
                                <td><i class="fas fa-trash" title="Eliminar" onclick="eliminarProducto({{$producto->id}})"></i></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="paginacion">
        {{ $productos->links() }}
    </div>

</div>


<!-- MODAL CREAR PRODUCTO -->
<div class="modal fade" id="modalCrearProducto" tabindex="-1" role="dialog" aria-labelledby="crearProducto" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header cabecera-crear">
                <h5 class="modal-title" id="crearProducto">Registrar Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="descripcion">Descripción</label>
                            <input type="text" id="descripcion" class="form-control" placeholder="Descripción">
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="marca">Marca</label>
                                </div>
                                <select class="custom-select" id="marca">
                                    @foreach ($marcas as $marca)
                                        <option value="{{$marca->id}}">{{$marca->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="modelo">Modelo</label>
                                </div>
                                <select class="custom-select" id="modelo">
                                    @foreach ($modelos as $modelo)
                                        <option value="{{$modelo->id}}">{{$modelo->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="color">Color</label>
                                </div>
                                <select class="custom-select" id="color">
                                    @foreach ($colores as $color)
                                        <option value="{{$color->id}}">{{$color->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="talla">Talla</label>
                                </div>
                                <select class="custom-select" id="talla">
                                    @foreach ($tallas as $talla)
                                        <option value="{{$talla->id}}">{{$talla->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="linea">Línea</label>
                                </div>
                                <select class="custom-select" id="linea">
                                    @foreach ($lineas as $linea)
                                        <option value="{{$linea->id}}">{{$linea->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <small id="error" class="help-block col-sm-offset-0 col-sm-12 validar-campo"></small>
                        </div>
                    </div>
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnCrearProducto">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ACTUALIZAR PRODUCTO -->
<div class="modal fade" id="modalEditarProducto" tabindex="-1" role="dialog" aria-labelledby="editarProducto" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <input type="hidden" id="idProductoA">

            <div class="modal-header cabecera-editar">
                <h5 class="modal-title" id="editarProducto">Editar Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="descripcionA">Descripción</label>
                            <input type="text" id="descripcionA" class="form-control" placeholder="Descripción">
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="marcaA">Marca</label>
                                </div>
                                <select class="custom-select" id="marcaA">
                                    @foreach ($marcas as $marca)
                                        <option value="{{$marca->id}}">{{$marca->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="modeloA">Modelo</label>
                                </div>
                                <select class="custom-select" id="modeloA">
                                    @foreach ($modelos as $modelo)
                                        <option value="{{$modelo->id}}">{{$modelo->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="colorA">Color</label>
                                </div>
                                <select class="custom-select" id="colorA">
                                    @foreach ($colores as $color)
                                        <option value="{{$color->id}}">{{$color->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="tallaA">Talla</label>
                                </div>
                                <select class="custom-select" id="tallaA">
                                    @foreach ($tallas as $talla)
                                        <option value="{{$talla->id}}">{{$talla->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="lineaA">Línea</label>
                                </div>
                                <select class="custom-select" id="lineaA">
                                    @foreach ($lineas as $linea)
                                        <option value="{{$linea->id}}">{{$linea->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <small id="errorA" class="help-block col-sm-offset-0 col-sm-12 validar-campo"></small>
                        </div>
                    </div>
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnActualizarProducto">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ELIMINAR PROVEEDOR -->
<div class="modal fade" id="modalEliminarProveedor" tabindex="-1" role="dialog" aria-labelledby="eliminarProveedor" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <input type="hidden" id="idProveedorE">

            <div class="modal-header cabecera-eliminar">
                <h5 class="modal-title" id="eliminarProveedor">Eliminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                ¿Está seguro que desea eliminar este proveedor?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnEliminarProveedor">Eliminar</button>
            </div>
        </div>
    </div>
</div>
    
@endsection

@section('js')
    <script src="{{ asset('js/cruds/productos.js') }}"></script>
@endsection