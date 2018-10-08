var origin = window.location.origin
var pathname = window.location.pathname

URI_ACTUALIZAR = origin+pathname
URI_CREAR = origin+'/categorias'

/************ CATEGORÍAS **************/
// MODAL CREAR
function crearCategoria(){
    $('#modalCrearCategoria').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// CREAR 
$('#btnCrearCategoria').click(function(){
    var nombre = $('#nombreCategoria').val()
    //var icono = $('#iconoCategoria').val()
    var orden = $('#ordenCategoria').val()
    
    if(nombre == ""){
        $('#campoNombre').text('Campo obligatorio')
        $('#campoNombre').css('display', 'inline')
        $('#nombreCategoria').focus()

    }else{
        var data = {
            nombre,
            orden,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'post',
            url: 'categorias/crear',
            dataType: 'json',
            data,
            success: function(a){
                if(a.estado){
                    $('#modalCrearCategoria').modal('hide')
                    location.replace(URI_CREAR)

                }else{
                    $('#campoNombre').text(a.mensaje)
                    $('#campoNombre').css('display', 'inline')
                    $('#nombreCategoria').focus()
                }
            },
            error: function(e){
                $('#campoNombre').text(e)
                $('#campoNombre').css('display', 'inline')
            }   
        })
    }
})

// MODAL EDITAR
function editarCategoria(id){
    $.ajax({
        type: 'post',
        url: 'categorias/editar',
        dataType: 'json',
        data: {
            id,
            _token: $('input[name=_token]').val(),
        },
        complete: function(res){
            var data = res.responseJSON
            var nombre = data.nombre
            //var icono = data.icono
            var orden = data.orden

            $('#nombreCategoriaA').val(nombre)
            //$('#iconoCategoriaA').val(icono)
            $('#ordenCategoriaA').val(orden)
            $('#idCategoriaA').val(id)

            $('#modalEditarCategoria').modal({
                keyboard: false,
                backdrop: 'static'
            })
        }   
    })
}

// ACTUALIZAR 
$('#btnActualizarCategoria').click(function(){
    var id = $('#idCategoriaA').val()
    var nombre = $('#nombreCategoriaA').val()
    //var icono = $('#iconoCategoriaA').val()
    var orden = $('#ordenCategoriaA').val()
    
    if(nombre == ""){
        $('#campoNombreA').css('display', 'inline')
        $('#nombreCategoriaA').focus()

    }else{
        var data = {
            id,
            nombre,
            orden,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'patch',
            url: 'categorias/actualizar',
            dataType: 'json',
            data,
            success: function(a){
                if(a.estado){
                    $('#modalEditarCategoria').modal('hide')
                    location.replace(URI_ACTUALIZAR)

                }else{
                    $('#campoNombreA').text(a.mensaje)
                    $('#campoNombreA').css('display', 'inline')
                    $('#nombreCategoriaA').focus()
                }
            },
            error: function(e){
                $('#campoNombreA').text(e)
                $('#campoNombreA').css('display', 'inline')
                $('#nombreCategoriaA').focus()
            }  
        })
    }
})

// MODAL ELIMINAR
function eliminarCategoria(id){
    $('#idCategoriaE').val(id)
    $('#modalEliminarCategoria').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// ELIMINAR 
$('#btnEliminarCategoria').click(function(){
    var id = $('#idCategoriaE').val()
    var data = {
        id,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'delete',
        url: 'categorias/eliminar',
        dataType: 'json',
        data,
        complete: function(a){
            $('#modalEliminarCategoria').modal('hide')
            location.replace(URI_CREAR)
        }   
    })
})