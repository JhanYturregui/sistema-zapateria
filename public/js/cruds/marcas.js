APP_URI = "http://localhost:8000"
/************ MARCAS **************/
// MODAL CREAR
function crearMarca(){
    $('#modalCrearMarca').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// CREAR 
$('#btnCrearMarca').click(function(){
    var nombre = $('#nombreMarca').val()
    
    if(nombre == ""){
        $('#campoMarca').text("Campo obligatorio")
        $('#campoMarca').css('display', 'inline')
        $('#nombreMarca').focus()

    }else{
        var data = {
            nombre,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'post',
            url: 'marcas/crear',
            dataType: 'json',
            data,
            complete: function(a){
                res = a.responseJSON
                if(res.estado){
                    $('#modalCrearMarca').modal('hide')
                    location.replace(APP_URI+'/marcas')
                    

                }else{
                    $('#campoMarca').text(res.mensaje)
                    $('#campoMarca').css('display', 'inline')
                }
            }   
        })
    }
})

// MODAL EDITAR
function editarMarca(id){
    $.ajax({
        type: 'post',
        url: 'marcas/editar',
        dataType: 'json',
        data: {
            id,
            _token: $('input[name=_token]').val(),
        },
        complete: function(res){
            var data = res.responseJSON
            var nombre = data.nombre

            $('#nombreMarcaA').val(nombre)
            $('#idMarcaA').val(id)

            $('#modalEditarMarca').modal({
                keyboard: false,
                backdrop: 'static'
            })
        }   
    })
}

// ACTUALIZAR 
$('#btnActualizarMarca').click(function(){
    var id = $('#idMarcaA').val()
    var nombre = $('#nombreMarcaA').val()
    
    if(nombre == ""){
        $('#campoMarcaA').css('display', 'inline')
        $('#nombreMarcaA').focus()

    }else{
        var data = {
            id,
            nombre,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'patch',
            url: 'marcas/actualizar',
            dataType: 'json',
            data,
            complete: function(a){
                res = a.responseJSON
                if(res.estado){
                    $('#modalEditarMarca').modal('hide')
                    location.replace(APP_URI+'/marcas')

                }else{
                    $('#campoMarcaA').text(res.mensaje)
                    $('#campoMarcaA').css('display', 'inline')
                    $('#nombreMarcaA').focus()
                }
            }   
        })
    }
})

// MODAL ELIMINAR
function eliminarMarca(id){
    $('#idMarcaE').val(id)
    $('#modalEliminarMarca').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// ELIMINAR 
$('#btnEliminarMarca').click(function(){
    var id = $('#idMarcaE').val()
    var data = {
        id,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'delete',
        url: 'marcas/eliminar',
        dataType: 'json',
        data,
        complete: function(a){
            $('#modalEliminarMarca').modal('hide')
            location.replace(APP_URI+'/marcas')
        }   
    })
})