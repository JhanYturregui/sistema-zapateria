@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/estilos/app/crud.css') }}" rel="stylesheet" >
@endsection

@section('contenido-principal')

<div class="crud-large">

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

    <div class="botones">
        <button class="btn btn-primary crear" onclick="crearDocumentoCompra()"><i class="fas fa-plus"></i>Registrar compra</button>
        <!--<input type="search" class="form-control buscar" placeholder="Buscar">-->
    </div>

    <div class="datos">
        <div class="card text-center">
            <div class="card-header">
                <h3>Lista de Documentos de Compra</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="cabecera-datos">
                        <tr>
                            <th>Numero</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            @if (Auth::user()->tipo == 1)
                                <th>Anular</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($compras as $compra)
                            <tr>
                                <td class="identificador">{{ $compra->numero }}</td>
                                <td>{{ $compra->created_at }}</td>
                                <td>{{ $compra->monto_total }}</td>
                                @if ($compra->estado == false)
                                    <td style="color: red"><i class="fas fa-ban" title="Anulado"></i></td>
                                @else
                                    <td style="color:#4caf50"><i class="fas fa-check"></i></td>    
                                @endif
                                @if (Auth::user()->tipo == 1 || Auth::user()->tipo == 2)
                                    @if ($compra->estado == true)
                                        <td><i class="fas fa-trash" title="Anular" onclick="anularDocumentoCompra('{{$compra->numero}}')"></i></td>
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
        {{$compras->links()}}
    </div>

</div>


<!-- MODAL CREAR DOCUMENTO -->
<div class="modal fade" id="modalCrearDocumentoCompra" tabindex="-1" role="dialog" aria-labelledby="crearDocumentoCompra" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header cabecera-crear">
                <h5 class="modal-title" id="crearDocumentoCompra">Registrar Compra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body modal-doc">
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="numeroDoc">N° Factura</label>
                        <input type="text" value="" id="numeroDoc" class="form-control" >
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="fechaDoc">Fecha</label>
                        <input type="date" id="fechaDoc" class="form-control">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="montoTotal">Monto</label>
                        <input type="text" id="montoTotal" class="form-control">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="codigoProd">Código producto</label>
                        <input type="text" id="codigoProd" class="form-control" placeholder="Ingrese código" onkeyup="buscarProductos(this.value)" onkeypress="agregarProd(event, this.value)">
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="docProveedor">N° Doc Proveedor</label>
                        <input type="text" id="docProveedor" class="form-control" placeholder="Ingrese número documento" onkeyup="soloNumerosPersona(event)" onblur="buscarProveedor(this.value)">
                    </div>
                    <div class="col-md-8 form-group">
                        <label for="datosProveedor">Dato Proveedor</label>
                        <input type="text" id="datosProveedor" class="form-control" readonly>
                    </div>

                    <div class="col-md-2 form-group"></div>
                    <div class="col-md-8 form-group" id="productos">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Descripción</th>
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
                            <thead class="cabecera-productos">
                                <tr class="titulo">
                                    <th colspan="7">PRODUCTOS SELECCIONADOS</th>
                                </tr>
                                <tr>
                                    <th>Código</th>
                                    <th>Marca</th>
                                    <th>Color</th>
                                    <th>Línea</th>
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
                <button type="button" class="btn btn-primary" id="btnRegistrarDocumentoCompra">Registrar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ANULAR DOCUMENTO -->
<div class="modal fade" id="modalAnularDocumentoCompra" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
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
                ¿Está seguro que desea anular este documento de compra?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnAnularDocumentoCompra">Anular</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TALLAS -->
<div class="modal fade" id="modalTallas" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <input type="hidden" id="codigoProducto" >

            <div class="modal-header cabecera-accesos">
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
    <script src="{{ asset('js/cruds/documentos_compra.js') }}"></script>
@endsection