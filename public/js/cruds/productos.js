var origin = localStorage.getItem('url')
var pathname = window.location.pathname

URI = origin+pathname

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
    var modelo = $('#modelo').val()
    var descripcion = $('#descripcion').val()
    var marca = $('#marca').val()
    var color = $('#color').val()
    var taco = $('#taco').val()
    var linea = $('#linea').val()
    var linea2 = $('#linea2').val()
    var linea3 = $('#linea3').val()
    var compra = $('#precioCompra').val()
    var precioCompra = parseFloat(compra)
    var venta = $('#precioVenta').val()
    var precioVenta = parseFloat(venta)

    if(isNaN(precioCompra)){
        $('#error').text('El precio debe ser un valor númerico')
        $('#error').css('display', 'inline')
        $('#precioCompra').focus()

    }else if(isNaN(precioVenta)){
        $('#error').text('El precio debe ser un valor númerico')
        $('#error').css('display', 'inline')
        $('#precioVenta').focus()

    }else{
        var data = {
            modelo,
            descripcion,
            marca,
            color,
            taco,
            linea,
            linea2,
            linea3,
            precioCompra,
            precioVenta,
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
                    location.replace(URI)
                        
                }else{
                    $('#error').text(res.mensaje)
                    $('#error').css('display', 'inline')
                }
            },
            error: function(e){
                $('#error').text(e.message)
                $('#error').css('display', 'inline')
            }   
        })
    }
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
            var modelo = data.modelo
            var descripcion = data.descripcion
            var marca = data.marca
            var color = data.color
            var taco = data.taco
            var linea = data.linea
            var linea2 = data.linea_2
            var linea3 = data.linea_3
            var precioCompra = data.precio_compra
            var precioVenta = data.precio_venta

            $('#modeloA').val(modelo)
            $('#descripcionA').val(descripcion)
            $('#marcaA').val(marca).trigger('change')
            $('#colorA').val(color).trigger('change')
            $('#tacoA').val(taco).trigger('change')
            $('#lineaA').val(linea).trigger('change')
            $('#linea2A').val(linea2).trigger('change')
            $('#linea3A').val(linea3).trigger('change')
            $('#precioCompraA').val(precioCompra)
            $('#precioVentaA').val(precioVenta)
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
    var codigo = $('#codigoA').val()
    var descripcion = $('#descripcionA').val()
    var marca = $('#marcaA').val()
    var modelo = $('#modeloA').val()
    var color = $('#colorA').val()
    var taco = $('#tacoA').val()
    var linea = $('#lineaA').val()
    var precioCompra = $('#precioCompraA').val()
    var precioVenta = $('#precioVentaA').val()

    var data = {
        id,
        codigo,
        descripcion,
        marca,
        modelo,
        color,
        taco,
        linea,
        precioCompra,
        precioVenta,
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
                location.replace(URI)

            }else{
                $('#errorA').text(res.mensaje)
                $('#errorA').css('display', 'inline')
            }
        },
        error: function(xhr, status){
            var mensaje = "Error al actualizar producto. Vuelva a intentarlo."
            $('#errorA').text(mensaje)
            $('#errorA').css('display', 'inline')
        }   
    })
})

// MODAL ELIMINAR
function eliminarProducto(id){
    $('#idProductoE').val(id)
    $('#modalEliminarProducto').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// ELIMINAR 
$('#btnEliminarProducto').click(function(){
    var id = $('#idProductoE').val()
    var data = {
        id,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'delete',
        url: 'productos/eliminar',
        dataType: 'json',
        data,
        success: function(a){
            $('#modalEliminarProducto').modal('hide')
            location.replace(URI)
        },
        error: function(a){
            console.log(a)
            $('#error').text(a)
            $('#error').css('display', 'inline')
        }   
    })
})

function buscarPorCodigo(codigo){
    var data = {
        codigo,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'post',
        url: 'productos/listarxCod',
        dataType: 'json',
        data,
        success: function(a){
            
        },
        error: function(a){

        }   
    })
}