<div class="contenido-barra">
    <div class="nombre-tienda">
        <h4>Zapatería</h4>
    </div>
    <div class="botones-navegacion">
        <ul>
            <li><a href="#" title="Configuración"><i class="fas fa-cog"></i></a></li>
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