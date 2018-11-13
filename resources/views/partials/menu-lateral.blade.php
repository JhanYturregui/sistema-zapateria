<div class="menu-lateral">

    <div class="contenido-lateral">

        <!-- CABECERA -->
        <!--<div class="cabecera-lateral">

        </div>-->

        <!-- CUERPO -->
        <div class="cuerpo-lateral">
            <div class="accordion">
                @php
                    $i = 1;
                @endphp
                @foreach ($datos as $categoria => $value)
                    @php
                        if($categoria == 'BASE DE DATOS'){
                            $id = "listaBASE";
                        }else{
                            $id = "lista".$categoria;
                        }
                    @endphp
                    <div class="categorias" data-toggle="collapse" data-target="#<?php echo $id ?>" aria-expanded="<?php echo ($i==1) ? 'true' : 'false' ?>">
                        <p>
                            <i class="fas fa-list-ul"></i> {{$categoria}} <i class="fas fa-sort-down"></i>
                        </p>
                    </div>

                    <div class="opciones-categoria collapse show" id="<?php echo $id ?>">
                    @foreach ($value as $llave => $opciones)
                        @foreach ($opciones as $ruta => $opcion)    
                            <ul>
                                <li>
                                    <a href="{{ route($ruta) }}"><i class="fas fa-circle"></i>{{$opcion}}</a>
                                </li>
                            </ul>
                        @endforeach
                    @endforeach
                    </div>        
                    
                    @php
                        $i++;
                    @endphp
                    
                @endforeach

                <input type="hidden" id="contadorCategorias" value="{{$i}}">

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

</div>