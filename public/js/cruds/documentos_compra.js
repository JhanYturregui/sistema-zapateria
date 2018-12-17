var origin = localStorage.getItem('url')
var pathname = window.location.pathname

URI = origin+pathname

var prodSeleccionados = []
var iniciado = true
var existePersona = false
var dataTallas = []

/************ DOCUMENTOS COMPRA **************/
// MODAL CREAR
function crearDocumentoCompra(){
    var fecha = new Date()
    var fechaActual = fecha.getDate()+"/"+(fecha.getMonth()+1)+"/"+fecha.getFullYear()
    $('#fechaDoc').val(fechaActual)
    
    $('#modalCrearDocumentoCompra').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// CREAR 
$('#btnRegistrarDocumentoCompra').click(function(){
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

            if(cantidad > 0){
                aux = {codigo, cantidad, descuento}
                productos.push(aux)
            }else{
                boolCant = false 
                break
            }
        }
    }

    var numeroDoc = $('#numeroDoc').val()
    var fechaDoc = $('#fechaDoc').val()
    var docProveedor = $('#docProveedor').val()
    var montoTotal = $('#montoTotal').val()

    if(numeroDoc.length == 0){
        $('#mensaje').text("Ingrese número de factura")
        $('#mensaje').css('display', 'inline')
        $('#numeroDoc').focus()

    }else if(montoTotal.length == 0){
        $('#mensaje').text("Ingrese el monto")
        $('#mensaje').css('display', 'inline')
        $('#montoTotal').focus()

    }else if(!hayProductos){
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
        var docProveedor = $('#docProveedor').val()
        var montoTotal = $('#montoTotal').val()

        var data = {
            numeroDoc,
            fechaDoc,
            docProveedor,
            productos,
            montoTotal,
            dataTallas,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'post',
            url: 'documentos_compra/crear',
            dataType: 'json',
            data,
            success: function(a){
                if(a.estado){
                    $('#modalCrearDocumentoCompra').modal('hide')
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
    if(codigo.length >= 4){
        var data = {
            codigo,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'post',
            url: 'productos/buscar_pro_compras',
            dataType: 'json',
            data,
            success: function(datos){
                $('#productosSeleccionar').html("")
                var tamaño = datos.length
                var fila = ""
                if(tamaño>0){
                    for(i=0; i<tamaño; i++){
                        codigo = datos[i].codigo
                        descripcion = datos[i].descripcion
                        if(descripcion == null){
                            descripcion = ""
                        }
                        cantidad = datos[i].cantidad
                        precio = datos[i].precio_venta

                        fila += '<tr>'+
                                    '<td>'+codigo+'</td>'+
                                    '<td>'+descripcion+'</td>'+
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
            var fila = ""

            if(iniciado){
                prodSeleccionados.push(codigo)
                iniciado = false
                fila += '<tr id="fila-'+codigo+'">'+
                            '<td>'+data.codigo+'</td>'+
                            '<td>'+data.marca+'</td>'+
                            '<td>'+data.color+'</td>'+
                            '<td>'+data.linea+'</td>'+
                            '<td><input id="cant-'+data.codigo+'" onkeyup="soloNumeros(event, '+"'"+data.codigo+"'"+')" onblur="calcularTotal('+"'"+data.codigo+"'"+', '+"'c'"+')" type="text" class="cantidad" value="0"/></td>'+
                            '<td><i class="tallas fas fa-pen" onclick="tallas('+"'"+codigo+"'"+')" title="Elegir tallas"></i></td>'+
                            '<td><i class="fas fa-times" onclick="eliminarProducto('+"'"+data.codigo+"'"+', '+"'"+data.precio_venta+"'"+')"></i></td>'+
                        '</tr>'

                $('#todosProductos').append(fila)
                $('#productosSeleccionados').css('display', 'inline')
                /* */
                $('#productosSeleccionar').html("")
                $('#productos').css('display', 'none')
                $('#codigoProd').val('')

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
                                '<td>'+data.color+'</td>'+
                                '<td>'+data.linea+'</td>'+
                                '<td><input id="cant-'+data.codigo+'" onkeyup="soloNumeros(event, '+"'"+data.codigo+"'"+')" onblur="calcularTotal('+"'"+data.codigo+"'"+', '+"'c'"+')" type="text" class="cantidad" value="0"/></td>'+
                                '<td><i class="tallas fas fa-pen" onclick="tallas('+"'"+codigo+"'"+')" title="Elegir tallas"></i></td>'+
                                '<td><i class="fas fa-times" onclick="eliminarProducto('+"'"+data.codigo+"'"+', '+"'"+data.precio_venta+"'"+')"></i></td>' 

                        row.append(fila)
                                
                    }else{

                        fila += '<tr id="fila-'+codigo+'">'+
                                    '<td>'+data.codigo+'</td>'+
                                    '<td>'+data.marca+'</td>'+
                                    '<td>'+data.color+'</td>'+
                                    '<td>'+data.linea+'</td>'+
                                    '<td><input id="cant-'+data.codigo+'" onkeyup="soloNumeros(event, '+"'"+data.codigo+"'"+')" onblur="calcularTotal('+"'"+data.codigo+"'"+', '+"'c'"+')" type="text" class="cantidad" value="0"/></td>'+
                                    '<td><i class="tallas fas fa-pen" onclick="tallas('+"'"+codigo+"'"+')" title="Elegir tallas"></i></td>'+
                                    '<td><i class="fas fa-times" onclick="eliminarProducto('+"'"+data.codigo+"'"+', '+"'"+data.precio_venta+"'"+')"></i></td>'+
                                '</tr>'
                        
                        $('#todosProductos').append(fila)
                    }
                    
                    $('#productosSeleccionados').css('display', 'inline')
                    $('#caja').css('display', 'inline')

                    /* */
                    $('#productosSeleccionar').html("")
                    $('#productos').css('display', 'none')
                    $('#codigoProd').val('')

                }else{
                    var cantidad = parseInt($('#cant-'+data.codigo).val())
                    if(isNaN(cantidad)){
                        cantidad = 0
                    }
                    cantidad = cantidad + 1
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

    $('#fila-'+codigo).html('')

    // Eliminar Tallas
    var eliminar = true
    dataTemp = []
    var a = 0
    for(j=0; j<dataTallas.length; j++){
        temp = {}
        $.each(dataTallas[j], function(k, item) {
            cod = item.codigo
            if(cod == codigo){
                tall = item.talla
                cant = item.cantidad
                temp = {codigo: cod, cantidad: cant, talla: tall}
                dataTemp.push(temp)
                a++
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
    //console.log(dataTallas)

    // Eliminar códigos
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
function buscarProveedor(docProveedor){
    var data = {
        docProveedor,
        _token: $('input[name=_token]').val()
    }
    $.ajax({
        type: 'post',
        url: 'proveedores/buscar',
        dataType: 'json',
        data,
        success: function(a){
            if(!a.estado){
                $('#datosProveedor').css('color', '#f44336')
                $('#datosProveedor').css('font-size', '0.9em')
                existePersona = false
                
            }else{
                $('#datosProveedor').css('color', '#2196f3')
                $('#datosProveedor').css('font-size', '0.9em')
                existePersona = true
            }
            $('#datosProveedor').val(a.mensaje)
        },
        error: function(e){
            console.log(e)
        }
    })
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
function anularDocumentoCompra(numero){
    $('#numeroDocumento').val(numero)
    $('#modalAnularDocumentoCompra').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// ANULAR 
$('#btnAnularDocumentoCompra').click(function(){
    var numeroDoc = $('#numeroDocumento').val()
    var data = {
        numeroDoc,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'delete',
        url: 'documentos_compra/anular',
        dataType: 'json',
        data,
        success: function(a){
            if(a.estado){
                $('#modalAnularDocumentoCompra').modal('hide')
                location.replace(URI)

            }else{
                $('#mensajeAnular').text(a.mensaje)
                $('#anularDoc').css('display', 'inline')
                $('#modalAnularDocumentoCompra').modal('hide')
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
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'post',
        url: 'tallas/listar',
        dataType: 'json',
        data,
        success: function(data){
            var cuerpo = '<div class="row">'
            var dato = ''
            for(i=0; i<data.length; i++){                
                dato += '<div class="col-md-3">'+
                            '<div class="custom-control custom-checkbox">'+
                                '<input type="checkbox" class="custom-control-input" id="tall-'+codigo+'-'+data[i].nombre+'" value='+data[i].nombre+' />'+
                                '<label class="custom-control-label" for="tall-'+codigo+'-'+data[i].nombre+'">'+data[i].nombre+'</label>'+
                                '<input class="form-control" id="cant-'+codigo+'-'+data[i].nombre+'" />'+
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
    $('#cant-'+codigo).val(contador)
    $('#modalTallas').modal('hide')
}