<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Zapatería</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="{{ asset('css/estilos/login.css') }}" rel="stylesheet" >
    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap.min.css') }}">
    <!-- JS -->
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap/bootstrap.min.js') }}"></script>

</head>
<body>

    <div class="login-form">

        <div class="card text-center">
            <div class="card-header">
                <h2><i class="fas fa-key"></i>Autenticación</h2>
            </div>

            <div class="card-body">
                <form  method="POST" action="{{ url('/login') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Usuario" required autofocus>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-unlock-alt"></i></span>
                        <input type="password" class="form-control" name="password" placeholder="Contraseña" required autofocus>
                      </div>
                    </div>
                    <ul class="list-unstyled list-inline pull-right">
                      <!--<li class="list-inline-item"> <a class="btn btn-link" href="{{ url('/password/reset') }}"> ¿Olvidaste tu contraseña?</a></li>-->
                      <li class="list-inline-item"> <button type="submit" class="btn btn-primary">  Iniciar sesión</button></li>
                    </ul>
                </form>
            </div>
        </div>    

        @include('partials.errores-login')  

    </div>

    <!-- Bootstrap JS -->
    <link rel="stylesheet" href="{{ asset('js/bootstrap/bootstrap.min.js') }}">
    
</body>
</html>