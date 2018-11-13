var origin = localStorage.getItem('url')
var pathname = window.location.pathname

URI_ACTUALIZAR = origin+pathname
URI_CREAR = origin+'/usuarios'

/************ USUARIOS **************/
// MODAL CREAR
function crearUsuario(){
    $('#numeroDoc').focus()
    $('#modalCrearUsuario').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// CREAR 
$('#btnCrearUsuario').click(function(){
    var numeroDocumento = $('#numeroDoc').val()
    var usuario = $('#usuario').val()
    var tipoUsuario = $('#tipoUsuario').val()
    var sucursal = $('#sucursal').val()
    
    if(numeroDocumento == ""){
        $('#campoNumeroDoc').text('Campo obligatorio')
        $('#campoNumeroDoc').css('display', 'inline')
        $('#numeroDoc').focus()

    }else if(usuario == ""){
        $('#campoUsuario').text('Campo obligatorio')
        $('#campoUsuario').css('display', 'inline')
        $('#usuario').focus()

    }else{
        var data = {
            numeroDocumento,
            usuario,
            tipoUsuario,
            sucursal,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'post',
            url: 'usuarios/crear',
            dataType: 'json',
            data,
            success: function(a){
                if(a.estado){
                    $('#modalCrearUsuario').modal('hide')
                    location.replace(URI_CREAR)

                }else{
                    $('#campoNumeroDoc').text(a.mensaje)
                    $('#campoNumeroDoc').css('display', 'inline')
                    $('#numeroDoc').focus()
                }
            },
            error: function(e){
                $('#campoNumeroDoc').text(e.message)
                $('#campoNumeroDoc').css('display', 'inline')
                $('#numeroDoc').focus()
            }
        })
    }
})

// MODAL EDITAR
function editarUsuario(id){
    $('#idUsuarioA').val(id)
    $.ajax({
        type: 'post',
        url: 'usuarios/editar',
        dataType: 'json',
        data: {
            id,
            _token: $('input[name=_token]').val(),
        },
        complete: function(res){
            var data = res.responseJSON
            numeroDoc = data.num_documento
            usuario = data.username
            tipoUsuario = data.tipo
            sucursal = data.sucursal

            $('#numeroDocA').val(numeroDoc)
            $('#usuarioA').val(usuario)
            $('#tipoUsuarioA').val(tipoUsuario)
            $('#sucursalA').val(sucursal)

            $('#modalEditarUsuario').modal({
                keyboard: false,
                backdrop: 'static'
            })
        }   
    })
}

// ACTUALIZAR 
$('#btnActualizarUsuario').click(function(){
    var id = $('#idUsuarioA').val()
    var numeroDocumento = $('#numeroDocA').val()
    var usuario = $('#usuarioA').val()
    var tipoUsuario = $('#tipoUsuarioA').val()
    var sucursal = $('#sucursalA').val()

    if(usuario == ""){
        $('#campoUsuarioA').text('Campo obligatorio')
        $('#campoUsuarioA').css('display', 'inline')
        $('#usuarioA').focus()

    }else{
        var data = {
            id,
            numeroDocumento,
            usuario,
            tipoUsuario,
            sucursal,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'patch',
            url: 'usuarios/actualizar',
            dataType: 'json',
            data,
            success: function(a){
                if(a.estado){
                    $('#modalEditarUsuario').modal('hide')
                    location.replace(URI_ACTUALIZAR)

                }else{
                    $('#campoUsuarioA').text(res.mensaje)
                    $('#campoUsuarioA').css('display', 'inline')
                    $('#usuarioA').focus()
                }
            },
            error: function(e){
                $('#campoUsuarioA').text(e.message)
                $('#campoUsuarioA').css('display', 'inline')
                $('#usuarioA').focus()
            }
        })
    }
})

// MODAL ELIMINAR
function eliminarUsuario(id){
    $('#idUsuarioE').val(id)
    $('#modalEliminarUsuario').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// ELIMINAR 
$('#btnEliminarUsuario').click(function(){
    var id = $('#idUsuarioE').val()
    var data = {
        id,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'delete',
        url: 'usuarios/eliminar',
        dataType: 'json',
        data,
        complete: function(a){
            $('#modalEliminarUsuario').modal('hide')
            location.replace(URI_ACTUALIZAR)
        }   
    })
})