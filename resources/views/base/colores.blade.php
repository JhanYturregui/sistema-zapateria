@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/estilos/app/crud.css') }}" rel="stylesheet" >
@endsection

@section('contenido-principal')

<div class="crud-small">

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

    <div class="botones">
        <button class="btn btn-primary crear" onclick="crearColor()"><i class="fas fa-plus"></i>Crear</button>
        <input type="search" class="form-control buscar" placeholder="Buscar">
    </div>

    <div class="datos">
        <div class="card text-center">
            <div class="card-header">
                <h3>Lista de Colores</h3>
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
                        @foreach ($colores as $color)
                            <tr>
                                <td>{{ $color->id }}</td>
                                <td>{{ $color->nombre }}</td>
                                <td><i class="fas fa-pen" title="Editar" onclick="editarColor({{$color->id}})"></i></td>
                                <td><i class="fas fa-trash" title="Eliminar" onclick="eliminarColor({{$color->id}})"></i></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="paginacion">
        {{ $colores->links() }}
    </div>

</div>


<!-- MODAL CREAR COLOR -->
<div class="modal fade" id="modalCrearColor" tabindex="-1" role="dialog" aria-labelledby="crearColor" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header cabecera-crear">
                <h5 class="modal-title" id="crearColor">Crear Color</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="nombreColor">Nombre</label>
                        <input type="text" id="nombreColor" class="form-control" placeholder="Nombre color">
                        <small id="campoColor" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>
                    </div>
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnCrearColor">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ACTUALIZAR COLOR -->
<div class="modal fade" id="modalEditarColor" tabindex="-1" role="dialog" aria-labelledby="editarColor" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <input type="hidden" id="idColorA">

            <div class="modal-header cabecera-editar">
                <h5 class="modal-title" id="editarColor">Editar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="nombreColorA">Nombre</label>
                        <input type="text" id="nombreColorA" class="form-control" placeholder="Nombre color">
                        <small id="campoColorA" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>
                    </div>
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnActualizarColor">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ELIMINAR COLOR -->
<div class="modal fade" id="modalEliminarColor" tabindex="-1" role="dialog" aria-labelledby="eliminarColor" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

            <input type="hidden" id="idColorE">

            <div class="modal-header cabecera-eliminar">
                <h5 class="modal-title" id="eliminarColor">Eliminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                ¿Está seguro que desea eliminar este color?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnEliminarColor">Eliminar</button>
            </div>
        </div>
    </div>
</div>
    
@endsection

@section('js')
    <script src="{{ asset('js/cruds/colores.js') }}"></script>
@endsection