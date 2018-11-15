var origin = localStorage.getItem('url')
var pathname = window.location.pathname

URI = origin+pathname

/************ TALLAS **************/
// MODAL CREAR
function crearTalla(){
    $('#modalCrearTalla').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// CREAR 
$('#btnCrearTalla').click(function(){
    var nombre = $('#nombreTalla').val()
    
    if(nombre == ""){
        $('#campoTalla').text("Campo obligatorio")
        $('#campoTalla').css('display', 'inline')
        $('#nombreTalla').focus()

    }else{
        var data = {
            nombre,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'post',
            url: 'tallas/crear',
            dataType: 'json',
            data,
            success: function(a){
                if(a.estado){
                    $('#modalCrearTalla').modal('hide')
                    location.replace(URI)
                    
                }else{
                    $('#campoTalla').text(a.mensaje)
                    $('#campoTalla').css('display', 'inline')
                    $('#nombreTalla').focus()
                }
            },
            error: function(e){
                $('#campoTalla').text(e.message)
                $('#campoTalla').css('display', 'inline')
                $('#nombreTalla').focus()
            }   
        })
    }
})

// MODAL EDITAR
function editarTalla(id){
    $.ajax({
        type: 'post',
        url: 'tallas/editar',
        dataType: 'json',
        data: {
            id,
            _token: $('input[name=_token]').val(),
        },
        complete: function(res){
            var data = res.responseJSON
            var nombre = data.nombre

            $('#nombreTallaA').val(nombre)
            $('#idTallaA').val(id)

            $('#modalEditarTalla').modal({
                keyboard: false,
                backdrop: 'static'
            })
        }   
    })
}

// ACTUALIZAR 
$('#btnActualizarTalla').click(function(){
    var id = $('#idTallaA').val()
    var nombre = $('#nombreTallaA').val()
    
    if(nombre == ""){
        $('#campoTallaA').css('display', 'inline')
        $('#nombreTallaA').focus()

    }else{
        var data = {
            id,
            nombre,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'patch',
            url: 'tallas/actualizar',
            dataType: 'json',
            data,
            success: function(a){
                if(a.estado){
                    $('#modalEditarTalla').modal('hide')
                    location.replace(URI)

                }else{
                    $('#campoTallaA').text(res.mensaje)
                    $('#campoTallaA').css('display', 'inline')
                    $('#nombreTallaA').focus()
                }
            },
            error: function(e){
                $('#campoTallaA').text(a.message)
                $('#campoTallaA').css('display', 'inline')
                $('#nombreTallaA').focus()
            }   
        })
    }
})

// MODAL ELIMINAR
function eliminarTalla(id){
    $('#idTallaE').val(id)
    $('#modalEliminarTalla').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// ELIMINAR 
$('#btnEliminarTalla').click(function(){
    var id = $('#idTallaE').val()
    var data = {
        id,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'delete',
        url: 'tallas/eliminar',
        dataType: 'json',
        data,
        complete: function(a){
            $('#modalEliminarTalla').modal('hide')
            location.replace(URI)
        }   
    })
})