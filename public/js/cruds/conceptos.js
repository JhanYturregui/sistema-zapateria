var origin = localStorage.getItem('url')
var pathname = window.location.pathname

URI_ACTUALIZAR = origin+pathname
URI_CREAR = origin+'/conceptos'

/************ CONCEPTOS **************/
// MODAL CREAR
function crearConcepto(){
    $('#modalCrearConcepto').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// CREAR 
$('#btnCrearConcepto').click(function(){
    var nombre = $('#nombreConcepto').val()
    var tipo = $('#tipoConcepto').val()
    
    if(nombre == ""){
        $('#campoNombre').text('Campo obligatorio')
        $('#campoNombre').css('display', 'inline')
        $('#nombreConcepto').focus()

    }else{
        var data = {
            nombre,
            tipo,            
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'post',
            url: 'conceptos/crear',
            dataType: 'json',
            data,
            success: function(a){
                if(a.estado){
                    $('#modalCrearConcepto').modal('hide')
                    location.replace(URI_CREAR)

                }else{
                    $('#campoNombre').text(a.mensaje)
                    $('#campoNombre').css('display', 'inline')
                    $('#nombreConcepto').focus()
                }
            },
            error: function(e){
                $('#campoNombre').text(e.message)
                $('#campoNombre').css('display', 'inline')
                $('#nombreConcepto').focus()
            }   
        })
    }
})

// MODAL EDITAR
function editarConcepto(id){
    $.ajax({
        type: 'post',
        url: 'conceptos/editar',
        dataType: 'json',
        data: {
            id,
            _token: $('input[name=_token]').val(),
        },
        complete: function(res){
            var data = res.responseJSON
            var nombre = data.nombre
            var tipo = data.tipo

            $('#nombreConceptoA').val(nombre)
            $('#tipoConceptoA').val(tipo)
            $('#idConceptoA').val(id)

            $('#modalEditarConcepto').modal({
                keyboard: false,
                backdrop: 'static'
            })
        }   
    })
}

// ACTUALIZAR 
$('#btnActualizarConcepto').click(function(){
    var id = $('#idConceptoA').val()
    var nombre = $('#nombreConceptoA').val()
    var tipo = $('#tipoConceptoA').val()
    
    if(nombre == ""){
        $('#campoNombreA').css('display', 'inline')
        $('#nombreConceptoA').focus()

    }else{
        var data = {
            id,
            nombre,
            tipo,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'patch',
            url: 'conceptos/actualizar',
            dataType: 'json',
            data,
            success: function(a){
                if(a.estado){
                    $('#modalEditarConcepto').modal('hide')
                    location.replace(URI_ACTUALIZAR)

                }else{
                    $('#campoNombreA').text(a.mensaje)
                    $('#campoNombreA').css('display', 'inline')
                    $('#nombreConceptoA').focus()
                }
            },
            error: function(e){
                $('#campoNombreA').text(e.message)
                $('#campoNombreA').css('display', 'inline')
                $('#nombreConceptoA').focus()
            }   
        })
    }
})

// MODAL ELIMINAR
function eliminarConcepto(id){
    $('#idConceptoE').val(id)
    $('#modalEliminarConcepto').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// ELIMINAR 
$('#btnEliminarConcepto').click(function(){
    var id = $('#idConceptoE').val()
    var data = {
        id,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'delete',
        url: 'conceptos/eliminar',
        dataType: 'json',
        data,
        complete: function(a){
            $('#modalEliminarConcepto').modal('hide')
            location.replace(URI_ACTUALIZAR)
        }   
    })
})