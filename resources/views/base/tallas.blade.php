@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/estilos/app/crud.css') }}" rel="stylesheet" >
@endsection

@section('contenido-principal')

<div class="crud-small">

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

    <div class="botones">
        <button class="btn btn-primary crear" onclick="crearTalla()"><i class="fas fa-plus"></i>Crear</button>
        <!--<input type="search" class="form-control buscar" placeholder="Buscar">-->
    </div>

    <div class="datos">
        <div class="card text-center">
            <div class="card-header">
                <h3>Lista de Tallas</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="cabecera-datos">
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th colspan="2"><i class="fas fa-wrench"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tallas as $talla)
                            <tr>
                                <td class="identificador">{{ $talla->id }}</td>
                                <td>{{ $talla->nombre }}</td>
                                <td><i class="fas fa-pen" title="Editar" onclick="editarTalla({{$talla->id}})"></i></td>
                                <td><i class="fas fa-trash" title="Eliminar" onclick="eliminarTalla({{$talla->id}})"></i></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="paginacion">
        {{ $tallas->links() }}
    </div>

</div>


<!-- MODAL CREAR TALLA -->
<div class="modal fade" id="modalCrearTalla" tabindex="-1" role="dialog" aria-labelledby="crearTalla" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header cabecera-crear">
                <h5 class="modal-title" id="crearTalla">Crear Talla</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="nombreTalla">Nombre</label>
                        <input type="text" id="nombreTalla" class="form-control" placeholder="Talla">
                        <small id="campoTalla" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>
                    </div>
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnCrearTalla">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ACTUALIZAR TALLA -->
<div class="modal fade" id="modalEditarTalla" tabindex="-1" role="dialog" aria-labelledby="editarTalla" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <input type="hidden" id="idTallaA">

            <div class="modal-header cabecera-editar">
                <h5 class="modal-title" id="editarTalla">Editar Talla</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="nombreTallaA">Nombre</label>
                        <input type="text" id="nombreTallaA" class="form-control" placeholder="Talla">
                        <small id="campoTallaA" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>
                    </div>
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnActualizarTalla">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ELIMINAR TALLA -->
<div class="modal fade" id="modalEliminarTalla" tabindex="-1" role="dialog" aria-labelledby="eliminarTalla" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

            <input type="hidden" id="idTallaE">

            <div class="modal-header cabecera-eliminar">
                <h5 class="modal-title" id="eliminarTalla">Eliminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                ¿Está seguro que desea eliminar esta talla?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnEliminarTalla">Eliminar</button>
            </div>
        </div>
    </div>
</div>
    
@endsection

@section('js')
    <script src="{{ asset('js/cruds/tallas.js') }}"></script>
@endsection