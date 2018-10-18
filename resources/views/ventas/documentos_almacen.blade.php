@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/estilos/app/crud.css') }}" rel="stylesheet" >
@endsection

@section('contenido-principal')

<div class="crud-large">

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

    <div class="botones">
        <button class="btn btn-primary crear" onclick="crearDocumentoAlmacen()"><i class="fas fa-plus"></i>Crear</button>
        <input type="search" class="form-control buscar" placeholder="Buscar">
    </div>

    <div class="datos">
        <div class="card text-center">
            <div class="card-header">
                <h3>Lista de Documentos de Almacén</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Numero</th>
                            <th>Tipo</th>
                            <th>Fecha</th>

                            @if (Auth::user()->tipo == 1)
                                <th ><i class="fas fa-wrench"></i></th>
                            @endif

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documentos as $documento)
                            <tr>
                                <td>{{ $documento->numero }}</td>
                                <td>{{ $documento->tipo }}</td>
                                <td>{{ $documento->created_at }}</td>

                                @if (Auth::user()->tipo == 1)
                                   <th ><i class="fas fa-trash" title="Anular" onclick="anularDocumentoAlmacen('{{$documento->numero}}')"></i></th>
                                @endif

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div id="anularDoc">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <p id="mensajeAnular"></p> 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>

    </div>

    <div class="paginacion">
        {{ $documentos->links() }}
    </div>

</div>


<!-- MODAL CREAR DOCUMENTO -->
<div class="modal fade" id="modalCrearDocumentoAlmacen" tabindex="-1" role="dialog" aria-labelledby="crearDocumentoAlmacen" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header cabecera-crear">
                <h5 class="modal-title" id="crearDocumentoAlmacen">Crear Documento Almacén</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body modal-doc">
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="numeroDoc">N° Documento</label>
                        <input type="text" value="{{$numeroDoc}}" id="numeroDoc" class="form-control" readonly>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="fechaDoc">Fecha Documento</label>
                        <input type="text" id="fechaDoc" class="form-control" readonly>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="tipoDoc">Tipo Documento</label>
                        <select class="form-control" id="tipoDoc">
                            <option value="ingreso">Ingreso</option>
                            <option value="salida">Salida</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="codigoProd">Código producto</label>
                        <input type="text" id="codigoProd" class="form-control" placeholder="Ingrese código" onkeyup="buscarProductos(this.value)">
                    </div>

                    <div class="col-md-2 form-group"></div>
                    <div class="col-md-8 form-group" id="productos">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Cantidad</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="productosSeleccionar">
                                
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-2 form-group"></div>

                    <div class="col-md-12 form-group" id="productosSeleccionados">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="titulo">
                                    <th colspan="8">PRODUCTOS SELECCIONADOS</th>
                                </tr>
                                <tr>
                                    <th>Código</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Color</th>
                                    <th>Talla</th>
                                    <th>Línea</th>
                                    <th>Cantidad</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody id="todosProductos">
                                
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 form-group">
                        <textarea id="comentario" cols="30" rows="4" class="form-control" placeholder="Comentario"></textarea>
                    </div>

                    <div class="col-md-12 form-group">
                        <small id="mensaje" class="help-block col-sm-offset-0 col-sm-12 validar-campo-lg">
                            </small>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnRegistrarDocumentoAlmacen">Registrar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ANULAR DOCUMENTO -->
<div class="modal fade" id="modalAnularDocumento" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <input type="hidden" id="numeroDocumento">

            <div class="modal-header cabecera-eliminar">
                <h5 class="modal-title" id="">Anular</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                ¿Está seguro que desea anular este documento de almacén?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnAnularDocumento">Anular</button>
            </div>
        </div>
    </div>
</div>
    
@endsection

@section('js')
    <script src="{{ asset('js/cruds/documentos_almacen.js') }}"></script>
@endsection