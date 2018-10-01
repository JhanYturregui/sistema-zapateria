APP_URI = "http://localhost:8000"
/************ OPCIONES **************/
// MODAL CREAR
function crearOpcion(){
    $('#modalCrearOpcion').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// CREAR 
$('#btnCrearOpcion').click(function(){
    var nombre = $('#nombreOpcion').val()
    var categoria = $('#categoria').val()
    var orden = $('#ordenOpcion').val()
    var icono = $('#iconoOpcion').val()
    
    if(nombre == ""){
        $('#campoNombre').text('Campo obligatorio')
        $('#campoNombre').css('display', 'inline')
        $('#nombreOpcion').focus()

    }else{
        var data = {
            nombre,
            categoria,            
            orden,
            icono,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'post',
            url: 'opciones/crear',
            dataType: 'json',
            data,
            complete: function(a){
                res = a.responseJSON
                if(res.estado){
                    $('#modalCrearOpcion').modal('hide')
                    location.replace(APP_URI+'/opciones')

                }else{
                    $('#campoNombre').text(res.mensaje)
                    $('#campoNombre').css('display', 'inline')
                }
            }   
        })
    }
})

// MODAL EDITAR
function editarOpcion(id){
    $.ajax({
        type: 'post',
        url: 'opciones/editar',
        dataType: 'json',
        data: {
            id,
            _token: $('input[name=_token]').val(),
        },
        complete: function(res){
            var data = res.responseJSON
            var nombre = data.nombre
            var categoria = data.categoria
            var orden = data.orden
            var icono = data.icono

            $('#nombreOpcionA').val(nombre)
            $('#categoriaA').val(categoria)
            $('#ordenOpcionA').val(orden)
            $('#iconoOpcionA').val(icono)
            $('#idOpcionA').val(id)

            $('#modalEditarOpcion').modal({
                keyboard: false,
                backdrop: 'static'
            })
        }   
    })
}

// ACTUALIZAR 
$('#btnActualizarOpcion').click(function(){
    var id = $('#idOpcionA').val()
    var nombre = $('#nombreOpcionA').val()
    var categoria = $('#categoriaA').val()
    var orden = $('#ordenOpcionA').val()
    var icono = $('#iconoOpcionA').val()
    
    if(nombre == ""){
        $('#campoNombreA').css('display', 'inline')
        $('#nombreOpcionA').focus()

    }else{
        var data = {
            id,
            nombre,
            categoria,
            orden,
            icono,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'patch',
            url: 'opciones/actualizar',
            dataType: 'json',
            data,
            complete: function(a){
                $('#modalEditarOpcion').modal('hide')
                location.replace(APP_URI+'/opciones')
            }   
        })
    }
})

// MODAL ELIMINAR
function eliminarOpcion(id){
    $('#idOpcionE').val(id)
    $('#modalEliminarOpcion').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// ELIMINAR 
$('#btnEliminarOpcion').click(function(){
    var id = $('#idOpcionE').val()
    var data = {
        id,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'delete',
        url: 'opciones/eliminar',
        dataType: 'json',
        data,
        complete: function(a){
            $('#modalEliminarOpcion').modal('hide')
            location.replace(APP_URI+'/opciones')
        }   
    })
})