@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/estilos/app/crud.css') }}" rel="stylesheet" >
@endsection

@section('contenido-principal')

<div class="crud-large">

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

    <div class="botones">
        <button class="btn btn-primary crear" onclick="crearDocumentoAlmacen()"><i class="fas fa-plus"></i>Crear</button>
        <!--<input type="search" class="form-control buscar" placeholder="Buscar">-->
    </div>

    <div class="datos">
        <div class="card text-center">
            <div class="card-header">
                <h3>Lista de Documentos de Almacén</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="cabecera-datos">
                        <tr>
                            <th>Numero</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            @if (Auth::user()->tipo == 1 || Auth::user()->tipo == 2 || Auth::user()->tipo == 3)
                                <th>Acción</th>
                            @endif

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documentos as $documento)
                            @if ($documento->estado == 1)
                            <tr class="pendiente">
                            @else
                            <tr>
                            @endif
                                <td class="identificador">{{ $documento->numero }}</td>
                                @if ($documento->destino == Auth::user()->sucursal)
                                    <td>Ingreso</td>
                                @else
                                    <td>Salida</td>    
                                @endif
                                <td>{{ $documento->created_at }}</td>
                                @if ($documento->estado == 1)
                                    <td style="color: orange;"><i class="far fa-clock" title="Pendiente..."></i></td>
                                @elseif($documento->estado == 2)
                                    <td style="color:#4caf50"><i class="fas fa-check" title="Aceptado"></i></td>
                                @else
                                    <td style="color: red"><i class="fas fa-ban" title="Anulado"></i></td>    
                                @endif
                                @if (Auth::user()->tipo == 1 || Auth::user()->tipo == 2 || Auth::user()->tipo == 3)
                                    @if ($documento->destino == Auth::user()->sucursal && $documento->estado == 1)
                                        <td><i class="fas fa-check-circle" onclick="aceptarDocumentoAlmacen('{{$documento->numero}}')" title="Aceptar" style="cursor:pointer; color:#0277bd"></i></td>
                                    @else
                                        <td></td>    
                                    @endif
                                    
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
                    <!--<div class="col-md-3 form-group">
                        <label for="tipoDoc">Tipo Documento</label>
                        <select class="form-control" id="tipoDoc" onchange="tipoDocAlm()">
                            <option value="ingreso">Ingreso</option>
                            <option value="salida">Salida</option>
                        </select>
                    </div>-->
                    <div class="col-md-3 form-group" id="divSucursales">
                        <label id="tituloSuc" for="sucursales">Destino</label>
                        <select class="form-control" id="sucursales">
                            @foreach ($sucursales as $sucursal)
                                <option value="{{$sucursal->id}}">{{$sucursal->nombre}}</option>    
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="codigoProd">Código producto</label>
                        <input type="text" id="codigoProd" class="form-control" placeholder="Ingrese código" onkeyup="buscarProductos(this.value)">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="comentario">Comentario</label>
                        <textarea id="comentario" cols="30" rows="3" class="form-control" placeholder="Comentario"></textarea>
                    </div>

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

                    <div class="col-md-12 form-group" id="productosSeleccionados">
                        <table class="table table-bordered">
                            <thead class="cabecera-productos">
                                <tr class="titulo">
                                    <th colspan="5">PRODUCTOS SELECCIONADOS</th>
                                </tr>
                                <tr>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Cantidad</th>
                                    <th>Tallas</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody id="todosProductos">
                                
                            </tbody>
                        </table>
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

<!-- MODAL TALLAS -->
<div class="modal fade" id="modalTallas" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <!--<input type="hidden" id="codigoProducto" >-->

            <div class="modal-header cabecera-editar">
                <h5 class="modal-title" id="tituloTallas"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="cuerpoTallas">
                
            </div>

            <div class="modal-footer" id="footerTallas">
                
            </div>
        </div>
    </div>
</div>
    
@endsection

@section('js')
    <script src="{{ asset('js/cruds/documentos_almacen.js') }}"></script>
@endsection