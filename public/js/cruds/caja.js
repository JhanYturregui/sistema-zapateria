var origin = localStorage.getItem('url')
var pathname = window.location.pathname

URI = origin+pathname

// MODAL APERTURAR CAJA
function aperturarCaja(){
    var fecha = new Date()
    var fechaActual = fecha.getDate()+"/"+(fecha.getMonth()+1)+"/"+fecha.getFullYear()
    $('#fechaApertura').val(fechaActual)
    
    $('#modalAperturarCaja').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// BOTÓN APERTURAR CAJA
$('#btnAperturarCaja').click(function(){
    var numeroCaja = $('#numeroCaja').val()
    var montoApertura = $('#montoApertura').val()

    if(montoApertura.length == 0){
        $('#campoMontoApertura').text('Campo obligatorio')
        $('#campoMontoApertura').css('display', 'inline')
        $('#montoApertura').focus()

    }else{
        $('#campoMontoApertura').css('display', 'none')
        var data = {
            numeroCaja,
            montoApertura,
            _token: $('input[name=_token]').val()
        }
        $.ajax({
            type: 'post',
            url: 'caja/aperturar',
            dataType: 'json',
            data,
            success: function(a){
                if(a){
                    location.replace(URI)
                }
            },
            error: function(e){
                console.log(e)
            }
        })
    }

})

// MODAL CERRAR CAJA
function cerrarCaja(){
    var fecha = new Date()
    var fechaActual = fecha.getDate()+"/"+(fecha.getMonth()+1)+"/"+fecha.getFullYear()
    $('#fechaCierre').val(fechaActual)
    
    $('#modalCerrarCaja').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// BOTÓN CERRAR CAJA
$('#btnCerrarCaja').click(function(){
    var numeroCaja = $('#numeroCajaC').val()
    var montoCierre = $('#montoCierre').val()
    var montoReal = $('#montoReal').val()
    var comentario = $('#comentario').val()

    var data = {
        numeroCaja,
        montoCierre,
        montoReal,
        comentario,
        _token: $('input[name=_token]').val()
    }
    $.ajax({
        type: 'post',
        url: 'caja/cerrar',
        dataType: 'json',
        data,
        success: function(a){
            if(a){
                location.replace(URI)
            }
        },
        error: function(e){
            console.log(e)
        }
    })

})

// MODAL MOVIMIENTO CAJA
function crearMovimiento(){
    var fecha = new Date()
    var fechaActual = fecha.getDate()+"/"+(fecha.getMonth()+1)+"/"+fecha.getFullYear()
    $('#fechaMovimiento').val(fechaActual)
    
    $('#modalMovimientoCaja').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// MOVIMIENTOS CAJA
$(document).ready(function(){
    listarConceptos()
})

// LISTAR CONCEPTOS
function listarConceptos(){
    var tipo = $('#tipoMovimiento').val()
    var data = {
        tipo,
        _token: $('input[name=_token]').val()
    }
    $.ajax({
        type: 'post',
        url: 'caja/conceptos',
        dataType: 'json',
        data,
        success: function(a){
            $('#conceptoMovimiento').html('')
            for(i=0; i<a.length; i++){
                var opt = ''
                opt += '<option value="'+a[i].id+'">'+a[i].nombre+'</option>'
                $('#conceptoMovimiento').append(opt)
            }
        },
        error: function(e){
            console.log(e)
        }
    })
}

// REGISTRAR MOVIMIENTO CAJA
$('#btnRegistrarMovimiento').click(function(){
    var numero = $('#numeroMovimiento').val()
    var tipo = $('#tipoMovimiento').val()
    var concepto = $('#conceptoMovimiento').val()
    var persona = $('#persona').val()
    var monto = parseFloat($('#montoMovimiento').val())
    var comentario = $('#comentarioMovimiento').val()

    if(persona == ''){
        $('#campoPersona').css('display', 'inline')
        $('#persona').focus()

    }else if(monto == ''){
        $('#campoPersona').css('display', 'none')
        $('#campoMonto').css('display', 'inline')
        $('#montoMovimiento').focus()

    }else{
        $('#campoMonto').css('display', 'none')

        var data = {
                    numero, tipo, concepto, persona, monto, comentario, 
                    _token: $('input[name=_token]').val()
                   }

        $.ajax({
            type: 'post',
            url: 'caja/generar_movimiento',
            dataType: 'json',
            data,
            success: function(a){
                if(a.estado){
                    location.replace(URI)
                }
            },
            error: function(e){
                console.log(e)
            }
        })
        
    }

})

// MODAL ANULAR
function anularMovimiento(numero){
    $('#numeroMovimiento').val(numero)
    $('#modalAnularMovimiento').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// ANULAR MOVIMIENTO
$('#btnAnularMovimiento').click(function(){
    var numeroMov = $('#numeroMovimiento').val()
    var data = {
        numeroMov,
        _token: $('input[name=_token]').val()
    }
    $.ajax({
        type: 'post',
        url: '/caja/anular_movimiento',
        dataType: 'json',
        data,
        success: function(a){
            if(a.estado){
                location.replace(URI)
            }
        },
        error: function(e){

        }
    })
})

var cadEnteros = ''
function soloEnteros(e, id){
    tecla = e.keyCode
    if((tecla>47 && tecla<58) || (tecla>95 && tecla<106 || tecla == 8)){
        cadEnteros = $('#'+id).val()
    }
    $('#'+id).val(cadEnteros)
}

var cadDecimales = ''
function numerosDecimales(e, id){
    tecla = e.keyCode
    if((tecla>47 && tecla<58) || (tecla>95 && tecla<106) || tecla==190 || tecla==110 || tecla == 8){
        cadDecimales = $('#'+id).val()
    }
    $('#'+id).val(cadDecimales)
}