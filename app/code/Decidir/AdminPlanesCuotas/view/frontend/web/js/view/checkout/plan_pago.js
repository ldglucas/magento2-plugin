/*browser:true*/
/*global define*/
define(
    [
        'ko',
        'uiComponent',
        'jquery',
        'Magento_Checkout/js/model/quote',
        'Magento_Catalog/js/price-utils',
        'Decidir_AdminPlanesCuotas/js/model/plan',
        'Magento_Checkout/js/model/url-builder',
        'Magento_Customer/js/model/customer'
    ],
    function (
        ko,
        Component,
        $,
        quote,
        priceUtils,
        plan,
        urlBuilder,
        customer
    ) {
        'use strict';
        return Component.extend({
            defaults:{
                template: 'Decidir_AdminPlanesCuotas/selector-plan'
            },
            initialize: function ()
            {
                this._super();
            },
            getTarjetasDisponibles: function()
            {
                return plan.getTarjetasDisponibles();
            },
            getBancosDisponibles: function ()
            {
                return plan.getBancosDisponibles();
            },
            getPlanesDisponibles: function ()
            {
                return plan.getPlanesDisponibles();
            },
            getBancosDeTarjeta: function (data, event)
            {
                var tarjeta  = $('#' + event.target.id);

                $('.tarjeta-seleccionada').removeClass('tarjeta-seleccionada');

                $('.banco-seleccionado').removeClass('banco-seleccionado');
                $('.cuotas-disponibles').addClass('no-display-2');
                $('button.aplicar-plan').addClass('no-display-2');

                tarjeta.parent().parent().addClass('tarjeta-seleccionada');

                $('.bancos-disponibles').removeClass('no-display-2');

                /**
                 * Primero oculto todos los bancos, para luego mostrar lo que tiene disponible el plan.
                 */
                $('.box-banco').each(function()
                {
                    $(this).hide();
                    $(this).children('div').children('input').prop('checked',false);
                });

                var planesDisponibles = plan.getPlanesDisponibles();

                $.each(planesDisponibles,function(index, val)
                {
                    if(val.tarjeta_id == tarjeta.val())
                    {
                        $('#banco_'+val.banco_id).parent().parent().show();
                    }
                });

                return true;
            },
            getPlanesPago: function (data, event)
            {
                event.stopPropagation();

                var banco       = $('#' + event.target.id);
                var banco_id    = banco.val();
                var tarjeta_id  = jQuery('[name="tarjeta"]:checked').val();
                $('.adminplanes-loader').removeClass('no-display-2');

                var serviceUrl;

                if (!customer.isLoggedIn())
                {
                    serviceUrl = urlBuilder.createUrl('/guest-carts/:cartId/payment-information', {
                        cartId: quote.getQuoteId()
                    });
                }
                else {
                    serviceUrl = urlBuilder.createUrl('/carts/mine/payment-information', {});
                }

                $.ajax(
                {
                    url     : '/' + serviceUrl,
                    context : this,
                    success : function (response)
                    {
                        var grandTotal  = response.totals.grand_total;

                        $('button.aplicar-plan').addClass('no-display-2');
                        $('.banco-seleccionado').removeClass('banco-seleccionado');

                        banco.parent().parent().addClass('banco-seleccionado');

                        var planesDisponibles = plan.getPlanesDisponibles();
                        var planId = null;

                        $.each(planesDisponibles,function(index, val)
                        {
                            if(val.tarjeta_id == tarjeta_id && val.banco_id == banco_id)
                            {
                                planId = val.plan_pago_id;
                            }
                        });

                        var cuotas = plan.getCuotasDisponibles();

                        if (typeof cuotas[planId] != "undefined")
                        {
                            $('#cuotas-disponibles').empty();
                            $('.cuotas-disponibles').removeClass('no-display-2');

                            $.each(cuotas[planId], function (index,val)
                            {
                                var reintegroHtml = '';
                                var descuentoHtml = '';
                                var reintegroBox  = '';
                                var interesHtml   = '';
                                var totalCompra   = grandTotal;
                                var valorCuota;

                                if(val.reintegro > 0)
                                {
                                    reintegroBox = "<div class='reintegro-pop'>"+
                                        "<p>El reintegro es aplicado en el momento de recibir su resumen bancario. Sujeto a condiciones y topes del banco.</p><i class='cerrar-div' onclick='jQuery(\".reintegro-pop\").hide()'></i>"+
                                        "</div>";

                                    if(val.tipo_reintegro == 1)
                                        var reintegro = val.reintegro +'%';
                                    else
                                        var reintegro = priceUtils.formatPrice(val.reintegro, quote.getPriceFormat());

                                    reintegroHtml = "<span class='reintegro'>"+ reintegro +" de reintegro <i class='mas-info' " +
                                        " onclick='jQuery(this).parent().parent().parent().children(\".reintegro-pop\").toggle()' >"+
                                        "</i></span>";
                                }

                                if(val.descuento > 0)
                                {
                                    if(val.tipo_descuento == 1 && val.descuento < 100)
                                    {
                                        var descuento = val.descuento +'%';
                                        var descuentoNominal = (val.descuento * totalCompra)/100;

                                        valorCuota = priceUtils.formatPrice(((totalCompra - descuentoNominal)/val.cuota), quote.getPriceFormat());
                                        descuentoHtml = "<span class='reintegro descuento'>"+ descuento +" de descuento</span>";
                                    }
                                    else if(val.tipo_descuento == 2 && val.descuento < totalCompra)
                                    {
                                        var descuento = priceUtils.formatPrice(val.descuento, quote.getPriceFormat());

                                        valorCuota = priceUtils.formatPrice(((totalCompra - val.descuento)/val.cuota), quote.getPriceFormat());
                                        descuentoHtml = "<span class='reintegro descuento'>"+ descuento +" de descuento</span>";
                                    }
                                }
                                else
                                    valorCuota = priceUtils.formatPrice((totalCompra/val.cuota), quote.getPriceFormat());

                                if(val.interes == 0)
                                {
                                    if(val.cuota==1)
                                        interesHtml = val.cuota + ' cuota <strong style="display: inline">sin inter&eacute;s</strong> de '+'<strong>'+valorCuota+'</strong>';
                                    else
                                        interesHtml = val.cuota + ' cuotas <strong style="display: inline">sin inter&eacute;s</strong> de '+'<strong>'+valorCuota+'</strong>';
                                }
                                else
                                {
                                    var valorConInteres = parseFloat(totalCompra) + parseFloat(totalCompra * (val.interes/100));

                                    valorCuota = priceUtils.formatPrice((valorConInteres/val.cuota), quote.getPriceFormat());

                                    if(val.cuota==1)
                                        interesHtml = val.cuota + ' cuota fija de '+'<strong>'+valorCuota+'</strong>';
                                    else
                                        interesHtml = val.cuota + ' cuotas fijas de '+'<strong>'+valorCuota+'</strong>';
                                }

                                var onClick = "onclick = \"jQuery(\'button.aplicar-plan\').removeClass(\'no-display-2\');jQuery(this).children(\'input\').prop(\'checked\',true);jQuery(\'.box-plan-cuota\').removeClass(\'cuota-seleccionada\');jQuery(this).addClass(\'cuota-seleccionada\')\"";

                                var boxPlanCuota = "<div class='box-plan-cuota' id='plan_"+planId+"' "+onClick+" >"+
                                    "<input name='plan' value='"+val.cuota+"' type='radio'>"+
                                    "<div class='right-cuota'>"+
                                    "<span class='cuota'>"+interesHtml+"</span>"+
                                    reintegroHtml+descuentoHtml+"</div>"+ reintegroBox+
                                    "</div>";

                                $('#cuotas-disponibles').append(boxPlanCuota);
                            });
                            $('.adminplanes-loader').addClass('no-display-2');
                        }
                        else
                        {
                            $('.adminplanes-loader').addClass('no-display-2');
                            alert('ERROR INTERNO: La combinacion de banco y tarjeta no tiene planes disponibles.')
                        }

                    },
                    error   : function (e, status)
                    {
                        alert("Se produjo un error, por favor intentelo nuevamente");
                        $('.adminplanes-loader').addClass('no-display-2');
                    }
                });

                return true;
            },
            checkInput: function (id)
            {
                var div = $('#' + id);

                if( (div.hasClass('box-tarjeta') && !div.hasClass('tarjeta-seleccionada')) ||
                    (div.hasClass('box-banco') && !div.hasClass('banco-seleccionado')) )
                {
                    div.children().find('input[type="radio"]').prop('checked',true);
                    div.children().find('input[type="radio"]').trigger('click');
                }

                return true;
            }
        });
    }
);
