var origin = localStorage.getItem('url')
var pathname = window.location.pathname

URI = origin+pathname

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
    //var icono = $('#iconoOpcion').val()
    
    if(nombre == ""){
        $('#campoNombre').text('Campo obligatorio')
        $('#campoNombre').css('display', 'inline')
        $('#nombreOpcion').focus()

    }else{
        var data = {
            nombre,
            categoria,            
            orden,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'post',
            url: 'opciones/crear',
            dataType: 'json',
            data,
            success: function(a){
                if(a.estado){
                    $('#modalCrearOpcion').modal('hide')
                    location.replace(URI)

                }else{
                    $('#campoNombre').text(a.mensaje)
                    $('#campoNombre').css('display', 'inline')
                    $('#nombreOpcion').focus()
                }
            },
            error: function(e){
                $('#campoNombre').text(e.message)
                $('#campoNombre').css('display', 'inline')
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
            //var icono = data.icono

            $('#nombreOpcionA').val(nombre)
            $('#categoriaA').val(categoria)
            $('#ordenOpcionA').val(orden)
            //$('#iconoOpcionA').val(icono)
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
    //var icono = $('#iconoOpcionA').val()
    
    if(nombre == ""){
        $('#campoNombreA').css('display', 'inline')
        $('#nombreOpcionA').focus()

    }else{
        var data = {
            id,
            nombre,
            categoria,
            orden,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'patch',
            url: 'opciones/actualizar',
            dataType: 'json',
            data,
            success: function(a){
                if(a.estado){
                    $('#modalEditarOpcion').modal('hide')
                    //location.replace(URI)
                    location.reload()

                }else{
                    $('#campoNombreA').text(a.mensaje)
                    $('#campoNombreA').css('display', 'inline')
                    $('#nombreOpcionA').focus()
                }
            },
            error: function(e){
                $('#campoNombreA').text(e.message)
                $('#campoNombreA').css('display', 'inline')
                $('#nombreOpcionA').focus()
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
            location.replace(URI)
        }   
    })
})