@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/estilos/app/crud.css') }}" rel="stylesheet" >
@endsection

@section('contenido-principal')

<div class="crud-small">

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

    <div class="botones">
        <button class="btn btn-primary crear" onclick="crearLinea()"><i class="fas fa-plus"></i>Crear</button>
        <input type="search" class="form-control buscar" placeholder="Buscar">
    </div>

    <div class="datos">
        <div class="card text-center">
            <div class="card-header">
                <h3>Lista de Líneas</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th colspan="2"><i class="fas fa-wrench"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lineas as $linea)
                            <tr>
                                <td>{{ $linea->id }}</td>
                                <td>{{ $linea->nombre }}</td>
                                <td><i class="fas fa-pen" title="Editar" onclick="editarLinea({{$linea->id}})"></i></td>
                                <td><i class="fas fa-trash" title="Eliminar" onclick="eliminarLinea({{$linea->id}})"></i></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="paginacion">
        {{ $lineas->links() }}
    </div>

</div>


<!-- MODAL CREAR LINEA -->
<div class="modal fade" id="modalCrearLinea" tabindex="-1" role="dialog" aria-labelledby="crearLinea" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header cabecera-crear">
                <h5 class="modal-title" id="crearLinea">Crear Línea</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="nombreLinea">Nombre</label>
                        <input type="text" id="nombreLinea" class="form-control" placeholder="Línea">
                        <small id="campoLinea" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>
                    </div>
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnCrearLinea">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ACTUALIZAR LINEA -->
<div class="modal fade" id="modalEditarLinea" tabindex="-1" role="dialog" aria-labelledby="editarLinea" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <input type="hidden" id="idLineaA">

            <div class="modal-header cabecera-editar">
                <h5 class="modal-title" id="editarLinea">Editar Línea</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="nombreLineaA">Nombre</label>
                        <input type="text" id="nombreLineaA" class="form-control" placeholder="Línea">
                        <small id="campoLineaA" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>
                    </div>
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnActualizarLinea">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ELIMINAR LINEA -->
<div class="modal fade" id="modalEliminarLinea" tabindex="-1" role="dialog" aria-labelledby="eliminarLinea" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <input type="hidden" id="idLineaE">

            <div class="modal-header cabecera-eliminar">
                <h5 class="modal-title" id="eliminarLinea">Eliminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                ¿Está seguro que desea eliminar esta línea?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnEliminarLinea">Eliminar</button>
            </div>
        </div>
    </div>
</div>
    
@endsection

@section('js')
    <script src="{{ asset('js/cruds/lineas.js') }}"></script>
@endsection