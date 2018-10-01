<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Zapateria</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="{{ asset('css/estilos/app/app.css') }}" rel="stylesheet" >
    <link href="{{ asset('css/estilos/app/barra-navegacion.css') }}" rel="stylesheet" >
    @yield('css')
    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap.min.css') }}">
    <!-- JS -->
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap/bootstrap.min.js') }}"></script>
    
</head>
<body>
    <input type="hidden" id="txtDoc" value="{{ Auth::user()->num_documento }}">

    <div class="todo-contenido">
        <!-- MENÚ LATERAL -->
        <div class="menu-lateral">
            @include('partials.menu-lateral')
        </div>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="contentido-principal">

            <!-- Barra de navegación -->
            <div class="barra-navegacion">
                @include('partials.barra-navegacion')
            </div>

            <!-- Contenido Menú -->
            <div class="contenido-dinamico">
                @yield('contenido-principal')
            </div>

        </div>
    </div>

    <script src="{{ asset('js/app/app.js') }}"></script>
    @yield('js')

</body>
</html>