var origin = localStorage.getItem('url')
var pathname = window.location.pathname

URI = origin+pathname

var prodSeleccionados = []
var iniciado = true
var existePersona = false
var dataTallas = []

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
$('#modalCrearDocumentoVenta').on('shown.bs.modal', function() {
  $('#codigoProd').focus();
})

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
            var descuento = $('#desc-'+codigo).val()
            var talla = $('#talla'+codigo).val()

            if(cantidad > 0){
                aux = {codigo, cantidad, talla, descuento}
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
            dataTallas,
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
                    location.replace(URI)

                }else{
                    $('#mensaje').text(a.mensaje)
                    $('#mensaje').css('display', 'inline')
                }
            },
            error: function(e){
                console.log(e)
            }
        })
    }
})

// BUSCAR PRODUCTOS
function buscarProductos(codigo){
    if(codigo.length > 3){
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
                        if(descripcion == null){
                            descripcion = ""
                        }
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


function agregarProd(event, codigo){
    tecla = event.keyCode
    if(tecla == 13){
        this.agregarProducto(codigo)
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
            var tallas = JSON.parse(data.tallas)
            var fila = ""

            if(iniciado){
                prodSeleccionados.push(codigo)
                iniciado = false
                fila += '<tr id="fila-'+codigo+'">'+
                            '<td>'+data.codigo+'</td>'+
                            '<td>'+data.descripcion+'</td>'+
                            '<td><i class="tallas fas fa-pen" onclick="tallas('+"'"+codigo+"'"+')" title="Elegir tallas"></i></td>'+
                            '<td id="precio-'+codigo+'">'+data.precio_venta+'</td>'+
                            '<td><input id="cant-'+data.codigo+'" readonly onkeyup="soloNumeros(event, '+"'"+data.codigo+"'"+')" onblur="calcularTotal('+"'"+data.codigo+"'"+', '+"'c'"+')" type="text" class="cantidad" value="0"/></td>'+
                            '<td><input class="cantidad" value="0" id="desc-'+data.codigo+'" onblur="calcularTotal('+"'"+data.codigo+"'"+', '+"'d'"+')" /></td>'+
                            '<td><input class="cantidad" value="0" id="final-'+data.codigo+'" onblur="calcularTotal('+"'"+data.codigo+"'"+', '+"'f'"+')" /></td>'+
                            '<td><i class="fas fa-times" onclick="eliminarProducto('+"'"+data.codigo+"'"+', '+"'"+data.precio_venta+"'"+')"></i></td>'+
                        '</tr>'

                $('#todosProductos').append(fila)
                $('#productosSeleccionados').css('display', 'inline')
                $('#caja').css('display', 'inline')

                $('#productosSeleccionar').html("")
                $('#productos').css("display", "none")
                $('#codigoProd').val("")
                
                //total = total + parseFloat(data.precio_venta)
                total = 0
                $('#cantTotal').val(total)
                $('#cantEfectivo').val(total)

                var prec = parseFloat($('#precio-'+data.codigo).text())
                var cant = $('#cant-'+data.codigo).val()
                var desc = $('#desc-'+data.codigo).val()
                var precFinal = (prec*cant - desc).toFixed(2)
                $('#final-'+data.codigo).val(precFinal)

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
                                '<td>'+data.descripcion+'</td>'+
                                '<td><i class="tallas fas fa-pen" onclick="tallas('+"'"+codigo+"'"+')" title="Elegir tallas"></i></td>'+
                                '<td id="precio-'+codigo+'">'+data.precio_venta+'</td>'+
                                '<td><input id="cant-'+data.codigo+'" readonly onkeyup="soloNumeros(event, '+"'"+data.codigo+"'"+')" onblur="calcularTotal('+"'"+data.codigo+"'"+', '+"'c'"+')" type="text" class="cantidad" value="0"/></td>'+
                                '<td><input class="cantidad" value="0" id="desc-'+data.codigo+'" onblur="calcularTotal('+"'"+data.codigo+"'"+', '+"'d'"+')" /></td>'+
                                '<td><input class="cantidad" value="0" id="final-' +data.codigo+'" onblur="calcularTotal('+"'"+data.codigo+"'"+', '+"'f'"+')" /></td>'+
                                '<td><i class="fas fa-times" onclick="eliminarProducto('+"'"+data.codigo+"'"+', '+"'"+data.precio_venta+"'"+')"></i></td>' 

                        row.append(fila)
                                
                    }else{

                        fila += '<tr id="fila-'+codigo+'">'+
                                    '<td>'+data.codigo+'</td>'+
                                    '<td>'+data.descripcion+'</td>'+
                                    '<td><i class="tallas fas fa-pen" onclick="tallas('+"'"+codigo+"'"+')" title="Elegir tallas"></i></td>'+
                                    '<td id="precio-'+codigo+'">'+data.precio_venta+'</td>'+
                                    '<td><input id="cant-'+data.codigo+'" readonly onkeyup="soloNumeros(event, '+"'"+data.codigo+"'"+')" onblur="calcularTotal('+"'"+data.codigo+"'"+', '+"'c'"+')" type="text" class="cantidad" value="0"/></td>'+
                                    '<td><input class="cantidad" value="0" id="desc-'+data.codigo+'" onblur="calcularTotal('+"'"+data.codigo+"'"+', '+"'d'"+')" /></td>'+
                                    '<td><input class="cantidad" value="0" id="final-' +data.codigo+'" onblur="calcularTotal('+"'"+data.codigo+"'"+', '+"'f'"+')" /></td>'+
                                    '<td><i class="fas fa-times" onclick="eliminarProducto('+"'"+data.codigo+"'"+', '+"'"+data.precio_venta+"'"+')"></i></td>'+
                                '</tr>'
                        
                        $('#todosProductos').append(fila)
                    }
                    
                    $('#productosSeleccionados').css('display', 'inline')
                    $('#caja').css('display', 'inline')
                    
                    $('#productosSeleccionar').html("")
                    $('#productos').css("display", "none")
                    $('#codigoProd').val("")

                    total = parseFloat($('#cantTotal').val())
                    //total = total + parseFloat(data.precio_venta)
                    $('#cantTotal').val(total)
                    $('#cantEfectivo').val(total)    

                    var prec = parseFloat($('#precio-'+data.codigo).text())
                    var cant = $('#cant-'+data.codigo).val()
                    var desc = $('#desc-'+data.codigo).val()
                    var precFinal = (prec*cant - desc).toFixed(2)
                    $('#final-'+data.codigo).val(precFinal)

                }else{
                    var cantidad = parseInt($('#cant-'+data.codigo).val())
                    if(isNaN(cantidad)){
                        cantidad = 0
                    }
                    var cantidad = cantidad + 1
                    //var precFinal = $('#final-'+data.codigo).val()
                    //console.log(precFinal) 

                    total = parseFloat($('#cantTotal').val())
                    total = total + parseFloat(data.precio_venta)
                    total = total.toFixed(2) 
                    $('#cantTotal').val(total)
                    $('#cantEfectivo').val(total)
                    $('#cant-'+data.codigo).val(cantidad)

                    var prec = parseFloat($('#precio-'+data.codigo).text())
                    var cant = $('#cant-'+data.codigo).val()
                    var desc = $('#desc-'+data.codigo).val()
                    var precFinal = (prec*cant - desc).toFixed(2)
                    $('#final-'+data.codigo).val(precFinal)
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
    $('#fila-'+codigo).html('')
    var i = prodSeleccionados.indexOf(codigo)
    if(i !== -1){
        prodSeleccionados.splice(i, 1)
    }
    $('#mensaje').css('display', 'none')

    var eliminar = true
    dataTemp = []
    for(j=0; j<dataTallas.length; j++){
        temp = {}
        $.each(dataTallas[j], function(k, item) {
            cod = item.codigo
            if(cod == codigo){
                tall = item.talla
                cant = item.cantidad
                temp = {codigo: cod, cantidad: cant, talla: tall}
                dataTemp.push(temp)
            }
        });
        if(dataTemp.length > 0){
            for(m=0; m<dataTemp.length; m++){
                aux4 = dataTemp[m].codigo
                aux2 = dataTemp[m].cantidad
                aux3 = dataTemp[m].talla
                if(aux4 != dataTallas[j][m].codigo || aux2 != dataTallas[j][m].cantidad || aux3 != dataTallas[j][m].talla){
                    eliminar = false
                }
            }
            if(eliminar){
                dataTallas.splice(dataTallas[j], 1)
            }
        }
    }
    console.log(dataTallas)

    if(prodSeleccionados.length == 0){
        $('#productosSeleccionados').css('display', 'none')
        $('#caja').css('display', 'none')
    }

    var total = 0;    
    for(i=0; i<prodSeleccionados.length; i++){
        var precFinal = parseFloat($('#final-'+prodSeleccionados[i]).val())
        total = total + precFinal
    }
    var nuevoTotal = total.toFixed(2)
    $('#cantTotal').val(nuevoTotal)
    $('#cantEfectivo').val(nuevoTotal)

}


// Campo solo números
var cad = ""
function soloNumeros(e, codigo){
    tecla = e.keyCode
    if((tecla>47 && tecla<58) || (tecla>95 && tecla<106) || tecla == 8){
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

function calcularTotal(codProd, param){
    // Total parcial
    var precio = parseFloat($('#precio-'+codProd).text())
    var cantidad = $('#cant-'+codProd).val()
    if(cantidad.length == 0){
        cantidad = 1
        $('#cant-'+codProd).val(cantidad)
    }
    var descuento = parseFloat($('#desc-'+codProd).val())
    if(descuento.length == 0){
        descuento = 0
        $('#desc-'+codProd).val(descuento)
    }
    var precioFinal = parseFloat($('#final-'+codProd).val())

    if(param == 'f'){
        descuento = precio*cantidad - precioFinal  
        descuento = parseFloat(descuento).toFixed(2)
        $('#desc-'+codProd).val(descuento)

    }else{
        precioFinal = precio*cantidad - descuento
        precioFinal = parseFloat(precioFinal).toFixed(2)
        $('#final-'+codProd).val(precioFinal)
    }

    if(precioFinal > precio*cantidad){
        precioFinal = precio*cantidad
        descuento = 0
        $('#final-'+codProd).val(precioFinal)
        $('#desc-'+codProd).val(descuento)
    }

    // Total de la venta
    var nTotal = 0; 
    for(var i=0; i<prodSeleccionados.length; i++){
        var aux = 0
        var codigo = prodSeleccionados[i]
        //var c = $('#cant-'+codigo).val()
        //var p = parseFloat($('#precio-'+codigo).text())
        //aux = c*p
        aux = parseFloat($('#final-'+codigo).val())
        nTotal = nTotal + aux
    }
    nTotal = parseFloat(nTotal).toFixed(2)
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


// MODAL ANULAR
function anularDocumentoVenta(numero){
    $('#numeroDocumento').val(numero)
    $('#modalAnularDocumentoVenta').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// ANULAR 
$('#btnAnularDocumentoVenta').click(function(){
    var numeroDoc = $('#numeroDocumento').val()
    var data = {
        numeroDoc,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'delete',
        url: 'documentos_venta/anular',
        dataType: 'json',
        data,
        success: function(a){
            if(a.estado){
                $('#modalAnularDocumentoVenta').modal('hide')
                location.replace(URI)

            }else{
                $('#mensajeAnular').text(a.mensaje)
                $('#anularDoc').css('display', 'inline')
                $('#modalAnularDocumentoVenta').modal('hide')
            }
        },
        error: function(e){
            $('#mensajeAnular').text(e.message)
            $('#anularDoc').css('display', 'inline')
        }   
    })
})

function tallas(codigo){
    //console.log(dataTallas)
    $('#cuerpoTallas').html('')
    $('#footerTallas').html('')
    var data = {
        codigo,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'post',
        url: 'tallas/listar_cod',
        dataType: 'json',
        data,
        success: function(data){
            var cuerpo = '<div class="row">'
            var dato = ''
            for(i=0; i<data.length; i++){                
                dato += '<div class="col-md-3">'+
                            '<div class="custom-control custom-checkbox">'+
                                '<input type="checkbox" class="custom-control-input" id="tall-'+codigo+'-'+data[i]+'" value='+data[i]+' />'+
                                '<label class="custom-control-label" for="tall-'+codigo+'-'+data[i]+'">'+data[i]+'</label>'+
                                '<input class="form-control" id="cant-'+codigo+'-'+data[i]+'" />'+
                            '</div>'+
                        '</div>'        
            }
            cuerpo += dato
            cuerpo += '</di>'
            $('#cuerpoTallas').append(cuerpo)

            for(j=0; j<dataTallas.length; j++){
                $.each(dataTallas[j], function(k, item) {
                    cod = item.codigo
                    tall = item.talla
                    cant = item.cantidad
                    if(cod == codigo){
                        $('#tall-'+cod+'-'+tall).prop('checked', true)
                        $('#cant-'+cod+'-'+tall).val(cant)
                    }
                });
            }

            var footer = ''
            footer += '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>'+
                      '<button type="button" class="btn btn-warning" onclick="guardarTallas('+"'"+codigo+"'"+')">Guardar</button>'
            $('#footerTallas').append(footer)          
        },
        error: function(e){
            
        }   
    })
    $('#tituloTallas').text('Producto: '+codigo)
    $('#modalTallas').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

function guardarTallas(codigo){
    var eliminar = true
    var dataTemp = []
    if(dataTallas.length > 0){
        for(j=0; j<dataTallas.length; j++){
            temp = {}
            $.each(dataTallas[j], function(k, item) {
                cod = item.codigo
                if(cod == codigo){
                    tall = item.talla
                    cant = item.cantidad
                    temp = {codigo: cod, cantidad: cant, talla: tall}
                    dataTemp.push(temp)
                }
            });
            if(dataTemp.length > 0){
                for(m=0; m<dataTemp.length; m++){
                    aux4 = dataTemp[m].codigo
                    aux2 = dataTemp[m].cantidad
                    aux3 = dataTemp[m].talla
                    if(aux4 != dataTallas[j][m].codigo || aux2 != dataTallas[j][m].cantidad || aux3 != dataTallas[j][m].talla){
                        eliminar = false
                    }
                }
                if(eliminar){
                    dataTallas.splice(dataTallas[j], 1)
                }
            }
        }
    }

    var dataAux = []
    var seleccionados = $('input:checkbox:checked').map(function(_, el) {
        return $(el).val();
    }).get()

    var contador = 0
    for(i=0; i<seleccionados.length; i++){
        aux = {}
        var cantidad = $('#cant-'+codigo+'-'+seleccionados[i]).val()
        var talla = seleccionados[i]

        aux = {codigo, cantidad, talla}
        dataAux.push(aux)
        contador = contador + parseInt(cantidad)
    }
    dataTallas.push(dataAux)
    //console.log(prodSeleccionados)
    $('#cant-'+codigo).val(contador)
    $('#desc-'+codigo).focus()
    $('#modalTallas').modal('hide')
}

// CAMBIAR TALLA
function cambiarTalla(numDoc, numCaja){
    $('#cuerpoCambiar').html('  ')
    var data = {
        numDoc,
        numCaja,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'post',
        url: 'documentos_venta/listar_vendidos',
        dataType: 'json',
        data,
        success: function(a){
            var dato = '<table class="table table-bordered table-sm" style="text-align:center;font-size:0.8em">'+
                            '<thead style="color: #fff">'+
                                '<tr class="bg-info">'+
                                    '<th>Código</th>'+
                                    '<th>Talla</th>'+
                                    //'<th>Cantidad</th>'+
                                    '<th>Nueva Talla</th>'+
                                '</tr>'+
                            '</thead>'+
                            '<input type="hidden" id="cantDatos" value="'+a.tallas.length+'" />'+
                            '<input type="hidden" id="numCajaC" value="'+numCaja+'" />'+
                            '<input type="hidden" id="numDocC" value="'+numDoc+'" />'+
                            '<tbody>'

            tallas = a.tallas
            cantidades = a.cantidades
            for(i=0; i<tallas.length; i++){
                aux = tallas[i].split('*')
                cod = aux[0]
                tall = aux[1]
                dato += 
                    '<tr>'+
                        '<td>'+cod+'</td>'+
                        '<td>'+tall+'</td>'+
                        //'<td>'+cantidades[i]+'</td>'+
                        '<td><input id="nt_'+i+'" type="text" value="" style="width: 50px; border-radius: 3px;" /></td>'+
                    '</tr>'
            }

            dato+=      '</tbody>'+
                    '</table>'

            $('#cuerpoCambiar').append(dato)
            $('#modalCambiarTalla').modal({
                keyboard: false,
                backdrop: 'static'
            })
        },
        error: function(e){

        }
    })
}

$('#btnCambiarTalla').click(function(){
    var nuevasTallas = []
    var numCaja = $('#numCajaC').val()
    var numDoc = $('#numDocC').val()
    var cant = $('#cantDatos').val()

    for(i=0; i<cant; i++){
        aux = $('#nt_'+i).val()
        nuevasTallas.push(aux)
    }
    var data = {
        nuevasTallas,
        numCaja,
        numDoc,
        _token: $('input[name=_token]').val(),
    }

    $.ajax({
        type: 'post',
        url: 'documentos_venta/cambiar_talla',
        data,
        dataType: 'json',
        success: function(a){
            if(a.estado){
                $('#modalCambiarTalla').modal('hide')
                location.replace(URI)
            }
        },
        error: function(e){
            console.log(e)
        }
    })
})