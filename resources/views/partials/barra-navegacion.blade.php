<div class="contenido-barra">
    <div class="nombre-tienda">
        <h4>Zapatería</h4>
    </div>
    <div class="botones-navegacion">
        <ul>
            <li><i class="fas fa-cog" onclick="cambiarContraseña()" title="Cambiar contraseña" style="cursor: pointer;"></i></li>
            <li>
                <a href="{{ route('logout') }}" title="Cerrar sesión" onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                </form>
            </li>
        </ul>
    </div>	
</div>

<!-- MODAL CAMBIAR CONTRASEÑA -->
<div class="modal fade" id="modalCambiarContraseña" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <!--<input type="hidden" id="numeroDocumento">-->

            <div class="modal-header cabecera-editar">
                <h5 class="modal-title" id="">Cambiar contraseña</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                Cambiar contraseña
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnCambiarContraseña">Guardar</button>
            </div>
        </div>
    </div>
</div>