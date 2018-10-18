var origin = window.location.origin
var pathname = window.location.pathname

URI_ACTUALIZAR = origin+pathname
URI_CREAR = origin+'/documentos_almacen'

var prodSeleccionados = []
var iniciado = true

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

    }else{
        var numeroDoc = $('#numeroDoc').val()
        var fechaDoc = $('#fechaDoc').val()
        var tipoDoc = $('#tipoDoc').val()
        var comentario = $('#comentario').val()

        var data = {
            numeroDoc,
            tipoDoc,
            comentario,
            productos,
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
                    location.replace(URI_CREAR)

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

// BUSCAR PRODUCTOS
function buscarProductos(codigo){
    if(codigo != ""){
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
                            '<td>'+data.marca+'</td>'+
                            '<td>'+data.modelo+'</td>'+
                            '<td>'+data.color+'</td>'+
                            '<td>'+data.talla+'</td>'+
                            '<td>'+data.linea+'</td>'+
                            '<td><input id="cant-'+data.codigo+'" onkeyup="soloNumeros(event, '+"'"+data.codigo+"'"+')" type="text" class="cantidad" value="1"/></td>'+
                            '<td><i class="fas fa-times" onclick="eliminarProducto('+"'"+data.codigo+"'"+')"></i></td>'+
                        '</tr>'
                $('#todosProductos').append(fila)
                $('#productosSeleccionados').css('display', 'inline')
                prodSeleccionados.push(codigo)

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
                                '<td>'+data.marca+'</td>'+
                                '<td>'+data.modelo+'</td>'+
                                '<td>'+data.color+'</td>'+
                                '<td>'+data.talla+'</td>'+
                                '<td>'+data.linea+'</td>'+
                                '<td><input id="cant-'+data.codigo+'" onkeyup="soloNumeros(event, '+"'"+data.codigo+"'"+')" type="text" class="cantidad" value="1"/></td>'+
                                '<td><i class="fas fa-times" onclick="eliminarProducto('+"'"+data.codigo+"'"+')"></i></td>' 

                        row.append(fila)
                                
                    }else{

                        fila += '<tr id="fila-'+codigo+'">'+
                                    '<td>'+data.codigo+'</td>'+
                                    '<td>'+data.marca+'</td>'+
                                    '<td>'+data.modelo+'</td>'+
                                    '<td>'+data.color+'</td>'+
                                    '<td>'+data.talla+'</td>'+
                                    '<td>'+data.linea+'</td>'+
                                    '<td><input id="cant-'+data.codigo+'" onkeyup="soloNumeros(event, '+"'"+data.codigo+"'"+')" type="text" class="cantidad" value="1"/></td>'+
                                    '<td><i class="fas fa-times" onclick="eliminarProducto('+"'"+data.codigo+"'"+')"></i></td>'+
                                '</tr>'
                        
                        $('#todosProductos').append(fila)
                    }
                    
                    $('#productosSeleccionados').css('display', 'inline')
                    prodSeleccionados.push(codigo)    

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

// MODAL ELIMINAR
function anularDocumentoAlmacen(numero){
    $('#numeroDocumento').val(numero)
    $('#modalAnularDocumento').modal({
        keyboard: false,
        backdrop: 'static'
    })
}

// ELIMINAR 
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
                location.replace(URI_ACTUALIZAR)

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