@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/estilos/app/crud.css') }}" rel="stylesheet" >
@endsection

@section('contenido-principal')

<div class="crud-small">

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

    <div class="botones">
        <button class="btn btn-primary crear" onclick="crearSucursal()"><i class="fas fa-plus"></i>Crear</button>
        <input type="search" class="form-control buscar" placeholder="Buscar">
    </div>

    <div class="datos">
        <div class="card text-center">
            <div class="card-header">
                <h3>Lista de Sucursales</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th colspan="2"><i class="fas fa-wrench"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sucursales as $sucursal)
                            <tr>
                                <td>{{ $sucursal->id }}</td>
                                <td>{{ $sucursal->nombre }}</td>
                                <td>{{ $sucursal->direccion }}</td>
                                <td><i class="fas fa-pen" title="Editar" onclick="editarSucursal({{$sucursal->id}})"></i></td>
                                <td><i class="fas fa-trash" title="Eliminar" onclick="eliminarSucursal({{$sucursal->id}})"></i></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="paginacion">
        {{ $sucursales->links() }}
    </div>

</div>


<!-- MODAL CREAR SUCURSAL -->
<div class="modal fade" id="modalCrearSucursal" tabindex="-1" role="dialog" aria-labelledby="crearSucursal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header cabecera-crear">
                <h5 class="modal-title" id="crearSucursal">Crear Sucursal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="nombreSucursal">Nombre</label>
                        <input type="text" id="nombreSucursal" class="form-control" placeholder="Nombre sucursal">
                        <small id="campoNombre" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>
                    </div>
                    <div class="form-group">
                        <label for="direccionSucursal">Dirección</label>
                        <input type="text" id="direccionSucursal" class="form-control" placeholder="Dirección sucursal">
                        <!--<small id="campoDireccion" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>-->
                    </div>
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnCrearSucursal">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ACTUALIZAR SUCURSAL -->
<div class="modal fade" id="modalEditarSucursal" tabindex="-1" role="dialog" aria-labelledby="editarSucursal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <input type="hidden" id="idSucursalA">

            <div class="modal-header cabecera-editar">
                <h5 class="modal-title" id="editarSucursal">Editar Sucursal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="nombreSucursalA">Nombre</label>
                        <input type="text" id="nombreSucursalA" class="form-control" placeholder="Nombre sucursal">
                        <small id="campoNombreA" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>
                    </div>
                    <div class="form-group">
                        <label for="direccionSucursalA">Dirección</label>
                        <input type="text" id="direccionSucursalA" class="form-control" placeholder="Dirección sucursal">
                        <!--<small id="campoDireccion" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>-->
                    </div>
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnActualizarSucursal">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ELIMINAR SUCURSAL -->
<div class="modal fade" id="modalEliminarSucursal" tabindex="-1" role="dialog" aria-labelledby="eliminarSucursal" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

            <input type="hidden" id="idSucursalE">

            <div class="modal-header cabecera-eliminar">
                <h5 class="modal-title" id="eliminarSucursal">Eliminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                ¿Está seguro que desea eliminar esta sucursal?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnEliminarSucursal">Eliminar</button>
            </div>
        </div>
    </div>
</div>
    
@endsection

@section('js')
    <script src="{{ asset('js/cruds/sucursales.js') }}"></script>
@endsection