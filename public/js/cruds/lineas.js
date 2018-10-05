APP_URI = "http://localhost:8000"
/************ LÍNEAS **************/
// MODAL CREAR
function crearLinea(){
    $('#modalCrearLinea').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// CREAR 
$('#btnCrearLinea').click(function(){
    var nombre = $('#nombreLinea').val()
    
    if(nombre == ""){
        $('#campoLinea').text("Campo obligatorio")
        $('#campoLinea').css('display', 'inline')
        $('#nombreLinea').focus()

    }else{
        var data = {
            nombre,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'post',
            url: 'lineas/crear',
            dataType: 'json',
            data,
            complete: function(a){
                res = a.responseJSON
                if(res.estado){
                    $('#modalCrearLinea').modal('hide')
                    location.replace(APP_URI+'/lineas')
                    

                }else{
                    $('#campoLinea').text(res.mensaje)
                    $('#campoLinea').css('display', 'inline')
                }
            }   
        })
    }
})

// MODAL EDITAR
function editarLinea(id){
    $.ajax({
        type: 'post',
        url: 'lineas/editar',
        dataType: 'json',
        data: {
            id,
            _token: $('input[name=_token]').val(),
        },
        complete: function(res){
            var data = res.responseJSON
            var nombre = data.nombre

            $('#nombreLineaA').val(nombre)
            $('#idLineaA').val(id)

            $('#modalEditarLinea').modal({
                keyboard: false,
                backdrop: 'static'
            })
        }   
    })
}

// ACTUALIZAR 
$('#btnActualizarLinea').click(function(){
    var id = $('#idLineaA').val()
    var nombre = $('#nombreLineaA').val()
    
    if(nombre == ""){
        $('#campoLineaA').text('Campo obligatorio')
        $('#campoLineaA').css('display', 'inline')
        $('#nombreLineaA').focus()

    }else{
        var data = {
            id,
            nombre,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'patch',
            url: 'lineas/actualizar',
            dataType: 'json',
            data,
            complete: function(a){
                res = a.responseJSON
                if(res.estado){
                    $('#modalEditarLinea').modal('hide')
                    location.replace(APP_URI+'/lineas')

                }else{
                    $('#campoLineaA').text(res.mensaje)
                    $('#campoLineaA').css('display', 'inline')
                    $('#nombreLineaA').focus()
                }
            }   
        })
    }
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