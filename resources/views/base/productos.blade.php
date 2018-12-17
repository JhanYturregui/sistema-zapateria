@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/estilos/app/crud.css') }}" rel="stylesheet" >
    <link href="{{ asset('css/select2/select2.css') }}" rel="stylesheet" >
    <link href="{{ asset('css/select2/select2.bootstrap.css') }}" rel="stylesheet" >
@endsection

@section('contenido-principal')

<div class="crud-small">

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

    <div class="botones">
        <button class="btn btn-primary crear" onclick="crearProducto()"><i class="fas fa-plus"></i>Crear</button>
        <input type="search" class="form-control buscar" placeholder="Buscar" onkeyup="buscarPorCodigo(this.value)">
    </div>

    <div class="datos">
        <div class="card text-center">
            <div class="card-header">
                <h3>Lista de Productos</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="cabecera-productos">
                        <tr>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th colspan="2"><i class="fas fa-wrench"></i></th>
                        </tr>
                    </thead>
                    <tbody id="datosProductos">
                        @foreach ($productos as $producto)
                            <tr>
                                <td class="identificador">{{ $producto->codigo }}</td>
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
<div class="modal fade" id="modalCrearProducto" role="dialog" aria-labelledby="crearProducto" aria-hidden="true">
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
                        <div class="form-group col-md-4">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="modelo">Modelo</label>
                                </div>
                                <input type="text" id="modelo" class="form-control" placeholder="Modelo Producto">
                                <small id="campoCodigo" class="help-block col-sm-offset-0 col-sm-12 validar-campo"></small>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="precioCompra">P. Compra</label>
                                </div>
                                <input type="text" class="form-control" id="precioCompra" placeholder="Precio compra" aria-label="Precio compra" aria-describedby="precioCompra">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="precioVenta">P. Venta</label>
                                </div>
                                <input type="text" class="form-control" id="precioVenta" placeholder="Precio venta" aria-label="Precio venta" aria-describedby="precioVenta">
                            </div>
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
                                    <label class="input-group-text" for="linea">Línea</label>
                                </div>
                                <select class="custom-select" id="linea">
                                    @foreach ($lineas as $linea)
                                        <option value="{{$linea->id}}">{{$linea->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="linea2">Línea 2</label>
                                </div>
                                <select class="custom-select" id="linea2">
                                    @foreach ($lineas2 as $linea)
                                        <option value="{{$linea->id}}">{{$linea->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="linea3">Línea 3</label>
                                </div>
                                <select class="custom-select" id="linea3">
                                    @foreach ($lineas3 as $linea)
                                        <option value="{{$linea->id}}">{{$linea->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="taco">Taco</label>
                                </div>
                                <select class="custom-select" id="taco">
                                    @foreach ($tacos as $taco)
                                        <option value="{{$taco->id}}">{{$taco->numero}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="descripcion">Descripción</label>
                                </div>
                                <input type="text" id="descripcion" class="form-control" placeholder="Descripcion">
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
                        <div class="form-group col-md-4">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="modeloA">Modelo</label>
                                </div>
                                <input type="text" id="modeloA" class="form-control" placeholder="Modelo Producto">
                                <small id="campoCodigoA" class="help-block col-sm-offset-0 col-sm-12 validar-campo"></small>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="precioCompraA">P. Compra</label>
                                </div>
                                <input type="text" class="form-control" id="precioCompraA" placeholder="Precio compra" aria-label="Precio compra" aria-describedby="precioCompraA">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="precioVentaA">P. Venta</label>
                                </div>
                                <input type="text" class="form-control" id="precioVentaA" placeholder="Precio venta" aria-label="Precio venta" aria-describedby="precioVentaA">
                            </div>
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
                                    <label class="input-group-text" for="lineaA">Línea</label>
                                </div>
                                <select class="custom-select" id="lineaA">
                                    @foreach ($lineas as $linea)
                                        <option value="{{$linea->id}}">{{$linea->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="linea2A">Línea 2</label>
                                </div>
                                <select class="custom-select" id="linea2A">
                                    @foreach ($lineas2 as $linea)
                                        <option value="{{$linea->id}}">{{$linea->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="linea3A">Línea 3</label>
                                </div>
                                <select class="custom-select" id="linea3A">
                                    @foreach ($lineas3 as $linea)
                                        <option value="{{$linea->id}}">{{$linea->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="tacoA">Taco</label>
                                </div>
                                <select class="custom-select" id="tacoA">
                                    @foreach ($tacos as $taco)
                                        <option value="{{$taco->id}}">{{$taco->numero}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="descripcionA">Descripción</label>
                                </div>
                                <input type="text" id="descripcionA" class="form-control" placeholder="Descripcion">
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
                <button type="button" class="btn btn-success" id="btnActualizarProducto">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ELIMINAR PRODUCTO -->
<div class="modal fade" id="modalEliminarProducto" tabindex="-1" role="dialog" aria-labelledby="eliminarProducto" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

            <input type="hidden" id="idProductoE">

            <div class="modal-header cabecera-eliminar">
                <h5 class="modal-title" id="eliminarProducto">Eliminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                ¿Está seguro que desea eliminar este producto?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnEliminarProducto">Eliminar</button>
            </div>
        </div>
    </div>
</div>
    
@endsection

@section('js')
    <script src="{{ asset('js/cruds/productos.js') }}"></script>
    <script src="{{ asset('js/select2/select2.js') }}"></script>
    <script>
        $('#modalCrearProducto select').css('width', '100%')
        $('#marca').select2({})
        $('#color').select2({})
        $('#talla').select2({})
        $('#linea').select2({})
        $('#linea2').select2({})
        $('#linea3').select2({})

        $('#modalEditarProducto select').css('width', '100%')
        $('#marcaA').select2({})
        $('#colorA').select2({})
        $('#tallaA').select2({})
        $('#lineaA').select2({})
        $('#linea2A').select2({})
        $('#linea3A').select2({})
    </script>
@endsection