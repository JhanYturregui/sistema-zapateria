@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/estilos/app/crud.css') }}" rel="stylesheet" >
@endsection

@section('contenido-principal')

<div class="crud-small">

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

    <div class="botones">
        <button class="btn btn-primary crear" onclick="crearRol()"><i class="fas fa-plus"></i>Crear</button>
        <!--<input type="search" class="form-control buscar" placeholder="Buscar">-->
    </div>

    <div class="datos">
        <div class="card text-center">
            <div class="card-header">
                <h3>Lista de Roles</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="cabecera-productos">
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th colspan="2"><i class="fas fa-wrench"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $rol)
                            <tr>
                                <td class="identificador">{{ $rol->id }}</td>
                                <td>{{ $rol->nombre }}</td>
                                <td><i class="fas fa-pen" title="Editar" onclick="editarRol({{$rol->id}})"></i></td>
                                <td><i class="fas fa-trash" title="Eliminar" onclick="eliminarRol({{$rol->id}})"></i></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="paginacion">
        {{ $roles->links() }}
    </div>

</div>


<!-- MODAL CREAR ROL -->
<div class="modal fade" id="modalCrearRol" tabindex="-1" role="dialog" aria-labelledby="crearRol" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header cabecera-crear">
                <h5 class="modal-title" id="crearRol">Crear</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="nombreRol">Nombre</label>
                        <input type="text" id="nombreRol" class="form-control" placeholder="Nombre rol">
                        <small id="campoNombre" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>
                    </div>
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnCrearRol">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ACTUALIZAR ROL -->
<div class="modal fade" id="modalEditarRol" tabindex="-1" role="dialog" aria-labelledby="editarRol" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <input type="hidden" id="idRolA">

            <div class="modal-header cabecera-editar">
                <h5 class="modal-title" id="editarRol">Editar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="nombreRolA">Nombre</label>
                        <input type="text" id="nombreRolA" class="form-control" placeholder="Nombre rol">
                        <small id="campoNombreA" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>
                    </div>
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnActualizarRol">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ELIMINAR ROL -->
<div class="modal fade" id="modalEliminarRol" tabindex="-1" role="dialog" aria-labelledby="eliminarRol" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

            <input type="hidden" id="idRolE">

            <div class="modal-header cabecera-eliminar">
                <h5 class="modal-title" id="eliminarRol">Eliminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                ¿Está seguro que desea eliminar este tipo de usuario?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnEliminarRol">Eliminar</button>
            </div>
        </div>
    </div>
</div>
    
@endsection

@section('js')
    <script src="{{ asset('js/cruds/roles.js') }}"></script>
@endsection