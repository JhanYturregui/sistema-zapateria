@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/estilos/app/crud.css') }}" rel="stylesheet" >
@endsection

@section('contenido-principal')

<div class="crud-small">

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

    <div class="botones">
        <button class="btn btn-primary crear" onclick="crearOpcion()"><i class="fas fa-plus"></i>Crear</button>
        <input type="search" class="form-control buscar" placeholder="Buscar">
    </div>

    <div class="datos">
        <div class="card text-center">
            <div class="card-header">
                <h3>Lista de Opciones</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Orden</th>
                            <th>Ícono</th>
                            <th colspan="2"><i class="fas fa-wrench"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($opciones as $opcion)
                            <tr>
                                <td>{{ $opcion->id }}</td>
                                <td>{{ $opcion->nombre }}</td>
                                <td>{{ $opcion->categoria }}</td>
                                <td>{{ $opcion->orden }}</td>
                                <td><i class="{{ $opcion->icono }}"></i></td>
                                <td><i class="fas fa-pen" title="Editar" onclick="editarOpcion({{$opcion->id}})"></i></td>
                                <td><i class="fas fa-trash" title="Eliminar" onclick="eliminarOpcion({{$opcion->id}})"></i></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="paginacion">
        {{ $opciones->links() }}
    </div>

</div>


<!-- MODAL CREAR OPCIÓN -->
<div class="modal fade" id="modalCrearOpcion" tabindex="-1" role="dialog" aria-labelledby="crearOpcion" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header cabecera-crear">
                <h5 class="modal-title" id="crearOpcion">Crear</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="nombreOpcion">Nombre</label>
                        <input type="text" id="nombreOpcion" class="form-control" placeholder="Nombre opción de categoría">
                        <small id="campoNombre" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Categoría</label>
                        <select class="form-control" id="categoria">
                            @foreach ($categorias as $categoria)
                                <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>        
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ordenOpcion">Orden</label>
                        <select class="form-control" id="ordenOpcion">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="iconoOpcion">Ícono</label>
                        <input type="text" id="iconoOpcion" class="form-control" placeholder="Clase ícono">
                    </div>
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnCrearOpcion">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ACTUALIZAR OPCIÓN -->
<div class="modal fade" id="modalEditarOpcion" tabindex="-1" role="dialog" aria-labelledby="editarOpcion" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <input type="hidden" id="idOpcionA">

            <div class="modal-header cabecera-editar">
                <h5 class="modal-title" id="editarOpcion">Editar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="nombreOpcionA">Nombre</label>
                        <input type="text" id="nombreOpcionA" class="form-control" placeholder="Nombre opción de categoría">
                        <small id="campoNombreA" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>
                    </div>
                    <div class="form-group">
                        <label for="categoriaA">Categoría</label>
                        <select class="form-control" id="categoriaA">
                            @foreach ($categorias as $categoria)
                                <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>     
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ordenOpcionA">Orden</label>
                        <select class="form-control" id="ordenOpcionA">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="iconoOpcionA">Ícono</label>
                        <input type="text" id="iconoOpcionA" class="form-control" placeholder="Clase ícono">
                    </div>
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnActualizarOpcion">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ELIMINAR CATEGORÍA -->
<div class="modal fade" id="modalEliminarOpcion" tabindex="-1" role="dialog" aria-labelledby="eliminarOpcion" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <input type="hidden" id="idOpcionE">

            <div class="modal-header cabecera-eliminar">
                <h5 class="modal-title" id="eliminarOpcion">Eliminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                ¿Está seguro que desea eliminar esta opción?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnEliminarOpcion">Eliminar</button>
            </div>
        </div>
    </div>
</div>
    
@endsection

@section('js')
    <script src="{{ asset('js/cruds/opciones.js') }}"></script>
@endsection