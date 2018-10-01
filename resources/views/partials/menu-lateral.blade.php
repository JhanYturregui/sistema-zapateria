<div class="menu-lateral">

    <div class="contenido-lateral">

        <!-- CABECERA -->
        <div class="cabecera-lateral">

        </div>

        <!-- CUERPO -->
        <div class="cuerpo-lateral">

            @foreach ($datos as $categoria => $value)
                <div class="categorias">
                    <p>
                        <i class="fas fa-lock"></i> {{$categoria}} <i class="fas fa-sort-down"></i>
                    </p>
                </div>

                @foreach ($value as $llave => $opciones)
                    @foreach ($opciones as $ruta => $opcion)
                    <div class="opciones-categoria">
                        <ul>
                            <li>
                                <a href="{{ route($ruta) }}"><i class="fas fa-caret-right"></i>{{$opcion}}</a>
                            </li>
                        </ul>
                    </div>
                    @endforeach
                @endforeach
                
            @endforeach

            <!--<div class="categorias">
                <p>
                    <i class="fas fa-lock"></i> Seguridad <i class="fas fa-sort-down"></i>
                </p>
            </div>

            <div class="opciones-categoria">
                <ul>
                    <li>
                        <a href="{{ route('categorias') }}"><i class="fas fa-caret-right"></i>Categorías</a>
                    </li>
                    <li>
                        <a href="{{ route('opciones') }}"><i class="fas fa-caret-right"></i>Opciones</a>
                    </li>
                </ul>
            </div>

            <div class="categorias">
                <p>
                    <i class="fas fa-lock"></i> Base de Datos <i class="fas fa-sort-down"></i>
                </p>
            </div>

            <div class="opciones-categoria">
                <ul>
                    <li>
                        <a href="{{ route('tipos_usuario') }}"><i class="fas fa-caret-right"></i>Tipo de Usuario</a>
                    </li>
                    <li>
                        <a href="{{ route('roles') }}"><i class="fas fa-caret-right"></i>Roles</a>
                    </li>
                </ul>
            </div>-->
                    
        </div>

    </div>

</div>