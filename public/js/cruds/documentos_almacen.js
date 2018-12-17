var origin = localStorage.getItem('url')
var pathname = window.location.pathname

URI = origin+pathname

var prodSeleccionados = []
var iniciado = true
var dataTallas = []

/************ DOCUMENTOS ALMACÉN **************/
// MODAL CREAR
function crearDocumentoAlmacen(){
    var fecha = new Date()
    var fechaActual = fecha.getDate()+"/"+(fecha.getMonth()+1)+"/"+fecha.getFullYear()
    $('#fechaDoc').val(fechaActual)
    
    $('#modalCrearDocumentoAlmacen').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// CREAR 
$('#btnRegistrarDocumentoAlmacen').click(function(){
    var productos = []
    var boolCant = true
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

    if(!boolCant){
        $('#mensaje').text("La cantidad de un producto debe ser mayor a 0")
        $('#mensaje').css('display', 'inline')

    }else if(productos.length == 0){
        $('#mensaje').text("Debe seleccionar algún producto")
        $('#mensaje').css('display', 'inline')

    }else{
        var numeroDoc = $('#numeroDoc').val()
        var sucursal = $('#sucursales').val()
        var comentario = $('#comentario').val()

        var data = {
            numeroDoc,
            comentario,
            productos,
            sucursal,
            dataTallas,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'post',
            url: 'documentos_almacen/crear',
            dataType: 'json',
            data,
            success: function(a){
                if(a.estado){
                    $('#modalCrearDocumentoAlmacen').modal('hide')
                    location.replace(URI)

                }else{
                    $('#mensaje').text(a.mensaje)
                    $('#mensaje').css('display', 'inline')
                }
            },
            error: function(e){
                $('#mensaje').text(e.message)
                $('#mensaje').css('display', 'inline')
            }
        })
    }
})

function aceptarDocumentoAlmacen(numeroDoc){
    var data = {
        numeroDoc,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'post',
        url: 'documentos_almacen/aceptar',
        dataType: 'json',
        data,
        success: function(a){
            location.replace(URI)
        },
        error: function(e){

        }
    })
}

// BUSCAR PRODUCTOS
function buscarProductos(codigo){
    if(codigo.length >= 4){
        var data = {
            codigo,
            _token: $('input[name=_token]').val(),
        }
        $.ajax({
            type: 'post',
            url: 'productos/buscar',
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

                        fila += '<tr>'+
                                    '<td>'+codigo+'</td>'+
                                    '<td>'+descripcion+'</td>'+
                                    '<td>'+cantidad+'</td>'+
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
                iniciado = false
                fila += '<tr id="fila-'+codigo+'">'+
                            '<td>'+data.codigo+'</td>'+
                            '<td>'+data.descripcion+'</td>'+
                            //'<td>'+data.modelo+'</td>'+
                            //'<td>'+data.color+'</td>'+
                            //'<td>'+data.talla+'</td>'+
                            //'<td>'+data.linea+'</td>'+
                            '<td><input id="cant-'+data.codigo+'" onkeyup="soloNumeros(event, '+"'"+data.codigo+"'"+')" type="text" class="cantidad" value="0"/></td>'+
                            '<td><i class="tallas fas fa-pen" onclick="tallas('+"'"+codigo+"'"+')" title="Elegir tallas"></i></td>'+
                            '<td><i class="fas fa-times" onclick="eliminarProducto('+"'"+data.codigo+"'"+')"></i></td>'+
                        '</tr>'
                $('#todosProductos').append(fila)
                $('#productosSeleccionados').css('display', 'inline')
                prodSeleccionados.push(codigo)

                $('#productosSeleccionar').html("")
                $('#productos').css("display", 'none')
                $('#codigoProd').val('')

            }else{
                agregar = true
                for(i=0; i<prodSeleccionados.length; i++){
                    if(prodSeleccionados[i] == data.codigo){
                        agregar = false
                    }
                }

                if(agregar){
                    var row = $('#fila-'+codigo)
                    var existeFila = $('#todosProductos').find(row).length
                    
                    if(existeFila>0){
                        fila += '<td>'+data.codigo+'</td>'+
                                '<td>'+data.descripcion+'</td>'+
                                '<td><input id="cant-'+data.codigo+'" onkeyup="soloNumeros(event, '+"'"+data.codigo+"'"+')" type="text" class="cantidad" value="0"/></td>'+
                                '<td><i class="tallas fas fa-pen" onclick="tallas('+"'"+codigo+"'"+')" title="Elegir tallas"></i></td>'+
                                '<td><i class="fas fa-times" onclick="eliminarProducto('+"'"+data.codigo+"'"+')"></i></td>' 

                        row.append(fila)
                                
                    }else{

                        fila += '<tr id="fila-'+codigo+'">'+
                                    '<td>'+data.codigo+'</td>'+
                                    '<td>'+data.descripcion+'</td>'+
                                    '<td><input id="cant-'+data.codigo+'" onkeyup="soloNumeros(event, '+"'"+data.codigo+"'"+')" type="text" class="cantidad" value="0"/></td>'+
                                    '<td><i class="tallas fas fa-pen" onclick="tallas('+"'"+codigo+"'"+')" title="Elegir tallas"></i></td>'+
                                    '<td><i class="fas fa-times" onclick="eliminarProducto('+"'"+data.codigo+"'"+')"></i></td>'+
                                '</tr>'
                        
                        $('#todosProductos').append(fila)
                    }
                    
                    $('#productosSeleccionados').css('display', 'inline')
                    prodSeleccionados.push(codigo)  

                    $('#productosSeleccionar').html("")
                    $('#productos').css("display", 'none')
                    $('#codigoProd').val('')  

                }else{
                    var cantidad = parseInt($('#cant-'+data.codigo).val())
                    if(isNaN(cantidad)){
                        cantidad = 0
                    }
                    var cantidad = cantidad + 1;
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
function eliminarProducto(codigo){
    $('#fila-'+codigo).html('')
    var i = prodSeleccionados.indexOf(codigo)
    if(i !== -1){
        prodSeleccionados.splice(i, 1)
    }
    //
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
    $('#mensaje').css('display', 'none')
}


// Campo solo números
var cad = ""
function soloNumeros(e, codigo){
    tecla = e.keyCode
    console.log(tecla)
    if((tecla>47 && tecla<58) || (tecla>95 && tecla<106)){
        cad = $('#cant-'+codigo).val()
    }else if(tecla == 8){
        cad = ""
    }
    $('#cant-'+codigo).val(cad)
}

// MODAL ANULAR
function anularDocumentoAlmacen(numero){
    $('#numeroDocumento').val(numero)
    $('#modalAnularDocumento').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// ANULAR 
$('#btnAnularDocumento').click(function(){
    var numeroDoc = $('#numeroDocumento').val()
    var data = {
        numeroDoc,
        _token: $('input[name=_token]').val(),
    }
    $.ajax({
        type: 'delete',
        url: 'documentos_almacen/anular',
        dataType: 'json',
        data,
        success: function(a){
            if(a.estado){
                $('#modalAnularDocumento').modal('hide')
                location.replace(URI)

            }else{
                $('#mensajeAnular').text(a.mensaje)
                $('#anularDoc').css('display', 'inline')
                $('#modalAnularDocumento').modal('hide')
            }
        },
        error: function(e){
            $('#mensajeAnular').text(e.message)
            $('#anularDoc').css('display', 'inline')
        }   
    })
})

function tipoDocAlm(){
    var tipo = $('#tipoDoc').val()
    if(tipo == 'ingreso'){
        $('#tituloSuc').text('Origen')
    }else{
        $('#tituloSuc').text('Destino')
    }
}

function tallas(codigo){
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
                      '<button type="button" class="btn btn-success" onclick="guardarTallas('+"'"+codigo+"'"+')">Guardar</button>'
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