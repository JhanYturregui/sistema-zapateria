@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/estilos/app/crud.css') }}" rel="stylesheet" >
@endsection

@section('contenido-principal')

<div class="crud-small">

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

    <div class="datos">
            <div class="card text-center">
                <div class="card-header">
                    <h3>Listado de Reportes</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Número</th>
                                <th>Fecha</th>
                                <th>Monto</th>
                                <th>Detalles</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reportes as $reporte)
                                <tr>
                                    <td>{{ $reporte->numero }}</td>
                                    <td>{{ $reporte->created_at }}</td>
                                    <td>{{ $reporte->monto_real }}</td>
                                    <td><i class="fas fa-eye" title="Ver detalles" onclick="verDetalles('{{$reporte->numero}}')" style="cursor:pointer; color:#0277bd"></i></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
    
        </div>
    
        <div class="paginacion">
            {{ $reportes->links() }}
        </div>
    
</div>


<!-- MODAL DETALLE REPORTE -->
<div class="modal fade" id="modalDetalleReporte" tabindex="-1" role="dialog" aria-labelledby="detalleReporte" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header cabecera-crear">
                <h5 class="modal-title" id="detalleReporte">Detalle de Reporte</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="accordion" id="accordionExample">
                    <div class="card">
                      <div class="card-header" id="tituloDetalles">
                        <h5 class="mb-0">
                          <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#detalles" aria-expanded="true" aria-controls="collapseOne" id="btnDetalles">Detalles</button>
                        </h5>
                      </div>
                  
                      <div id="detalles" class="collapse show" aria-labelledby="tituloDetalles" data-parent="#accordionExample">
                        <div class="card-body" id="cuerpoDetalles">
                          
                        </div>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                          <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#ventas" aria-expanded="false" aria-controls="collapseTwo">
                            Ventas
                          </button>
                        </h5>
                      </div>
                      <div id="ventas" class="collapse" aria-labelledby="ventas" data-parent="#accordionExample">
                        <div class="card-body" id="cuerpoVentas">
                          
                        </div>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-header" id="headingThree">
                        <h5 class="mb-0">
                          <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#movimientos" aria-expanded="false" aria-controls="movimientos">
                            Movimientos
                          </button>
                        </h5>
                      </div>
                      <div id="movimientos" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                        <div class="card-body" id="cuerpoMovimientos">
                          
                        </div>
                      </div>
                    </div>
                  </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

    
@endsection

@section('js')
    <script src="{{ asset('js/cruds/reportes.js') }}"></script>
@endsection