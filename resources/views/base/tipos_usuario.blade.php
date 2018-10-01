@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/estilos/app/crud.css') }}" rel="stylesheet" >
@endsection

@section('contenido-principal')

<div class="crud-small">

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

    <div class="botones">
        <button class="btn btn-primary crear" onclick="crearTipoUsuario()"><i class="fas fa-plus"></i>Crear</button>
        <input type="search" class="form-control buscar" placeholder="Buscar">
    </div>

    <div class="datos">
        <div class="card text-center">
            <div class="card-header">
                <h3>Listado Tipos de Usuario</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th colspan="3"><i class="fas fa-wrench"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tiposUsuario as $tipoUsuario)
                            <tr>
                                <td>{{ $tipoUsuario->id }}</td>
                                <td>{{ $tipoUsuario->nombre }}</td>
                                <td><i class="fas fa-key" title="Accesos" onclick="accesos({{$tipoUsuario->id}})"></i></td>
                                <td><i class="fas fa-pen" title="Editar" onclick="editarTipoUsuario({{$tipoUsuario->id}})"></i></td>
                                <td><i class="fas fa-trash" title="Eliminar" onclick="eliminarTipoUsuario({{$tipoUsuario->id}})"></i></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="paginacion">
        {{ $tiposUsuario->links() }}
    </div>

</div>


<!-- MODAL CREAR TIPO USUARIO -->
<div class="modal fade" id="modalCrearTipoUsuario" tabindex="-1" role="dialog" aria-labelledby="crearTipoUsuario" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header cabecera-crear">
                <h5 class="modal-title" id="crearTipoUsuario">Crear</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="nombreTipoUsuario">Nombre</label>
                        <input type="text" id="nombreTipoUsuario" class="form-control" placeholder="Nombre tipo de usuario">
                        <small id="campoNombre" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>
                    </div>
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnCrearTipoUsuario">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ACTUALIZAR TIPO USUARIO -->
<div class="modal fade" id="modalEditarTipoUsuario" tabindex="-1" role="dialog" aria-labelledby="editarTipoUsuario" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <input type="hidden" id="idTipoUsuarioA">

            <div class="modal-header cabecera-editar">
                <h5 class="modal-title" id="editarTipoUsuario">Editar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="nombreTipoUsuarioA">Nombre</label>
                        <input type="text" id="nombreTipoUsuarioA" class="form-control" placeholder="Nombre tipo de usuario">
                        <small id="campoNombreA" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>
                    </div>
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnActualizarTipoUsuario">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ELIMINAR TIPO USUARIO -->
<div class="modal fade" id="modalEliminarTipoUsuario" tabindex="-1" role="dialog" aria-labelledby="eliminarTipoUsuario" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <input type="hidden" id="idTipoUsuarioE">

            <div class="modal-header cabecera-eliminar">
                <h5 class="modal-title" id="eliminarTipoUsuario">Eliminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                ¿Está seguro que desea eliminar este tipo de usuario?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnEliminarTipoUsuario">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ACCESOS -->
<div class="modal fade" id="modalAccesos" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <input type="hidden" id="tipoUsuario" >

            <div class="modal-header cabecera-accesos">
                <h5 class="modal-title" id="">Accesos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="cuerpoAccesos">
                
                @foreach ($categorias as $categoria)
                    <p data-toggle="collapse" data-target="#cat-{{$categoria->id}}" aria-expanded="false" aria-controls="cat-{{$categoria->id}}" class="categorias">
                        <i class="fas fa-caret-down"></i>{{$categoria->nombre}}
                    </p>
                    <div class="collapse" id="cat-{{$categoria->id}}">

                        <!-- -->
                        <div class="card card-body">
                            
                            @foreach ($opciones as $opcion)
                                @if ($categoria->id == $opcion->categoria)
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="check-{{$opcion->id}}" value="{{$opcion->id}}">
                                    <label class="custom-control-label" for="check-{{$opcion->id}}">{{$opcion->nombre}}</label>
                                </div>
                                @endif
                            @endforeach
                            
                        </div>
                        <!-- -->

                    </div>     
                @endforeach

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning" id="btnAccesos">Guardar</button>
            </div>
        </div>
    </div>
</div>
    
@endsection

@section('js')
    <script src="{{ asset('js/cruds/tipos_usuario.js') }}"></script>
@endsection