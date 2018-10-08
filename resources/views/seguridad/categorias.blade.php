@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/estilos/app/crud.css') }}" rel="stylesheet" >
@endsection

@section('contenido-principal')

<div class="crud-small">

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

    <div class="botones">
        <button class="btn btn-primary crear" onclick="crearCategoria()"><i class="fas fa-plus"></i>Crear</button>
        <input type="search" class="form-control buscar" placeholder="Buscar">
    </div>

    <div class="datos">
        <div class="card text-center">
            <div class="card-header">
                <h3>Lista de Categorías</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Orden</th>
                            <th colspan="2"><i class="fas fa-wrench"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categorias as $categoria)
                            <tr>
                                <td>{{ $categoria->id }}</td>
                                <td>{{ $categoria->nombre }}</td>
                                <td>{{ $categoria->orden }}</td>
                                <td><i class="fas fa-pen" title="Editar" onclick="editarCategoria({{$categoria->id}})"></i></td>
                                <td><i class="fas fa-trash" title="Eliminar" onclick="eliminarCategoria({{$categoria->id}})"></i></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="paginacion">
        {{ $categorias->links() }}
    </div>

</div>


<!-- MODAL CREAR CATEGORÍA -->
<div class="modal fade" id="modalCrearCategoria" tabindex="-1" role="dialog" aria-labelledby="crearCategoria" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header cabecera-crear">
                <h5 class="modal-title" id="crearCategoria">Crear Categoría</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="cat">Nombre</label>
                        <input type="text" id="nombreCategoria" class="form-control" placeholder="Nombre categoría">
                        <small id="campoNombre" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Orden</label>
                        <select class="form-control" id="ordenCategoria">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                    <!--<div class="form-group">
                        <label for="cat">Ícono</label>
                        <input type="text" id="iconoCategoria" class="form-control" placeholder="Clase ícono">
                    </div>-->
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnCrearCategoria">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ACTUALIZAR CATEGORÍA -->
<div class="modal fade" id="modalEditarCategoria" tabindex="-1" role="dialog" aria-labelledby="editarCategoria" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <input type="hidden" id="idCategoriaA">

            <div class="modal-header cabecera-editar">
                <h5 class="modal-title" id="editarCategoria">Editar Categoría</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="nombreCategoriaA">Nombre</label>
                        <input type="text" id="nombreCategoriaA" class="form-control" placeholder="Nombre categoría">
                        <small id="campoNombreA" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>
                    </div>
                    <div class="form-group">
                        <label for="ordenCategoriaA">Orden</label>
                        <select class="form-control" id="ordenCategoriaA">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                    <!--<div class="form-group">
                        <label for="cat">Ícono</label>
                        <input type="text" id="iconoCategoriaA" class="form-control" placeholder="Clase ícono">
                    </div>-->
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnActualizarCategoria">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ELIMINAR CATEGORÍA -->
<div class="modal fade" id="modalEliminarCategoria" tabindex="-1" role="dialog" aria-labelledby="eliminarCategoria" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

            <input type="hidden" id="idCategoriaE">

            <div class="modal-header cabecera-eliminar">
                <h5 class="modal-title" id="eliminarCategoria">Eliminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                ¿Está seguro que desea eliminar esta categoría?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnEliminarCategoria">Eliminar</button>
            </div>
        </div>
    </div>
</div>
    
@endsection

@section('js')
    <script src="{{ asset('js/cruds/categorias.js') }}"></script>
@endsection