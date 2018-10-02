APP_URI = "http://localhost:8000"
/************ MODELOS **************/
// MODAL CREAR
function crearModelo(){
    $('#modalCrearModelo').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// CREAR 
$('#btnCrearModelo').click(function(){
    var nombre = $('#nombreModelo').val()
    
    if(nombre == ""){
        $('#campoModelo').text("Campo obligatorio")
        $('#campoModelo').css('display', 'inline')
        $('#nombreModelo').focus()

    }else{
        var data = {
            nombre,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'post',
            url: 'modelos/crear',
            dataType: 'json',
            data,
            complete: function(a){
                res = a.responseJSON
                if(res.estado){
                    $('#modalCrearModelo').modal('hide')
                    location.replace(APP_URI+'/modelos')
                    

                }else{
                    $('#campoModelo').text(res.mensaje)
                    $('#campoModelo').css('display', 'inline')
                }
            }   
        })
    }
})

// MODAL EDITAR
function editarModelo(id){
    $.ajax({
        type: 'post',
        url: 'modelos/editar',
        dataType: 'json',
        data: {
            id,
            _token: $('input[name=_token]').val(),
        },
        complete: function(res){
            var data = res.responseJSON
            var nombre = data.nombre

            $('#nombreModeloA').val(nombre)
            $('#idModeloA').val(id)

            $('#modalEditarModelo').modal({
                keyboard: false,
                backdrop: 'static'
            })
        }   
    })
}

// ACTUALIZAR 
$('#btnActualizarModelo').click(function(){
    var id = $('#idModeloA').val()
    var nombre = $('#nombreModeloA').val()
    
    if(nombre == ""){
        $('#campoModeloA').text('Campo obligatorio')
        $('#campoModeloA').css('display', 'inline')
        $('#nombreModeloA').focus()

    }else{
        var data = {
            id,
            nombre,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'patch',
            url: 'modelos/actualizar',
            dataType: 'json',
            data,
            complete: function(a){
                res = a.responseJSON
                if(res.estado){
                    $('#modalEditarModelo').modal('hide')
                    location.replace(APP_URI+'/modelos')

                }else{
                    $('#campoModeloA').text(res.mensaje)
                    $('#campoModeloA').css('display', 'inline')
                    $('#nombreModeloA').focus()
                }
            }   
        })
    }
})

// MODAL ELIMINAR
function eliminarModelo(id){
    $('#idModeloE').val(id)
    $('#modalEliminarModelo').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// ELIMINAR 
$('#btnEliminarModelo').click(function(){
    var id = $('#idModeloE').val()
    var data = {
        id,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'delete',
        url: 'modelos/eliminar',
        dataType: 'json',
        data,
        complete: function(a){
            $('#modalEliminarModelo').modal('hide')
            location.replace(APP_URI+'/modelos')
        }   
    })
})