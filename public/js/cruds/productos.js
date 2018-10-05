//APP_URI = "http://localhost:8000"
var origin = window.location.origin
var pathname = window.location.pathname
APP_URI = origin+pathname
/************ PROVEEDORES **************/
// MODAL CREAR
function crearProducto(){
    $('#modalCrearProducto').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// CREAR 
$('#btnCrearProducto').click(function(){
    var descripcion = $('#descripcion').val()
    var marca = $('#marca').val()
    var modelo = $('#modelo').val()
    var color = $('#color').val()
    var talla = $('#talla').val()
    var linea = $('#linea').val()
    
    var data = {
        descripcion,
        marca,
        modelo,
        color,
        talla,
        linea,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'post',
        url: 'productos/crear',
        dataType: 'json',
        data,
        success: function(res){
            if(res.estado){
                $('#modalCrearProducto').modal('hide')
                location.replace(APP_URI)
                    
            }else{
                $('#error').text(res.mensaje)
                $('#error').css('display', 'inline')
            }
        },
        error: function(xhr, status){
            var mensaje = "Error al crear producto. Vuelva a intentarlo."
            $('#error').text(mensaje)
            $('#error').css('display', 'inline')
        }   
    })
})

// MODAL EDITAR
function editarProducto(id){
    $.ajax({
        type: 'post',
        url: 'productos/editar',
        dataType: 'json',
        data: {
            id,
            _token: $('input[name=_token]').val(),
        },
        complete: function(res){
            var data = res.responseJSON
            var descripcion = data.descripcion
            var marca = data.marca
            var modelo = data.modelo
            var color = data.color
            var talla = data.talla
            var linea = data.linea

            $('#descripcionA').val(descripcion)
            $('#marcaA').val(marca)
            $('#modeloA').val(modelo)
            $('#colorA').val(color)
            $('#tallaA').val(talla)
            $('#lineaA').val(linea)
            $('#idProductoA').val(id)

            $('#modalEditarProducto').modal({
                keyboard: false,
                backdrop: 'static'
            })
        }   
    })
}

// ACTUALIZAR 
$('#btnActualizarProducto').click(function(){
    var id = $('#idProductoA').val()
    var descripcion = $('#descripcionA').val()
    var marca = $('#marcaA').val()
    var modelo = $('#modeloA').val()
    var color = $('#colorA').val()
    var talla = $('#tallaA').val()
    var linea = $('#lineaA').val()

    var data = {
        id,
        descripcion,
        marca,
        modelo,
        color,
        talla,
        linea,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'patch',
        url: 'productos/actualizar',
        dataType: 'json',
        data,
        success: function(res){
            if(res.estado){
                $('#modalEditarProducto').modal('hide')
                location.replace(APP_URI)

            }else{
                $('#error').text(res.mensaje)
                $('#error').css('display', 'inline')
            }
        },
        error: function(xhr, status){
            var mensaje = "Error al actualizar producto. Vuelva a intentarlo."
            $('#error').text(mensaje)
            $('#error').css('display', 'inline')
        }   
    })
})

// MODAL ELIMINAR
function eliminarLinea(id){
    $('#idLineaE').val(id)
    $('#modalEliminarLinea').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// ELIMINAR 
$('#btnEliminarLinea').click(function(){
    var id = $('#idLineaE').val()
    var data = {
        id,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'delete',
        url: 'lineas/eliminar',
        dataType: 'json',
        data,
        complete: function(a){
            $('#modalEliminarLinea').modal('hide')
            location.replace(APP_URI+'/lineas')
        }   
    })
})