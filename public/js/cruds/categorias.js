APP_URI = "http://localhost:8000"
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
    var icono = $('#iconoCategoria').val()
    var orden = $('#ordenCategoria').val()
    
    if(nombre == ""){
        $('#campoNombre').text('Campo obligatorio')
        $('#campoNombre').css('display', 'inline')
        $('#nombreCategoria').focus()

    }else{
        var data = {
            nombre,
            icono,
            orden,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'post',
            url: 'categorias/crear',
            dataType: 'json',
            data,
            complete: function(a){
                res = a.responseJSON
                if(res.estado){
                    $('#modalCrearCategoria').modal('hide')
                    location.replace(APP_URI+'/categorias')

                }else{
                    $('#campoNombre').text(res.mensaje)
                    $('#campoNombre').css('display', 'inline')
                }
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
            var icono = data.icono
            var orden = data.orden

            $('#nombreCategoriaA').val(nombre)
            $('#iconoCategoriaA').val(icono)
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
    var icono = $('#iconoCategoriaA').val()
    var orden = $('#ordenCategoriaA').val()
    
    if(nombre == ""){
        $('#campoNombreA').css('display', 'inline')
        $('#nombreCategoriaA').focus()

    }else{
        var data = {
            id,
            nombre,
            icono,
            orden,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'patch',
            url: 'categorias/actualizar',
            dataType: 'json',
            data,
            complete: function(a){
                $('#modalEditarCategoria').modal('hide')
                location.replace(APP_URI+'/categorias')
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
            location.replace(APP_URI+'/categorias')
        }   
    })
})