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
                    <thead>
                        <tr>
                            <th>Numero</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            @if (Auth::user()->tipo == 1)
                                <th>Anular</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($compras as $compra)
                            <tr>
                                <td>{{ $compra->numero }}</td>
                                <td>{{ $compra->created_at }}</td>
                                <td>{{ $compra->monto_total }}</td>
                                @if (Auth::user()->tipo == 1 || Auth::user()->tipo == 2)
                                    <td><i class="fas fa-trash" title="Anular" onclick="anularDocumentoCompra('{{$compra->numero}}')"></i></td>
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
                    <div class="col-md-4 form-group">
                        <label for="numeroDoc">N° Documento</label>
                        <input type="text" value="{{$numeroDoc}}" id="numeroDoc" class="form-control" readonly>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="fechaDoc">Fecha Documento</label>
                        <input type="text" id="fechaDoc" class="form-control" readonly>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="codigoProd">Código producto</label>
                        <input type="text" id="codigoProd" class="form-control" placeholder="Ingrese código" onkeyup="buscarProductos(this.value)" onkeypress="agregarProd(event, this.value)">
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="docProveedor">N° Doc Proveedor</label>
                        <input type="text" id="docProveedor" class="form-control" placeholder="Ingrese número documento" onkeyup="soloNumerosPersona(event)" onblur="buscarPersona(this.value)">
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
                                    <th>Cantidad</th>
                                    <th>Precio</th>
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
                                    <th colspan="11">PRODUCTOS SELECCIONADOS</th>
                                </tr>
                                <tr>
                                    <th>Código</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Color</th>
                                    <th>Talla</th>
                                    <th>Línea</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Dsct</th>
                                    <th>Precio Final</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody id="todosProductos">
                                
                            </tbody>
                        </table>
                    </div>    
                    
                    <div class="col-md-12 caja" id="caja">
                        <div class="metodo-pago">
                            <div class="elementos">
                                <label for="metodoPago">Método de pago</label>
                                <select class="form-control" id="metodoPago" onchange="metodosPago()">
                                    <option value="efectivo">Efectivo</option>
                                    <option value="tarjeta">Tarjeta</option>
                                    <option value="ambos">Ambos</option>
                                </select>
                            </div>
                            <div class="elementos">
                                <label for="cantEfectivo">Efectivo</label>
                                <input type="text" id="cantEfectivo" class="form-control" value="0" onkeyup="soloNumerosEfectivo(event), calcularResto(this.id), calcularVuelto2()">
                            </div>
                            <div class="elementos">
                                <label for="cantTarjeta">Tarjeta</label>
                                <input type="text" id="cantTarjeta" value="0" class="form-control" readonly onkeyup="soloNumerosTarjeta(event), calcularResto(this.id)">    
                            </div>
                        </div>
                        <div class="total">
                            <label class="label-cantidad">TOTAL:</label>
                            <input type="text" class="cantidad-total" id="cantTotal" readonly value="0">
                            <label class="label-cantidad">DINERO:</label>
                            <input type="text" class="dinero" id="dinero" onkeyup="soloNumerosDinero(event), calcularVuelto()">
                            <label class="label-cantidad">VUELTO:</label>
                            <input type="text" class="vuelto" id="vuelto" readonly>
                        </div>
                        
                    </div>    

                    <div class="col-md-12 form-group">
                        <small id="mensaje" class="help-block col-sm-offset-0 col-sm-12 validar-campo-lg">
                            </small>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnRegistrarDocumentoVenta">Registrar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ANULAR DOCUMENTO -->
<div class="modal fade" id="modalAnularDocumentoVenta" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
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
                ¿Está seguro que desea anular este documento de venta?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnAnularDocumentoVenta">Anular</button>
            </div>
        </div>
    </div>
</div>
    
@endsection

@section('js')
    <script src="{{ asset('js/cruds/documentos_compra.js') }}"></script>
@endsection