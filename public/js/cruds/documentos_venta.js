var origin = window.location.origin
var pathname = window.location.pathname

URI_ACTUALIZAR = origin+pathname
URI_CREAR = origin+'/documentos_venta'

var prodSeleccionados = []
var iniciado = true
var existePersona = false

/************ DOCUMENTOS ALMACÉN **************/
// MODAL CREAR
function crearDocumentoVenta(){
    var fecha = new Date()
    var fechaActual = fecha.getDate()+"/"+(fecha.getMonth()+1)+"/"+fecha.getFullYear()
    $('#fechaDoc').val(fechaActual)
    
    $('#modalCrearDocumentoVenta').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// CREAR 
$('#btnRegistrarDocumentoVenta').click(function(){
    var productos = []
    var boolCant = true
    var hayProductos = false

    if(prodSeleccionados.length > 0){
        hayProductos = true
        for(i=0; i<prodSeleccionados.length; i++){
            var aux = {}
            var codigo = prodSeleccionados[i]
            var cantidad = $('#cant-'+codigo).val()

            if(cantidad > 0){
                aux = {codigo, cantidad}
                productos.push(aux)
            }else{
                boolCant = false 
                break
            }
        }
    }

    if(!hayProductos){
        $('#mensaje').text("Debe seleccionar al menos un producto")
        $('#mensaje').css('display', 'inline')

    }else if(!existePersona){
        $('#mensaje').text("El número de documento ingresado no se encuentra registrado")
        $('#mensaje').css('display', 'inline')
        $('#docPersona').focus()

    }else if(!boolCant){
        $('#mensaje').text("La cantidad de un producto debe ser mayor a 0")
        $('#mensaje').css('display', 'inline')

    }else{
        $('#mensaje').css('display', 'none')
        var numeroDoc = $('#numeroDoc').val()
        var docPersona = $('#docPersona').val()
        var metodoPago = $('#metodoPago').val()
        var cantTotal = $('#cantTotal').val()

        var metodosPago = {}
        var montosPago = {}
        if(metodoPago == 'ambos'){
            cantEfectivo = $('#cantEfectivo').val()
            cantTarjeta = $('#cantTarjeta').val()
            efectivo = 'efectivo'
            tarjeta = 'tarjeta'
            metodosPago = {efectivo, tarjeta} 
            montosPago = {cantEfectivo, cantTarjeta}

        }else if(metodoPago == 'efectivo'){
            cantEfectivo = $('#cantEfectivo').val()
            efectivo = 'efectivo'
            metodosPago = {efectivo} 
            montosPago = {cantEfectivo}

        }else{
            cantTarjeta = $('#cantTarjeta').val()
            tarjeta = 'tarjeta'
            metodosPago = {tarjeta} 
            montosPago = {cantTarjeta}
        }

        var data = {
            numeroDoc,
            docPersona,
            metodosPago,
            montosPago,
            productos,
            cantTotal,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'post',
            url: 'documentos_venta/crear',
            dataType: 'json',
            data,
            success: function(a){
                if(a.estado){
                    $('#modalCrearDocumentoVenta').modal('hide')
                    location.replace(URI_CREAR)

                }else{
                    $('#mensaje').text(a.mensaje)
                    $('#mensaje').css('display', 'inline')
                }
            },
            error: function(e){
                console.log(e)
                //$('#mensaje').text(e.message)
                //$('#mensaje').css('display', 'inline')
            }
        })
    }
})

// BUSCAR PRODUCTOS
function buscarProductos(codigo){
    if(codigo != ""){
        var data = {
            codigo,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'post',
            url: 'productos/buscar_pro_ventas',
            dataType: 'json',
            data,
            success: function(data){
                $('#productosSeleccionar').html("")
                var tamaño = data.length
                var fila = ""

                if(tamaño>0){
                    for(i=0; i<tamaño; i++){
                        codigo = data[i].codigo
                        descripcion = data[i].descripcion
                        cantidad = data[i].cantidad
                        precio = data[i].precio_venta

                        fila += '<tr>'+
                                    '<td>'+codigo+'</td>'+
                                    '<td>'+descripcion+'</td>'+
                                    '<td>'+cantidad+'</td>'+
                                    '<td>'+precio+'</td>'+
                                    '<td><i class="fas fa-plus" onclick="agregarProducto('+"'"+codigo+"'"+')"></i></td>'+
                                '</tr>'
                    }
                    $('#productosSeleccionar').append(fila)
                    $('#productos').css('display', 'inline')
                }else{
                    $('#productosSeleccionar').html("")
                    $('#productos').css('display', 'none')
                }
            },
            error: function(e){
                console.log(e)
            }
        })
    }else{
        $('#productosSeleccionar').html("")
        $('#productos').css('display', 'none')
    }
}

var total = parseFloat($('#cantTotal').val())
// AGREGAR PRODUCTO
function agregarProducto(codigo){
    var data = {
        codigo,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'post',
        url: 'productos/buscar_pro',
        dataType: 'json',
        data,
        success: function(data){
            var fila = ""

            if(iniciado){
                prodSeleccionados.push(codigo)
                iniciado = false
                fila += '<tr id="fila-'+codigo+'">'+
                            '<td>'+data.codigo+'</td>'+
                            '<td>'+data.marca+'</td>'+
                            '<td>'+data.modelo+'</td>'+
                            '<td>'+data.color+'</td>'+
                            '<td>'+data.talla+'</td>'+
                            '<td>'+data.linea+'</td>'+
                            '<td id="precio-'+codigo+'">'+data.precio_venta+'</td>'+
                            '<td><input id="cant-'+data.codigo+'" onkeyup="soloNumeros(event, '+"'"+data.codigo+"'"+')" onblur="calcularTotal()" type="text" class="cantidad" value="1"/></td>'+
                            '<td><i class="fas fa-times" onclick="eliminarProducto('+"'"+data.codigo+"'"+', '+"'"+data.precio_venta+"'"+')"></i></td>'+
                        '</tr>'

                $('#todosProductos').append(fila)
                $('#productosSeleccionados').css('display', 'inline')
                $('#caja').css('display', 'inline')

                total = total + parseFloat(data.precio_venta)
                $('#cantTotal').val(total)
                $('#cantEfectivo').val(total)

            }else{
                agregar = true
                for(i=0; i<prodSeleccionados.length; i++){
                    if(prodSeleccionados[i] == data.codigo){
                        agregar = false
                    }
                }

                if(agregar){
                    prodSeleccionados.push(codigo)
                    var row = $('#fila-'+codigo)
                    var existeFila = $('#todosProductos').find(row).length
                    
                    if(existeFila>0){
                        fila += '<td>'+data.codigo+'</td>'+
                                '<td>'+data.marca+'</td>'+
                                '<td>'+data.modelo+'</td>'+
                                '<td>'+data.color+'</td>'+
                                '<td>'+data.talla+'</td>'+
                                '<td>'+data.linea+'</td>'+
                                '<td id="precio-'+codigo+'">'+data.precio_venta+'</td>'+
                                '<td><input id="cant-'+data.codigo+'" onkeyup="soloNumeros(event, '+"'"+data.codigo+"'"+')" onblur="calcularTotal()" type="text" class="cantidad" value="1"/></td>'+
                                '<td><i class="fas fa-times" onclick="eliminarProducto('+"'"+data.codigo+"'"+', '+"'"+data.precio_venta+"'"+')"></i></td>' 

                        row.append(fila)
                                
                    }else{

                        fila += '<tr id="fila-'+codigo+'">'+
                                    '<td>'+data.codigo+'</td>'+
                                    '<td>'+data.marca+'</td>'+
                                    '<td>'+data.modelo+'</td>'+
                                    '<td>'+data.color+'</td>'+
                                    '<td>'+data.talla+'</td>'+
                                    '<td>'+data.linea+'</td>'+
                                    '<td id="precio-'+codigo+'">'+data.precio_venta+'</td>'+
                                    '<td><input id="cant-'+data.codigo+'" onkeyup="soloNumeros(event, '+"'"+data.codigo+"'"+')" onblur="calcularTotal()" type="text" class="cantidad" value="1"/></td>'+
                                    '<td><i class="fas fa-times" onclick="eliminarProducto('+"'"+data.codigo+"'"+', '+"'"+data.precio_venta+"'"+')"></i></td>'+
                                '</tr>'
                        
                        $('#todosProductos').append(fila)
                    }
                    
                    $('#productosSeleccionados').css('display', 'inline')
                    $('#caja').css('display', 'inline')
                    
                    total = parseFloat($('#cantTotal').val())
                    total = total + parseFloat(data.precio_venta)
                    $('#cantTotal').val(total)
                    $('#cantEfectivo').val(total)    

                }else{
                    var cantidad = parseInt($('#cant-'+data.codigo).val())
                    if(isNaN(cantidad)){
                        cantidad = 0
                    }
                    var cantidad = cantidad + 1
                    total = parseFloat($('#cantTotal').val())
                    total = total + parseFloat(data.precio_venta)
                    total = total.toFixed(2) 
                    $('#cantTotal').val(total)
                    $('#cantEfectivo').val(total)
                    $('#cant-'+data.codigo).val(cantidad)
                }
            }
        },
        error: function(e){
            console.log(e)
        }
    })
}

// ELIMINAR PRODUCTO SELECCIONADO
function eliminarProducto(codigo, precio){
    var aux = $('#cant-'+codigo).val()
    var aux2 = aux*parseFloat(precio)
    var aux3 = parseFloat($('#cantTotal').val())
    var nuevoTotal = aux3-aux2
    $('#cantTotal').val(nuevoTotal)

    $('#fila-'+codigo).html('')
    var i = prodSeleccionados.indexOf(codigo)
    if(i !== -1){
        prodSeleccionados.splice(i, 1)
    }
    $('#mensaje').css('display', 'none')

    if(prodSeleccionados.length == 0){
        $('#productosSeleccionados').css('display', 'none')
        $('#caja').css('display', 'none')
    }

    
}


// Campo solo números
var cad = ""
function soloNumeros(e, codigo){
    tecla = e.keyCode
    if((tecla>47 && tecla<58) || (tecla>95 && tecla<106) || teclas == 8){
        cad = $('#cant-'+codigo).val()
    }
    $('#cant-'+codigo).val(cad)
}

// Solo números persona
var cadena = ""
function soloNumerosPersona(e){
    tecla = e.keyCode
    if((tecla>47 && tecla<58) || (tecla>95 && tecla<106 || tecla == 8)){
        cadena = $('#docPersona').val()
    }
    $('#docPersona').val(cadena)
}

// BUSCAR PERSONA
function buscarPersona(numeroDoc){
    var data = {
        numeroDoc,
        _token: $('input[name=_token]').val()
    }
    $.ajax({
        type: 'post',
        url: 'personas/buscar',
        dataType: 'json',
        data,
        success: function(a){
            if(!a.estado){
                $('#datosCliente').css('color', '#f44336')
                $('#datosCliente').css('font-size', '0.9em')
                existePersona = false
                
            }else{
                $('#datosCliente').css('color', '#2196f3')
                $('#datosCliente').css('font-size', '0.9em')
                existePersona = true
            }
            $('#datosCliente').val(a.mensaje)
        },
        error: function(e){
            console.log(e)
        }
    })
}

// MÉTODOS PAGO
function metodosPago(){
    var metodo = $('#metodoPago').val()
    switch (metodo) {
        case 'efectivo':
            $('#cantEfectivo').removeAttr('readonly')
            $('#cantTarjeta').attr('readonly', 'readonly')
            $('#dinero').removeAttr('readonly')
            $('#dinero').css('background', '#fff')
            $('#cantEfectivo').focus()
            $('#cantEfectivo').val($('#cantTotal').val())
            $('#cantTarjeta').val(0)
            break

        case 'tarjeta':
            $('#cantTarjeta').removeAttr('readonly')
            $('#cantEfectivo').attr('readonly', 'readonly')
            $('#dinero').attr('readonly', 'readonly')
            $('#dinero').css('background', '#ced4da')
            $('#cantTarjeta').focus()
            $('#cantTarjeta').val($('#cantTotal').val())
            $('#cantEfectivo').val(0)
            $('#dinero').val(0)
            $('#vuelto').val(0)
            break

        case 'ambos':
            $('#cantEfectivo').removeAttr('readonly')
            $('#cantTarjeta').removeAttr('readonly')
            $('#dinero').removeAttr('readonly')
            $('#dinero').css('background', '#fff')
            $('#cantEfectivo').focus()
            break
    }
}

// CALCULAR VUELTO
function calcularVuelto(){
    var dinero = $('#dinero').val()
    dinero = parseFloat(dinero)
    var metodo = $('#metodoPago').val()

    switch (metodo) {
        case 'efectivo':
            var efectivo = parseFloat($('#cantEfectivo').val())
            var vuelto = dinero - efectivo
            vuelto = vuelto.toFixed(2)
            $('#vuelto').val(vuelto)
            break

        case 'tarjeta':
            var tarjeta = $('#cantTarjeta').val()
            var vuelto = dinero - tarjeta
            vuelto = vuelto.toFixed(2)
            $('#vuelto').val(vuelto)
            break

        case 'ambos':
            var efectivo = $('#cantEfectivo').val()
            var tarjeta = $('#cantTarjeta').val()
            var vuelto = dinero - efectivo
            $('#vuelto').val(vuelto)
            break
    }
}

var cdn = ""
function soloNumerosEfectivo(e){
    tecla = e.keyCode
    if((tecla>47 && tecla<58) || (tecla>95 && tecla<106) || tecla==190 || tecla==110 || tecla == 8){
        cdn = $('#cantEfectivo').val()
    }
    $('#cantEfectivo').val(cdn)
}

var cdn2 = ""
function soloNumerosTarjeta(e){
    tecla = e.keyCode
    if((tecla>47 && tecla<58) || (tecla>95 && tecla<106) || tecla==190 || tecla==110 || tecla == 8){
        cdn2 = $('#cantTarjeta').val()
    }
    $('#cantTarjeta').val(cdn2)
}

var cdn3 = ""
function soloNumerosDinero(e){
    tecla = e.keyCode
    if((tecla>47 && tecla<58) || (tecla>95 && tecla<106) || tecla==190 || tecla==110 || tecla == 8){
        cdn3 = $('#dinero').val()
    }
    $('#dinero').val(cdn3)
}

function calcularTotal(){
    var nTotal = 0; 
    for(var i=0; i<prodSeleccionados.length; i++){
        var aux = 0
        var codigo = prodSeleccionados[i]
        var c = $('#cant-'+codigo).val()
        var p = parseInt($('#precio-'+codigo).text())
        aux = c*p
        nTotal = nTotal + aux
    }
    $('#cantTotal').val(nTotal)
    $('#cantEfectivo').val(nTotal)
}

function calcularResto(id){
    var metodoPago = $('#metodoPago').val()
    var cantTotal = $('#cantTotal').val()
    var cantidad = $('#'+id).val()

    if(metodoPago == 'ambos'){
        if(id == 'cantEfectivo'){
            var resto = parseFloat(cantTotal - cantidad).toFixed(2)
            $('#cantTarjeta').val(resto)

        }else{
            var resto = parseFloat(cantTotal - cantidad).toFixed(2)
            $('#cantEfectivo').val(resto)
        }
    }
}

function calcularVuelto2(){
    var dinero = $('#dinero').val()
    var efectivo = $('#cantEfectivo').val()
    var vuelto = parseFloat(dinero - efectivo).toFixed(2)
    $('#vuelto').val(vuelto)
}