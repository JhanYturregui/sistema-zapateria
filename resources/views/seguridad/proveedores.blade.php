@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/estilos/app/crud.css') }}" rel="stylesheet" >
@endsection

@section('contenido-principal')

<div class="crud-large">

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

    <div class="botones">
        <button class="btn btn-primary crear" onclick="crearProveedor()"><i class="fas fa-plus"></i>Crear</button>
        <input type="search" class="form-control buscar" placeholder="Buscar">
    </div>

    <div class="datos">
        <div class="card text-center">
            <div class="card-header">
                <h3>Lista de Proveedores</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>RUC</th>
                            <th>Proveedor</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th colspan="2"><i class="fas fa-wrench"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($proveedores as $proveedor)
                            <tr>
                                <td>{{ $proveedor->ruc }}</td>
                                <td>{{ $proveedor->nombre }}</td>
                                <td>{{ $proveedor->correo }}</td>
                                <td>{{ $proveedor->telefono1 }}</td>
                                <td><i class="fas fa-pen" title="Editar" onclick="editarProveedor({{$proveedor->id}})"></i></td>
                                <td><i class="fas fa-trash" title="Eliminar" onclick="eliminarProveedor({{$proveedor->id}})"></i></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="paginacion">
        {{ $proveedores->links() }}
    </div>

</div>


<!-- MODAL CREAR PROVEEDOR -->
<div class="modal fade" id="modalCrearProveedor" tabindex="-1" role="dialog" aria-labelledby="crearProveedor" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header cabecera-crear">
                <h5 class="modal-title" id="crearProveedor">Registrar Proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="ruc">RUC</label>
                        <input type="text" id="ruc" class="form-control" placeholder="RUC">
                        <small id="campoRUC" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Proveedor</label>
                        <input type="text" id="nombre" class="form-control" placeholder="Proveedor">
                        <small id="campoNombre" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" id="direccion" class="form-control" placeholder="Dirección">
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo</label>
                        <input type="text" id="correo" class="form-control" placeholder="Correo">
                    </div>
                    <div class="form-group">
                        <label for="telefono1">Teléfono 1</label>
                        <input type="text" id="telefono1" class="form-control" placeholder="Teléfono">
                    </div>
                    <div class="form-group">
                        <label for="telefono2">Teléfono 2</label>
                        <input type="text" id="telefono2" class="form-control" placeholder="Teléfono">
                    </div>
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnCrearProveedor">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ACTUALIZAR PROVEEDOR -->
<div class="modal fade" id="modalEditarProveedor" tabindex="-1" role="dialog" aria-labelledby="editarProveedor" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <input type="hidden" id="idProveedorA">

            <div class="modal-header cabecera-editar">
                <h5 class="modal-title" id="editarPrveedor">Editar Proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="rucA">RUC</label>
                        <input type="text" id="rucA" class="form-control" placeholder="RUC" readonly>
                        <small id="campoRUCA" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>
                    </div>
                    <div class="form-group">
                        <label for="nombreA">Proveedor</label>
                        <input type="text" id="nombreA" class="form-control" placeholder="Proveedor">
                        <small id="campoNombreA" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>
                    </div>
                    <div class="form-group">
                        <label for="direccionA">Dirección</label>
                        <input type="text" id="direccionA" class="form-control" placeholder="Dirección">
                    </div>
                    <div class="form-group">
                        <label for="correoA">Correo</label>
                        <input type="text" id="correoA" class="form-control" placeholder="Correo">
                    </div>
                    <div class="form-group">
                        <label for="telefono1A">Teléfono 1</label>
                        <input type="text" id="telefono1A" class="form-control" placeholder="Teléfono">
                    </div>
                    <div class="form-group">
                        <label for="telefono2A">Teléfono 2</label>
                        <input type="text" id="telefono2A" class="form-control" placeholder="Teléfono">
                    </div>
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnActualizarProveedor">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ELIMINAR PROVEEDOR -->
<div class="modal fade" id="modalEliminarProveedor" tabindex="-1" role="dialog" aria-labelledby="eliminarProveedor" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
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
    <script src="{{ asset('js/cruds/proveedores.js') }}"></script>
@endsection