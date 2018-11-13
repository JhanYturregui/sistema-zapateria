@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/estilos/app/crud.css') }}" rel="stylesheet" >
@endsection

@section('contenido-principal')

<div class="crud-large">

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

    <div class="botones">
        <button class="btn btn-primary crear" onclick="crearUsuario()"><i class="fas fa-plus"></i>Crear</button>
        <input type="search" class="form-control buscar" placeholder="Buscar">
    </div>

    <div class="datos">
        <div class="card text-center">
            <div class="card-header">
                <h3>Lista de Usuarios</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>N° Doc</th>
                            <th>Usuario</th>
                            <th>Tipo</th>
                            <th colspan="2"><i class="fas fa-wrench"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usuarios as $usuario)
                            <tr>
                                <td>{{ $usuario->num_documento }}</td>
                                <td>{{ $usuario->username }}</td>
                                <td>{{ $usuario->tipo_usuario }}</td>
                                <td><i class="fas fa-pen" title="Editar" onclick="editarUsuario({{$usuario->id}})"></i></td>
                                <td><i class="fas fa-trash" title="Eliminar" onclick="eliminarUsuario({{$usuario->id}})"></i></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="paginacion">
        {{ $usuarios->links() }}
    </div>

</div>


<!-- MODAL CREAR ROL -->
<div class="modal fade" id="modalCrearUsuario" tabindex="-1" role="dialog" aria-labelledby="crearUsuario" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header cabecera-crear">
                <h5 class="modal-title" id="crearUsuario">Crear Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>   
                    <div class="form-group">
                        <input type="text" id="numeroDoc" class="form-control" placeholder="Número de documento">
                        <small id="campoNumeroDoc" class="help-block col-sm-offset-0 col-sm-12 validar-campo">Campo obligatorio</small>
                    </div>
                    <div class="form-group">
                        <input type="text" id="usuario" class="form-control" placeholder="Usuario">
                        <small id="campoUsuario" class="help-block col-sm-offset-0 col-sm-12 validar-campo">Campo obligatorio</small>
                    </div>
                    <div class="form-group">
                        <select class="form-control" id="tipoUsuario">
                            @foreach ($tiposUsuario as $tipo)
                                <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                            @endforeach
                        </select>
                    </div> 
                    <div class="form-group">
                        <select class="form-control" id="sucursal">
                            @foreach ($sucursales as $sucursal)
                                <option value="{{$sucursal->id}}">{{$sucursal->nombre}}</option>
                            @endforeach
                        </select>
                    </div> 
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnCrearUsuario">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ACTUALIZAR Usuario -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" role="dialog" aria-labelledby="editarUsuario" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <input type="hidden" id="idUsuarioA">

            <div class="modal-header cabecera-editar">
                <h5 class="modal-title" id="editarUsuario">Editar Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <input type="text" id="numeroDocA" class="form-control" placeholder="Número de documento" readonly>
                    </div>
                    <div class="form-group">
                        <input type="text" id="usuarioA" class="form-control" placeholder="Usuario">
                        <small id="campoUsuarioA" class="help-block col-sm-offset-0 col-sm-12 validar-campo">Campo obligatorio</small>
                    </div>
                    <div class="form-group">
                        <select class="form-control" id="tipoUsuarioA">
                            @foreach ($tiposUsuario as $tipo)
                                <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select class="form-control" id="sucursalA">
                            @foreach ($sucursales as $sucursal)
                                <option value="{{$sucursal->id}}">{{$sucursal->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnActualizarUsuario">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ELIMINAR USUARIO -->
<div class="modal fade" id="modalEliminarUsuario" tabindex="-1" role="dialog" aria-labelledby="eliminarUsuario" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

            <input type="hidden" id="idUsuarioE">

            <div class="modal-header cabecera-eliminar">
                <h5 class="modal-title" id="eliminarUsuario">Eliminar Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                ¿Está seguro que desea eliminar a este usuario?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnEliminarUsuario">Eliminar</button>
            </div>
        </div>
    </div>
</div>
    
@endsection

@section('js')
    <script src="{{ asset('js/cruds/usuarios.js') }}"></script>
@endsection