var origin = localStorage.getItem('url')
var pathname = window.location.pathname

URI = origin+pathname

/************ TIPOS USUARIO **************/
// MODAL CREAR
function crearTipoUsuario(){
    $('#modalCrearTipoUsuario').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// CREAR 
$('#btnCrearTipoUsuario').click(function(){
    var nombre = $('#nombreTipoUsuario').val()
    
    if(nombre == ""){
        $('#campoNombre').text('Campo obligatorio')
        $('#campoNombre').css('display', 'inline')
        $('#nombreTipoUsuario').focus()

    }else{
        var data = {
            nombre,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'post',
            url: 'tipos_usuario/crear',
            dataType: 'json',
            data,
            complete: function(a){
                res = a.responseJSON
                if(res.estado){
                    $('#modalCrearTipoUsuario').modal('hide')
                    location.replace(URI)

                }else{
                    $('#campoNombre').text(res.mensaje)
                    $('#campoNombre').css('display', 'inline')
                    $('#nombreTipoUsuario').focus()
                }
            }   
        })
    }
})

// MODAL EDITAR
function editarTipoUsuario(id){
    $.ajax({
        type: 'post',
        url: 'tipos_usuario/editar',
        dataType: 'json',
        data: {
            id,
            _token: $('input[name=_token]').val(),
        },
        complete: function(res){
            var data = res.responseJSON
            var nombre = data.nombre

            $('#nombreTipoUsuarioA').val(nombre)
            $('#idTipoUsuarioA').val(id)

            $('#modalEditarTipoUsuario').modal({
                keyboard: false,
                backdrop: 'static'
            })
        }   
    })
}

// ACTUALIZAR 
$('#btnActualizarTipoUsuario').click(function(){
    var id = $('#idTipoUsuarioA').val()
    var nombre = $('#nombreTipoUsuarioA').val()
    
    if(nombre == ""){
        $('#campoNombreA').css('display', 'inline')
        $('#nombreTipoUsuarioA').focus()

    }else{
        var data = {
            id,
            nombre,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'patch',
            url: 'tipos_usuario/actualizar',
            dataType: 'json',
            data,
            complete: function(a){
                $('#modalEditarTipoUsuario').modal('hide')
                location.replace(URI)
            }   
        })
    }
})

// MODAL ELIMINAR
function eliminarTipoUsuario(id){
    $('#idTipoUsuarioE').val(id)
    $('#modalEliminarTipoUsuario').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// ELIMINAR 
$('#btnEliminarTipoUsuario').click(function(){
    var id = $('#idTipoUsuarioE').val()
    var data = {
        id,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'delete',
        url: 'tipos_usuario/eliminar',
        dataType: 'json',
        data,
        complete: function(a){
            $('#modalEliminarTipoUsuario').modal('hide')
            location.replace(URI)
        }   
    })
})

// MODAL ACCESOS
function accesos(idTipoUsuario){
    $('input[type=checkbox]').each(function(){ 
        this.checked = false; 
    })
    $('#tipoUsuario').val(idTipoUsuario)
    var data = {
        idTipoUsuario,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'post',
        url: 'accesos/obtenerAccesos',
        dataType: 'json',
        data,
        complete: function(a){
            var datos = a.responseJSON
            $.each(datos, function(k, v){
                opcion = v.opcion
                $('#check-'+opcion).prop('checked', true)    
            })
        }   
    })
    $('#modalAccesos').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// ACCESOS
$('#btnAccesos').click(function(){

    var idTipoUsuario = $('#tipoUsuario').val()
    
    var seleccionados = $('input:checkbox:checked').map(function(_, el) {
        return $(el).val();
    }).get()

    var noSeleccionados = $('input:checkbox:not(:checked)').map(function(_, del) {
        return $(del).val();
    }).get()

    var data = {
        idTipoUsuario,
        seleccionados,
        noSeleccionados,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'post',
        url: 'accesos/configurarAccesos',
        dataType: 'json',
        data,
        complete: function(a){
            //console.log(a)
            $('#modalAccesos').modal('hide')
            location.replace(URI)
        }   
    })

})