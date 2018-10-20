@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/estilos/app/crud.css') }}" rel="stylesheet" >
@endsection

@section('contenido-principal')

<div class="crud-large">

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

    <div class="botones">
        @if ($aperturado == true)
            <button class="btn btn-success crear" onclick="crearMovimiento()" style="margin-right: 8px"><i class="fas fa-plus"></i>Nuevo movimiento</button>
            <button class="btn btn-danger crear" onclick="cerrarCaja()"><i class="fas fa-lock"></i>Cerrar caja</button>
        @else
            <button class="btn btn-primary crear" onclick="aperturarCaja()"><i class="fas fa-lock-open"></i>Aperturar caja</button>    
        @endif
        
    </div>

    <div class="datos">
            <div class="card text-center">
                <div class="card-header">
                    <h3>Listado de Movimientos</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Numero</th>
                                <th>Tipo</th>
                                <th>Fecha</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($movimientos as $movimiento)
                                <tr>
                                    <td>{{ $movimiento->numero }}</td>
                                    <td>{{ $movimiento->tipo }}</td>
                                    <td>{{ $movimiento->created_at }}</td>
                                    <td>{{ $movimiento->monto }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
    
        </div>
    
        <div class="paginacion">
            {{ $movimientos->links() }}
        </div>
    
</div>


<!-- MODAL APERTURAR CAJA -->
<div class="modal fade" id="modalAperturarCaja" tabindex="-1" role="dialog" aria-labelledby="aperturarCaja" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header cabecera-crear">
                <h5 class="modal-title" id="aperturarCaja">Aperturar Caja</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body modal-doc">
                <form>
                    <div class="form-group row">
                      <label for="fechaApertura" class="col-sm-3 col-form-label">Fecha</label>
                      <div class="col-sm-9">
                        <input type="text" readonly class="form-control" id="fechaApertura" value="">
                      </div>
                    </div>
                    <div class="form-group row">
                        <label for="numeroCaja" class="col-sm-3 col-form-label">Número</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="numeroCaja" readonly value="{{$numeroCaja}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="concepto" class="col-sm-3 col-form-label">Concepto</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="concepto" readonly value="Apertura de caja">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="montoApertura" class="col-sm-3 col-form-label">Monto Apertura</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="montoApertura">
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnAperturarCaja">Aperturar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL CERRAR CAJA -->
<div class="modal fade" id="modalCerrarCaja" tabindex="-1" role="dialog" aria-labelledby="cerrarCaja" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
    
                <div class="modal-header cabecera-eliminar">
                    <h5 class="modal-title" id="cerrarCaja">Cerrar Caja</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
            <div class="modal-body modal-doc">
                    <form>
                        <div class="form-group row">
                          <label for="fechaCierre" class="col-sm-3 col-form-label">Fecha</label>
                          <div class="col-sm-9">
                            <input type="text" readonly class="form-control" id="fechaCierre">
                          </div>
                        </div>
                        <div class="form-group row">
                            <label for="numeroCajaC" class="col-sm-3 col-form-label">Número</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="numeroCajaC" readonly value="{{$numeroCaja}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="conceptoCierre" class="col-sm-3 col-form-label">Concepto</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="conceptoCierre" readonly value="Cierre de caja">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="montoCierre" class="col-sm-3 col-form-label">Monto Cierre</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="montoCierre" readonly value="{{$montoCierre}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="montoReal" class="col-sm-3 col-form-label">Monto Real</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="montoReal">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="comentario" class="col-sm-3 col-form-label">Comentario</label>
                            <div class="col-sm-9">
                                <textarea type="text" class="form-control" id="comentario"></textarea>
                            </div>
                        </div>
                    </form>
            </div>
    
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnCerrarCaja">Aperturar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL MOVIMIENTO DE CAJA -->
<div class="modal fade" id="modalMovimientoCaja" tabindex="-1" role="dialog" aria-labelledby="movimientoCaja" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header cabecera-editar">
                <h5 class="modal-title" id="movimientoCaja">Registrar Movimiento de Caja</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body modal-doc">
                <form>
                    <div class="form-group row">
                      <label for="fechaMovimiento" class="col-sm-3 col-form-label">Fecha</label>
                      <div class="col-sm-9">
                        <input type="text" readonly class="form-control" id="fechaMovimiento" value="">
                      </div>
                    </div>
                    <div class="form-group row">
                        <label for="numeroMovimiento" class="col-sm-3 col-form-label">Número</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="numeroMovimiento" readonly value="{{$numeroMovimiento}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tipoMovimiento" class="col-sm-3 col-form-label">Tipo</label>
                        <div class="col-sm-9">
                            <select id="tipoMovimiento" class="form-control" onchange="listarConceptos()">
                                <option value="ingreso">Ingreso</option>
                                <option value="egreso">Egreso</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="conceptoMovimiento" class="col-sm-3 col-form-label">Concepto</label>
                        <div class="col-sm-9">
                            <select id="conceptoMovimiento" class="form-control"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="persona" class="col-sm-3 col-form-label">Persona</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="persona" placeholder="Número documento" onkeyup="soloEnteros(event, this.id)">
                            <small id="campoPersona" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                                Campo obligatorio</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="montoMovimiento" class="col-sm-3 col-form-label">Total</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="montoMovimiento" placeholder="Monto del movimiento" onkeyup="numerosDecimales(event, this.id)">
                            <small id="campoMonto" class="help-block col-sm-offset-0 col-sm-12 validar-campo">
                                Campo obligatorio</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="comentarioMovimiento" class="col-sm-3 col-form-label">Comentario</label>
                        <div class="col-sm-9">
                            <textarea type="text" class="form-control" id="comentarioMovimiento"></textarea>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnRegistrarMovimiento">Registrar</button>
            </div>
        </div>
    </div>
</div>
    
@endsection

@section('js')
    <script src="{{ asset('js/cruds/caja.js') }}"></script>
@endsection