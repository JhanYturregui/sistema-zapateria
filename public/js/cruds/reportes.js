var origin = localStorage.getItem('url')
var pathname = window.location.pathname

URI = origin+pathname

function verDetalles(numeroReporte){
    var data = {
        numeroReporte,
        _token: $('input[name=_token]').val()
    }
    $.ajax({
        type: 'post',
        url: '/reportes/detalles',
        data,
        dataType: 'json',
        success: function(a){
            // Detalles
            var detalles = ''
            detalles += '<ul>'+
                            '<li><strong>Fecha</strong>: '+a.fecha+'</li>'+
                            '<li><strong>Hora</strong>: '+a.hora+'</li>'+
                            '<li><strong>Cantidad Ventas</strong>: '+a.contVentas+'</li>'+
                            '<li><strong>Cantidad Movimientos</strong>: '+a.contMovimientos+'</li>'+
                            '<li><strong>Monto Total</strong>: '+a.montoTotal+'</li>'+
                            '<li><strong>Monto Real</strong>: '+a.montoReal+'</li>'+
                        '</ul>'
            $('#cuerpoDetalles').html(detalles)

            // Ventas
            var ventas = ''
            var dataVentas = a.ventas
            for(i=0; i<a.ventas.length; i++){
                var modosPago = JSON.parse(dataVentas[i].modos_pago)
                var productos = JSON.parse(dataVentas[i].productos)
                var cantProd = JSON.parse(dataVentas[i].cantidades)
                ventas += '<ul>'+
                            '<li><strong>'+dataVentas[i].created_at+'</strong>'+
                                '<ol>'+
                                    '<li>Doc. Cliente: '+dataVentas[i].cliente+'</li>'+
                                    '<li>Monto: '+dataVentas[i].monto_total+'</li>'+
                                    '<li>Productos: '
                                    for(k=0; k<productos.length; k++){
                                        ventas += '<ul>'+
                                                    '<li>'+productos[k]+' -> '+cantProd[k]+'</li>'+
                                                  '</ul>' 
                                    }
                                    ventas += '</li>'+
                                    '<li>Modo Pago: '
                                    for(j=0; j<modosPago.length; j++){
                                        ventas += '<ul>'+
                                                    '<li>'+modosPago[j]+'</li>'+
                                                  '</ul>'
                                    }
                                    ventas += '</li>'+
                                '</ol>'+
                            '</li>'+
                          '</ul>'  
            }
            $('#cuerpoVentas').html(ventas)

            // Movimientos
            var movimientos = ''
            var dataMovimientos = a.movimientos
            for(l=0; l<dataMovimientos.length; l++){
                movimientos +=  '<ul>'+
                                    '<li><strong>'+dataMovimientos[l].created_at+'</strong>'+
                                        '<ol>'+
                                            '<li>Tipo: '+dataMovimientos[l].tipo+'</li>'+
                                            '<li>Monto: '+dataMovimientos[l].monto+'</li>'+
                                            '<li>Doc Persona: '+dataMovimientos[l].doc_persona+'</li>'+
                                            '<li>Comentario: '+dataMovimientos[l].comentario+'</li>'+
                                        '</ol>'+
                                    '</li>'+
                                '</ul>'
            }
            $('#cuerpoMovimientos').html(movimientos)

            $('#modalDetalleReporte').modal({
                keyboard: false,
                backdrop: 'static'
            })
            console.log(a)
        },
        error: function(e){

        }
    })
}