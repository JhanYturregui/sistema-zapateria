var origin = localStorage.getItem('url')
var pathname = window.location.pathname

URI_ACTUALIZAR = origin+pathname
URI_CREAR = origin+'/sucursales'

/************ SUCURSALES **************/
// MODAL CREAR
function crearSucursal(){
    $('#modalCrearSucursal').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// CREAR 
$('#btnCrearSucursal').click(function(){
    var nombre = $('#nombreSucursal').val()
    var direccion = $('#direccionSucursal').val()
    
    if(nombre == ""){
        $('#campoNombre').text('Campo obligatorio')
        $('#campoNombre').css('display', 'inline')
        $('#nombreSucursal').focus()

    }else{
        var data = {
            nombre,
            direccion,            
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'post',
            url: 'sucursales/crear',
            dataType: 'json',
            data,
            success: function(a){
                if(a.estado){
                    $('#modalCrearSucursal').modal('hide')
                    location.replace(URI_CREAR)

                }else{
                    $('#campoNombre').text(a.mensaje)
                    $('#campoNombre').css('display', 'inline')
                    $('#nombreSucursal').focus()
                }
            },
            error: function(e){
                $('#campoNombre').text(e.message)
                $('#campoNombre').css('display', 'inline')
            }   
        })
    }
})

// MODAL EDITAR
function editarSucursal(id){
    $.ajax({
        type: 'post',
        url: 'sucursales/editar',
        dataType: 'json',
        data: {
            id,
            _token: $('input[name=_token]').val(),
        },
        complete: function(res){
            var data = res.responseJSON
            var nombre = data.nombre
            var direccion = data.direccion

            $('#nombreSucursalA').val(nombre)
            $('#direccionSucursalA').val(direccion)
            $('#idSucursalA').val(id)

            $('#modalEditarSucursal').modal({
                keyboard: false,
                backdrop: 'static'
            })
        }   
    })
}

// ACTUALIZAR 
$('#btnActualizarSucursal').click(function(){
    var id = $('#idSucursalA').val()
    var nombre = $('#nombreSucursalA').val()
    var direccion = $('#direccionSucursalA').val()
    
    if(nombre == ""){
        $('#campoNombreA').css('display', 'inline')
        $('#nombreSucursalA').focus()

    }else{
        var data = {
            id,
            nombre,
            direccion,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'patch',
            url: 'sucursales/actualizar',
            dataType: 'json',
            data,
            success: function(a){
                if(a.estado){
                    $('#modalEditarSucursal').modal('hide')
                    location.replace(URI_ACTUALIZAR)

                }else{
                    $('#campoNombreA').text(a.mensaje)
                    $('#campoNombreA').css('display', 'inline')
                    $('#nombreSucursalA').focus()
                }
            },
            error: function(e){
                $('#campoNombreA').text(e.message)
                $('#campoNombreA').css('display', 'inline')
                $('#nombreSucursalA').focus()
            }   
        })
    }
})

// MODAL ELIMINAR
function eliminarSucursal(id){
    $('#idSucursalE').val(id)
    $('#modalEliminarSucursal').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// ELIMINAR 
$('#btnEliminarSucursal').click(function(){
    var id = $('#idSucursalE').val()
    var data = {
        id,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'delete',
        url: 'sucursales/eliminar',
        dataType: 'json',
        data,
        complete: function(a){
            $('#modalEliminarSucursal').modal('hide')
            location.replace(URI_ACTUALIZAR)
        }   
    })
})