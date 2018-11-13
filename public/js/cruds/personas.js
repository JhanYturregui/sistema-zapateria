var origin = localStorage.getItem('url')
var pathname = window.location.pathname

URI_ACTUALIZAR = origin+pathname
URI_CREAR = origin+'/personas'

/************ PERSONAS **************/
// MODAL CREAR
function crearPersona(){
    $('#modalCrearPersona').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// CREAR 
$('#btnCrearPersona').click(function(){
    var tipoDocumento = $('#tipoDocumento').val()
    var numeroDocumento = $('#numeroDocumento').val()
    var nombres = $('#nombresPersona').val()
    var apellidos = $('#apellidosPersona').val()
    var razonSocial = $('#razonSocial').val()
    var correo = $('#correoPersona').val()
    var telefono = $('#telefonoPersona').val()
    var direccion = $('#direccionPersona').val()
    var roles = $('.roles:checked').map(function(_, el) {
        return $(el).val();
    }).get()
    
    if(numeroDocumento == ""){
        $('#campoNumeroDoc').text('Campo obligatorio')
        $('#campoNumeroDoc').css('display', 'inline')
        $('#numeroDocumento').focus()

    }else{
        var data = {
            tipoDocumento,
            numeroDocumento,
            nombres,
            apellidos,
            razonSocial,
            correo,
            telefono,
            direccion,
            roles,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'post',
            url: 'personas/crear',
            dataType: 'json',
            data,
            success: function(a){
                if(a.estado){
                    $('#modalCrearPersona').modal('hide')
                    location.replace(URI_CREAR)

                }else{
                    $('#campoNumeroDoc').text(a.mensaje)
                    $('#campoNumeroDoc').css('display', 'inline')
                    $('#numeroDocumento').focus()
                }
            },
            error: function(e){
                $('#campoNumeroDoc').text(e.message)
                $('#campoNumeroDoc').css('display', 'inline')
                $('#numeroDocumento').focus()
            }
        })
    }
})

// MODAL EDITAR
function editarPersona(id){
    $('#idPersonaA').val(id)
    $('input[type=checkbox]').each(function(){ 
        this.checked = false; 
    })
    $.ajax({
        type: 'post',
        url: 'personas/editar',
        dataType: 'json',
        data: {
            id,
            _token: $('input[name=_token]').val(),
        },
        complete: function(res){
            var data = res.responseJSON
            tipoDoc = data.tipo_documento
            numeroDoc = data.numero_documento
            nombres = data.nombres
            apellidos = data.apellidos
            razonSocial = data.razon_social
            correo = data.correo
            telefono = data.telefono
            direccion = data.direccion
            roles = data.roles

            $('#tipoDocumentoA').val(tipoDoc)
            $('#numeroDocumentoA').val(numeroDoc)
            $('#nombresPersonaA').val(nombres)
            $('#apellidosPersonaA').val(apellidos)
            $('#razonSocialA').val(razonSocial)
            $('#correoPersonaA').val(correo)
            $('#telefonoPersonaA').val(telefono)
            $('#direccionPersonaA').val(direccion)
            
            roles = JSON.parse(roles)
            for(var i=0; i<roles.length; i++){
                $('#rol-'+roles[i]+'-A').prop('checked', true)
            }

            $('#modalEditarPersona').modal({
                keyboard: false,
                backdrop: 'static'
            })
        }   
    })
}

// ACTUALIZAR 
$('#btnActualizarPersona').click(function(){
    var id = $('#idPersonaA').val()
    var tipoDocumento = $('#tipoDocumentoA').val()
    var numeroDocumento = $('#numeroDocumentoA').val()
    var nombres = $('#nombresPersonaA').val()
    var apellidos = $('#apellidosPersonaA').val()
    var razonSocial = $('#razonSocialA').val()
    var correo = $('#correoPersonaA').val()
    var telefono = $('#telefonoPersonaA').val()
    var direccion = $('#direccionPersonaA').val()
    var roles = $('.roles-act:checked').map(function(_, el) {
        return $(el).val();
    }).get()
    
    if(numeroDocumento == ""){
        $('#campoNumeroDocA').text('Campo obligatorio')
        $('#campoNumeroDocA').css('display', 'inline')
        $('#numeroDocumentoA').focus()

    }else{
        var data = {
            id,
            tipoDocumento,
            numeroDocumento,
            nombres,
            apellidos,
            razonSocial,
            correo,
            telefono,
            direccion,
            roles,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'patch',
            url: 'personas/actualizar',
            dataType: 'json',
            data,
            success: function(a){
                if(a.estado){
                    $('#modalEditarPersona').modal('hide')
                    location.replace(URI_ACTUALIZAR)

                }else{
                    $('#campoNumeroDocA').text(a.mensaje)
                    $('#campoNumeroDocA').css('display', 'inline')
                    $('#numeroDocumentoA').focus()
                }
            },
            error: function(e){
                $('#campoNumeroDocA').text(e.message)
                $('#campoNumeroDocA').css('display', 'inline')
                $('#numeroDocumentoA').focus()
            }
        })
    }
})

// MODAL ELIMINAR
function eliminarPersona(id){
    $('#idPersonaE').val(id)
    $('#modalEliminarPersona').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// ELIMINAR 
$('#btnEliminarPersona').click(function(){
    var id = $('#idPersonaE').val()
    var data = {
        id,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'delete',
        url: 'personas/eliminar',
        dataType: 'json',
        data,
        complete: function(a){
            $('#modalEliminarPersona').modal('hide')
            location.replace(URI_ACTUALIZAR)
        }   
    })
})

// Funciones onchange de Select
function segunTipo(){
    var tipo = $('#tipoDocumento').val()
    
    if(tipo == "DNI"){
        $('#razonSocial').attr('readonly', 'readonly')

    }else{
        $('#razonSocial').removeAttr('readonly')
    }
}

function segunTipoAct(){
    var tipo = $('#tipoDocumentoA').val()
    
    if(tipo == "DNI"){
        $('#razonSocialA').attr('readonly', 'readonly')

    }else{
        $('#razonSocialA').removeAttr('readonly')
    }
}