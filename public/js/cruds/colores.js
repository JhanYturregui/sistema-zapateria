APP_URI = "http://localhost:8000"
/************ COLORES **************/
// MODAL CREAR
function crearColor(){
    $('#modalCrearColor').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// CREAR 
$('#btnCrearColor').click(function(){
    var nombre = $('#nombreColor').val()
    
    if(nombre == ""){
        $('#campoColor').text("Campo obligatorio")
        $('#campoColor').css('display', 'inline')
        $('#nombreColor').focus()

    }else{
        var data = {
            nombre,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'post',
            url: 'colores/crear',
            dataType: 'json',
            data,
            complete: function(a){
                res = a.responseJSON
                if(res.estado){
                    $('#modalCrearColor').modal('hide')
                    location.replace(APP_URI+'/colores')
                    

                }else{
                    $('#campoColor').text(res.mensaje)
                    $('#campoColor').css('display', 'inline')
                }
            }   
        })
    }
})

// MODAL EDITAR
function editarColor(id){
    $.ajax({
        type: 'post',
        url: 'colores/editar',
        dataType: 'json',
        data: {
            id,
            _token: $('input[name=_token]').val(),
        },
        complete: function(res){
            var data = res.responseJSON
            var nombre = data.nombre

            $('#nombreColorA').val(nombre)
            $('#idColorA').val(id)

            $('#modalEditarColor').modal({
                keyboard: false,
                backdrop: 'static'
            })
        }   
    })
}

// ACTUALIZAR 
$('#btnActualizarColor').click(function(){
    var id = $('#idColorA').val()
    var nombre = $('#nombreColorA').val()
    
    if(nombre == ""){
        $('#campoColorA').css('display', 'inline')
        $('#nombreColorA').focus()

    }else{
        var data = {
            id,
            nombre,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'patch',
            url: 'colores/actualizar',
            dataType: 'json',
            data,
            complete: function(a){
                res = a.responseJSON
                if(res.estado){
                    $('#modalEditarColor').modal('hide')
                    location.replace(APP_URI+'/colores')

                }else{
                    $('#campoColorA').text(res.mensaje)
                    $('#campoColorA').css('display', 'inline')
                    $('#nombreColorA').focus()
                }
            }   
        })
    }
})

// MODAL ELIMINAR
function eliminarColor(id){
    $('#idColorE').val(id)
    $('#modalEliminarColor').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// ELIMINAR 
$('#btnEliminarColor').click(function(){
    var id = $('#idColorE').val()
    var data = {
        id,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'delete',
        url: 'colores/eliminar',
        dataType: 'json',
        data,
        complete: function(a){
            $('#modalEliminarColor').modal('hide')
            location.replace(APP_URI+'/colores')
        }   
    })
})