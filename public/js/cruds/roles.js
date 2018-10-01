APP_URI = "http://localhost:8000"
/************ ROLES **************/
// MODAL CREAR
function crearRol(){
    $('#modalCrearRol').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// CREAR 
$('#btnCrearRol').click(function(){
    var nombre = $('#nombreRol').val()
    
    if(nombre == ""){
        $('#campoNombre').text("Campo obligatorio")
        $('#campoNombre').css('display', 'inline')
        $('#nombreRol').focus()

    }else{
        var data = {
            nombre,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'post',
            url: 'roles/crear',
            dataType: 'json',
            data,
            complete: function(a){
                res = a.responseJSON
                if(res.estado){
                    $('#modalCrearRol').modal('hide')
                    location.replace(APP_URI+'/roles')

                }else{
                    $('#campoNombre').text(res.mensaje)
                    $('#campoNombre').css('display', 'inline')
                }
            }   
        })
    }
})

// MODAL EDITAR
function editarRol(id){
    $.ajax({
        type: 'post',
        url: 'roles/editar',
        dataType: 'json',
        data: {
            id,
            _token: $('input[name=_token]').val(),
        },
        complete: function(res){
            var data = res.responseJSON
            var nombre = data.nombre

            $('#nombreRolA').val(nombre)
            $('#idRolA').val(id)

            $('#modalEditarRol').modal({
                keyboard: false,
                backdrop: 'static'
            })
        }   
    })
}

// ACTUALIZAR 
$('#btnActualizarRol').click(function(){
    var id = $('#idRolA').val()
    var nombre = $('#nombreRolA').val()
    
    if(nombre == ""){
        $('#campoNombreA').css('display', 'inline')
        $('#nombreRolA').focus()

    }else{
        var data = {
            id,
            nombre,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'patch',
            url: 'roles/actualizar',
            dataType: 'json',
            data,
            complete: function(a){
                $('#modalEditarRol').modal('hide')
                location.replace(APP_URI+'/roles')
            }   
        })
    }
})

// MODAL ELIMINAR
function eliminarRol(id){
    $('#idRolE').val(id)
    $('#modalEliminarRol').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// ELIMINAR 
$('#btnEliminarRol').click(function(){
    var id = $('#idRolE').val()
    var data = {
        id,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'delete',
        url: 'roles/eliminar',
        dataType: 'json',
        data,
        complete: function(a){
            $('#modalEliminarRol').modal('hide')
            location.replace(APP_URI+'/roles')
        }   
    })
})