@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/estilos/app/crud.css') }}" rel="stylesheet" >
@endsection

@section('contenido-principal')

<div class="crud-small">

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

    <div class="botones">
        <button class="btn btn-primary crear" onclick="crearConcepto()"><i class="fas fa-plus"></i>Crear</button>
        <!--<input type="search" class="form-control buscar" placeholder="Buscar">-->
    </div>

    <div class="datos">
        <div class="card text-center">
            <div class="card-header">
                <h3>Lista de Conceptos</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th colspan="2"><i class="fas fa-wrench"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($conceptos as $concepto)
                            <tr>
                                <td>{{ $concepto->id }}</td>
                                <td>{{ $concepto->nombre }}</td>
                                <td>{{ $concepto->tipo }}</td>
                                <td><i class="fas fa-pen" title="Editar" onclick="editarConcepto({{$concepto->id}})"></i></td>
                                <td><i class="fas fa-trash" title="Eliminar" onclick="eliminarConcepto({{$concepto->id}})"></i></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="paginacion">
        {{ $conceptos->links() }}
    </div>

</div>


<!-- MODAL CREAR CONCEPTO -->
<div class="modal fade" id="modalCrearConcepto" tabindex="-1" role="dialog" aria-labelledby="crearConcepto" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header cabecera-crear">
                <h5 class="modal-title" id="crearConcepto">Crear Concepto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="nombreConcepto">Nombre</label>
                        <input type="text" id="nombreConcepto" class="form-control" placeholder="Nombre concepto">
                        <small id="campoNombre" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>
                    </div>
                    <div class="form-group">
                        <select class="form-control" id="tipoConcepto">
                            <option value="ingreso">Ingreso</option>
                            <option value="egreso">Egreso</option>
                        </select>
                    </div>
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnCrearConcepto">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ACTUALIZAR CONCEPTO -->
<div class="modal fade" id="modalEditarConcepto" tabindex="-1" role="dialog" aria-labelledby="editarConcepto" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <input type="hidden" id="idConceptoA">

            <div class="modal-header cabecera-editar">
                <h5 class="modal-title" id="editarConcepto">Editar Concepto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="nombreConceptoA">Nombre</label>
                        <input type="text" id="nombreConceptoA" class="form-control" placeholder="Nombre concepto">
                        <small id="campoNombreA" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                            Campo obligatorio</small>
                    </div>
                    <div class="form-group">
                        <select class="form-control" id="tipoConceptoA">
                            <option value="ingreso">Ingreso</option>
                            <option value="egreso">Egreso</option>
                        </select>
                    </div>
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnActualizarConcepto">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ELIMINAR CONCEPTO -->
<div class="modal fade" id="modalEliminarConcepto" tabindex="-1" role="dialog" aria-labelledby="eliminarConcepto" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

            <input type="hidden" id="idConceptoE">

            <div class="modal-header cabecera-eliminar">
                <h5 class="modal-title" id="eliminarConcepto">Eliminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                ¿Está seguro que desea eliminar este concepto?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnEliminarConcepto">Eliminar</button>
            </div>
        </div>
    </div>
</div>
    
@endsection

@section('js')
    <script src="{{ asset('js/cruds/conceptos.js') }}"></script>
@endsection