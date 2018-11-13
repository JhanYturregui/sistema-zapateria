@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/estilos/app/crud.css') }}" rel="stylesheet" >
@endsection

@section('contenido-principal')

<div class="crud-large">

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

    <div class="botones">
        <button class="btn btn-primary crear" onclick="crearPersona()"><i class="fas fa-plus"></i>Crear</button>
        <input type="search" class="form-control buscar" placeholder="Buscar">
    </div>

    <div class="datos">
        <div class="card text-center">
            <div class="card-header">
                <h3>Lista de Personas</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tipo Doc</th>
                            <th>N° Doc</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Correo</th>
                            <th colspan="2"><i class="fas fa-wrench"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($personas as $persona)
                            <tr>
                                <td>{{ $persona->tipo_documento }}</td>
                                <td>{{ $persona->numero_documento }}</td>
                                <td>{{ $persona->nombres }}</td>
                                <td>{{ $persona->apellidos }}</td>
                                <td>{{ $persona->correo }}</td>
                                <td><i class="fas fa-pen" title="Editar" onclick="editarPersona({{$persona->id}})"></i></td>
                                <td><i class="fas fa-trash" title="Eliminar" onclick="eliminarPersona({{$persona->id}})"></i></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="paginacion">
        {{ $personas->links() }}
    </div>

</div>


<!-- MODAL CREAR ROL -->
<div class="modal fade" id="modalCrearPersona" tabindex="-1" role="dialog" aria-labelledby="crearPersona" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header cabecera-crear">
                <h5 class="modal-title" id="crearPersona">Crear Persona</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="tipoDocumento">Tipo Doc</label>
                            <select class="form-control" id="tipoDocumento" onchange="segunTipo()">
                                <option value="DNI">DNI</option>
                                <option value="RUC">RUC</option>
                            </select>
                        </div>    
                        <div class="form-group col-md-4">
                            <label for="numeroDocumento">Numero Doc</label>
                            <input type="text" id="numeroDocumento" class="form-control" placeholder="Número de documento">
                            <small id="campoNumeroDoc" class="help-block col-sm-offset-0 col-sm-12 validar-campo">Campo obligatorio</small>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="razonSocial">Razón social</label>
                            <input type="text" id="razonSocial" class="form-control" placeholder="Razón Social" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nombresPersona">Nombres</label>
                            <input type="text" id="nombresPersona" class="form-control" placeholder="Nombres">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="apellidosPersona">Apellidos</label>
                            <input type="text" id="apellidosPersona" class="form-control" placeholder="Apellidos">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="correoPersona">Correo</label>
                            <input type="text" id="correoPersona" class="form-control" placeholder="Correo">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="direccionPersona">Dirección</label>
                            <input type="text" id="direccionPersona" class="form-control" placeholder="Dirección">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="telfonoPersona">Teléfono</label>
                            <input type="text" id="telefonoPersona" class="form-control" placeholder="Teléfono">
                        </div>
                        <div class="form-group col-md-12">
                            @foreach ($roles as $rol)
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="checkbox" id="rol-{{$rol->id}}" class="custom-control-input roles" value="{{$rol->id}}">
                                <label class="custom-control-label" for="rol-{{$rol->id}}">{{$rol->nombre}}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnCrearPersona">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ACTUALIZAR Persona -->
<div class="modal fade" id="modalEditarPersona" tabindex="-1" role="dialog" aria-labelledby="editarPersona" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <input type="hidden" id="idPersonaA">

            <div class="modal-header cabecera-editar">
                <h5 class="modal-title" id="editarPersona">Editar Persona</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="tipoDocumentoA">Tipo Doc</label>
                            <select class="form-control" id="tipoDocumentoA" onchange="segunTipoAct()">
                                <option value="DNI">DNI</option>
                                <option value="RUC">RUC</option>
                            </select>
                        </div>    
                        <div class="form-group col-md-4">
                            <label for="numeroDocumentoA">Numero Doc</label>
                            <input type="text" id="numeroDocumentoA" class="form-control" placeholder="Número de documento">
                            <small id="campoNumeroDocA" class="help-block col-sm-offset-0 col-sm-12 validar-campo">Campo obligatorio</small>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="razonSocialA">Razón social</label>
                            <input type="text" id="razonSocialA" class="form-control" placeholder="Razón Social" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nombresPersonaA">Nombres</label>
                            <input type="text" id="nombresPersonaA" class="form-control" placeholder="Nombres">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="apellidosPersonaA">Apellidos</label>
                            <input type="text" id="apellidosPersonaA" class="form-control" placeholder="Apellidos">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="correoPersonaA">Correo</label>
                            <input type="text" id="correoPersonaA" class="form-control" placeholder="Correo">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="direccionPersonaA">Dirección</label>
                            <input type="text" id="direccionPersonaA" class="form-control" placeholder="Dirección">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="telfonoPersonaA">Teléfono</label>
                            <input type="text" id="telefonoPersonaA" class="form-control" placeholder="Teléfono">
                        </div>
                        <div class="form-group col-md-12">
                            @foreach ($roles as $rol)
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="checkbox" id="rol-{{$rol->id}}-A" class="custom-control-input roles" value="{{$rol->id}}">
                                <label class="custom-control-label" for="rol-{{$rol->id}}-A">{{$rol->nombre}}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnActualizarPersona">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ELIMINAR PERSONA -->
<div class="modal fade" id="modalEliminarPersona" tabindex="-1" role="dialog" aria-labelledby="eliminarPersona" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

            <input type="hidden" id="idPersonaE">

            <div class="modal-header cabecera-eliminar">
                <h5 class="modal-title" id="eliminarPersona">Eliminar Persona</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                ¿Está seguro que desea eliminar a esta persona?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnEliminarPersona">Eliminar</button>
            </div>
        </div>
    </div>
</div>
    
@endsection

@section('js')
    <script src="{{ asset('js/cruds/personas.js') }}"></script>
@endsection