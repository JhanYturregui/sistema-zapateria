var origin = localStorage.getItem('url')
var pathname = window.location.pathname

APP_URI = origin+pathname
/************ PROVEEDORES **************/
// MODAL CREAR
function crearProveedor(){
    $('#modalCrearProveedor').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// CREAR 
$('#btnCrearProveedor').click(function(){
    var ruc = $('#ruc').val()
    var nombre = $('#nombre').val()
    var direccion = $('#direccion').val()
    var correo = $('#correo').val()
    var telefono1 = $('#telefono1').val()
    var telefono2 = $('#telefono2').val()
    
    if(ruc == ""){
        $('#campoRUC').text("Campo obligatorio")
        $('#campoRUC').css('display', 'inline')
        $('#ruc').focus()
    
    }else if(nombre == ""){
        $('#campoNombre').text("Campo obligatorio")
        $('#campoNombre').css('display', 'inline')
        $('#nombre').focus()

    }else{
        var data = {
            ruc,
            nombre,
            direccion,
            correo,
            telefono1,
            telefono2,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'post',
            url: 'proveedores/crear',
            dataType: 'json',
            data,
            complete: function(a){
                res = a.responseJSON
                if(res.estado){
                    $('#modalCrearProveedor').modal('hide')
                    location.replace(APP_URI)
                    

                }else{
                    $('#campoRUC').text(res.mensaje)
                    $('#campoRUC').css('display', 'inline')
                }
            }   
        })
    }
})

// MODAL EDITAR
function editarProveedor(id){
    $.ajax({
        type: 'post',
        url: 'proveedores/editar',
        dataType: 'json',
        data: {
            id,
            _token: $('input[name=_token]').val(),
        },
        complete: function(res){
            var data = res.responseJSON
            var ruc = data.ruc
            var nombre = data.nombre
            var direccion = data.direccion
            var correo = data.correo
            var telefono1 = data.telefono1
            var telefono2 = data.telefono2

            $('#rucA').val(ruc)
            $('#nombreA').val(nombre)
            $('#direccionA').val(direccion)
            $('#correoA').val(correo)
            $('#telefono1A').val(telefono1)
            $('#telefono2A').val(telefono2)
            $('#idProveedorA').val(id)

            $('#modalEditarProveedor').modal({
                keyboard: false,
                backdrop: 'static'
            })
        }   
    })
}

// ACTUALIZAR 
$('#btnActualizarProveedor').click(function(){
    var id = $('#idProveedorA').val()
    var ruc = $('#rucA').val()
    var nombre = $('#nombreA').val()
    var direccion = $('#direccionA').val()
    var correo = $('#correoA').val()
    var telefono1 = $('#telefono1A').val()
    var telefono2 = $('#telefono2A').val()
    
    if(ruc == ""){
        $('#campoRUCA').text('Campo obligatorio')
        $('#campoRUCA').css('display', 'inline')
        $('#rucA').focus()

    }else if(nombre == ""){
        $('#campoNombreA').text('Campo obligatorio')
        $('#campoNombreA').css('display', 'inline')
        $('#nombreA').focus()

    }else{
        var data = {
            id,
            ruc,
            nombre,
            direccion,
            correo,
            telefono1,
            telefono2,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'patch',
            url: 'proveedores/actualizar',
            dataType: 'json',
            data,
            complete: function(a){
                res = a.responseJSON
                if(res.estado){
                    $('#modalEditarProveedor').modal('hide')
                    location.replace(APP_URI)

                }else{
                    $('#campoRUCA').text(res.mensaje)
                    $('#campoRUCA').css('display', 'inline')
                    $('#rucA').focus()
                }
            }   
        })
    }
})

// MODAL ELIMINAR
function eliminarProveedor(id){
    $('#idProveedorE').val(id)
    $('#modalEliminarProveedor').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// ELIMINAR 
$('#btnEliminarProveedor').click(function(){
    var id = $('#idProveedorE').val()
    var data = {
        id,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'delete',
        url: 'proveedores/eliminar',
        dataType: 'json',
        data,
        complete: function(a){
            $('#modalEliminarProveedor').modal('hide')
            location.replace(APP_URI)
        }   
    })
})