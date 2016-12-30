
/*browser:true*/
/*global define*/
define(
    [
        'ko',
        'Magento_Checkout/js/view/payment/default',
        'jquery',
        'Magento_Checkout/js/model/quote',
        'Magento_Catalog/js/price-utils',
        'Decidir_AdminPlanesCuotas/js/model/plan',
        'Magento_Checkout/js/model/totals',
        'Decidir_AdminPlanesCuotas/js/action/get-payment-information',
        'mage/translate'
    ],
    function (
        ko,
        Component,
        $,
        quote,
        priceUtils,
        plan,
        totals,
        getPaymentInformationAction,
        $t
    ) {
        'use strict';
        return Component.extend({
            defaults:{
                template: 'Decidir_SpsDecidir/payment/decidir-form'
            },
            initialize: function ()
            {
                this._super();

                this.meses = ['01','02','03','04','05','06','07','08','09','10','11','12'];
                this.tarjetasTokenizadas = window.checkoutConfig.payment.decidirToken;
                this.tarjeta_tokenizada = ko.observable('');
            },
            getCode: function()
            {
                return 'decidir_spsdecidir';
            },
            isActive: function()
            {
                return true;
            },
            validate: function()
            {
                var $form = $('#' + this.getCode() + '-form');
                return $form.validation() && $form.validation('isValid');
            },
            getMeses: function ()
            {
                return _.map(this.meses, function(value) {
                    return {
                        'value': value,
                        'text' : value
                    }
                });
            },
            getAnios: function ()
            {
                var anios = [];
                var fecha = new Date();
                var anioActual =  fecha.getFullYear();

                for(var i = 0; i <= 10; i++)
                    anios[i] = anioActual + i;

                return _.map(anios, function(value) {
                    return {
                        'value': value,
                        'text' : value
                    }
                });
            },
            getCuotas: function (planId)
            {
                return window.checkoutConfig.payment.cuotasPorPlanDisponibles[planId];
            },
            getTarjetasTokenizadas: function ()
            {
                return _.map(this.tarjetasTokenizadas, function(value) {
                    return {
                        'value': value.decidir_token_id,
                        'text' : $t('Tarjeta ' + value.nombre_tarjeta + ' terminada en '+ value.card_number_last_4_digits)
                    }
                });
            },
            getToken: function (tokenId)
            {
                var token   = new Object();

                $.each(this.tarjetasTokenizadas, function (index,value)
                {
                    if(value.decidir_token_id == tokenId)
                        token = value;
                });

                return token;
            },
            /**
             * Metodo para traer la autorizacion desde decidir
             */
            getAuthorizeRequest: function()
            {
                var planesDisponibles = plan.getPlanesDisponibles();
                var detallesPlan;
                var tarjeta_id = $("[name='tarjeta']:checked").val();
                var sps_id     = $("[name='tarjeta']:checked").attr('data-id-sps');
                var banco_id   = $("[name='banco']:checked").val();
                var cuotas     = $("[name='plan']:checked").val();
                var plan_pago_id = $('.box-plan-cuota.cuota-seleccionada').attr('id').split('_')[1];

                var infoPlanCuota = {plan_pago_id:plan_pago_id, cuota:cuotas};
                $('.adminplanes-loader').removeClass('no-display-2');

                /**
                 * Se elimina cada vez que se instancia, debido a que sps crea nuevamente esta div cada vez
                 * que se instancia el Payment.init()
                 */
                $('#boxSecCodeHelpContainer').remove();

                var planSeleccionado = new Object();

                $.each(planesDisponibles,function(index, val)
                {
                    if(val.tarjeta_id == tarjeta_id && val.banco_id == banco_id && val.plan_pago_id == plan_pago_id)
                    {
                        planSeleccionado.id     = val.plan_pago_id;
                        var detalleCuotasHtml   = $("[name='plan']:checked").parent().children('.right-cuota').children('span.cuota').html();

                        detallesPlan =
                            "<li>Tarjeta: <span class='detalle-plan'>"+val.tarjeta_nombre+"</span></li>"+
                            "<li>Entidad financiera: <span class='detalle-plan'>"+val.banco_nombre+"</span></li>"+
                            "<li>Cuotas: <span class='detalle-plan'>"+detalleCuotasHtml+"</span></li>";
                        return true;
                    }
                });

                $.each(this.getCuotas(planSeleccionado.id),function(index, val)
                {
                    if(val.cuota == cuotas)
                    {
                        planSeleccionado.cuota = val;
                        return true;
                    }
                });

                planSeleccionado.detalleReintegro = this.getDetalleReintegro(planSeleccionado.cuota.reintegro,planSeleccionado.cuota.tipo_reintegro);

                /**$('table.table-totals tbody').append(trHtml)**/

                $('.detalles-plan-seleccionado').html(detallesPlan);

                $.ajax('/spsdecidir/payment/authorizeRequest',
                {
                    type     : "post",
                    context  : this,
                    data     :
                    {
                        tarjeta_id      : sps_id,
                        cantidad_cuotas : cuotas,
                        cuota_enviar    : planSeleccionado.cuota.cuota_enviar,
                        plan_pago_id    : plan_pago_id
                    },
                    success  : function(response)
                    {
                        /**console.log(response);*/
                        if(response.rk != 0 && response.prk !=0)
                        {
                            if(planSeleccionado.cuota.descuento !=0)
                            {
                                $.ajax('/rest/V1/descuento/plan_pago/'+planSeleccionado.cuota.plan_pago_id+'/cuota/'+planSeleccionado.cuota.cuota,
                                {
                                    method  : 'PUT',
                                    context : this,
                                    success : function (response)
                                    {
                                        var deferred = $.Deferred();

                                        totals.isLoading(true);
                                        getPaymentInformationAction(deferred);
                                        $.when(deferred).done(function () {
                                            totals.isLoading(false);
                                        });

                                        $('tr.descuento_cuota th').text(response);
                                        $('tr.descuento_cuota').show();
                                    },
                                    error   : function (e, status)
                                    {
                                        alert("Se produjo un error, por favor intentelo nuevamente");
                                        $('.adminplanes-loader').addClass('no-display-2');
                                        $('tr.descuento_cuota').hide();
                                    }
                                });
                            }

                            if(planSeleccionado.detalleReintegro)
                            {
                                $('table.table-totals tbody').append(planSeleccionado.detalleReintegro);
                            }

                            $('.selector-cuotas').addClass('no-display-2');
                            $('.tarjetas-disponibles').addClass('no-display-2');
                            $('.bancos-disponibles').addClass('no-display-2');
                            $('.cuotas-disponibles').addClass('no-display-2');
                            $('button.aplicar-plan').addClass('no-display-2');

                            $('.plan-seleccionado').removeClass('no-display-2');

                            $('div.sps-datos-tarjeta').show('slow');
                            $('#sps-pagar-btn').show();
                            $('.adminplanes-loader').addClass('no-display-2');

                            var responseKeys = {rk:response.rk,prk:response.prk};
                            $('#sps-request-key').val(response.prk);

                            new	Payment().init(
                                {
                                    id:	'decidir_spsdecidir-form',
                                    fieldsId:
                                    {
                                        CardHolderName         : 'sps-tarjeta-nombre',
                                        CardHolderMail         : 'sps-email',
                                        CardNumber             : 'sps-tarjeta-numero',
                                        /**CardExpirationMonth    : 'sps-tarjeta-vencimiento-mes',*/
                                        /**CardExpirationYear     : 'sps-tarjeta-vencimiento-anio',*/
                                        CardExpirationDate     : 'sps-tarjeta-vencimiento',
                                        CardSecurityCode       : 'sps-tarjeta-codigo-seguridad',
                                        PublicRequestKey       : 'sps-request-key',
                                        CardSecurityCodeHelper : 'sps-tarjeta-codigo-seguridad-helper'
                                    },
                                    callback: function(responsePayment)
                                    {
                                        console.log(responsePayment);

                                        if("Error" in responsePayment && responsePayment.Error.length > 0)
                                            return false;

                                        var detallesPago = 'Pago realizado con ';

                                        $('.detalles-plan-seleccionado > li > span').each(function(index,value)
                                        {
                                            if(index == 0)
                                                detallesPago += jQuery(this).text() + ' y ';
                                            if(index == 1)
                                                detallesPago += jQuery(this).text() + ' en ';
                                            if(index == 2)
                                                detallesPago += jQuery(this).text();
                                        });

                                        responseKeys.pak    = responsePayment.PublicAnswerKey;
                                        responseKeys.status = responsePayment.Status;
                                        responseKeys.tarjeta= $("[name='tarjeta']:checked").val();
                                        responseKeys.banco  = $("[name='banco']:checked").val();
                                        responseKeys.detallesPago = detallesPago;

                                        $.ajax('/spsdecidir/payment/authorizeAnswer',
                                            {
                                                type    : "post",
                                                data    : responseKeys,
                                                success : function (response)
                                                {
                                                    console.log(response);
                                                    $('#terminar-pedido-sps').trigger('click');
                                                },
                                                error   : function (e, status)
                                                {
                                                    alert("Se produjo un error, por favor intentelo nuevamente");
                                                    $('.adminplanes-loader').addClass('no-display-2');
                                                    console.log(e);
                                                }
                                            });
                                    }
                                    ,options:
                                {
                                    displayCreditCardTypeDetected   :	false,
                                    displayCreditCardTypeContainerId:	null,
                                    cardType                        :	$("[name='tarjeta']:checked").val(),
                                    cardBin                         :	null,
                                    displayCardBin                  :	true,
                                    displayCardBinContainerId       :	null
                                }
                                });

                            $('#boxSecCodeHelpContainer').detach().appendTo('li.sps-codigo-seguridad')
                        }
                        else
                        {
                            alert("Se produjo un error al procesar las cuotas. Por favor intentelo nuevamente");
                            console.log(response);
                        }
                    },
                    error    : function()
                    {
                        $('#sps-pagar-btn').hide();
                        $('.adminplanes-loader').addClass('no-display-2');
                        alert("Disculpe, tuvimos inconveniente. Intente nuevamente");
                    }
                });

               return false;
            },
            getAuthorizeRequestTokenizado: function ()
            {
                var planesDisponibles = plan.getPlanesDisponibles();
                var detallesPlan;
                var tarjeta_id   = $("#decidir-token-tarjeta").val();
                var sps_id       = $("#decidir-token-tarjeta").attr('data-id-sps');
                var banco_id     = $("#decidir-token-banco").val();
                var cuotas       = $("[name='plan']:checked").val();
                var plan_pago_id = $('.box-plan-cuota.cuota-seleccionada').attr('id').split('_')[1];

                var infoPlanCuota = {plan_pago_id:plan_pago_id, cuota:cuotas};
                $('.adminplanes-loader').removeClass('no-display-2');

                /**
                 * Se elimina cada vez que se instancia, debido a que sps crea nuevamente esta div cada vez
                 * que se instancia el Payment.init()
                 */
                $('#boxSecCodeHelpContainer').remove();

                var planSeleccionado = new Object();

                $.each(planesDisponibles,function(index, val)
                {
                    if(val.tarjeta_id == tarjeta_id && val.banco_id == banco_id && val.plan_pago_id == plan_pago_id)
                    {
                        planSeleccionado.id     = val.plan_pago_id;
                        var detalleCuotasHtml   = $("[name='plan']:checked").parent().children('.right-cuota').children('span.cuota').html();

                        detallesPlan = "<li>Cuotas: <span class='detalle-plan'>"+detalleCuotasHtml+"</span></li>";
                        return true;
                    }
                });

                $.each(this.getCuotas(planSeleccionado.id),function(index, val)
                {
                    if(val.cuota == cuotas)
                    {
                        planSeleccionado.cuota = val;
                        return true;
                    }
                });

                planSeleccionado.detalleReintegro = this.getDetalleReintegro(planSeleccionado.cuota.reintegro,planSeleccionado.cuota.tipo_reintegro);

                $('.cuotas-disponibles').addClass('no-display-2');

                $('.detalles-plan-seleccionado-token').html(detallesPlan).removeClass('no-display-2');
                $('.tarjetas-tokenizadas').show();
                $('.sps-datos-tarjeta-tokenizada').show();

                $.ajax('/spsdecidir/payment/authorizeRequest',
                {
                    type     : "post",
                    context  : this,
                    data     :
                    {
                        tarjeta_id      : $('#decidir-token-tarjeta').attr('data-id-sps'),
                        cantidad_cuotas : cuotas,
                        cuota_enviar    : planSeleccionado.cuota.cuota_enviar,
                        plan_pago_id    : plan_pago_id
                    },
                    success  : function(response)
                    {
                        if(planSeleccionado.cuota.descuento !=0)
                        {
                            $.ajax('/rest/V1/descuento/plan_pago/'+planSeleccionado.cuota.plan_pago_id+'/cuota/'+planSeleccionado.cuota.cuota,
                            {
                                method  : 'PUT',
                                context : this,
                                success : function (response)
                                {
                                    var deferred = $.Deferred();

                                    totals.isLoading(true);
                                    getPaymentInformationAction(deferred);
                                    $.when(deferred).done(function () {
                                        totals.isLoading(false);
                                    });

                                    $('tr.descuento_cuota th').text(response);
                                    $('tr.descuento_cuota').show();
                                },
                                error   : function (e, status)
                                {
                                    alert("Se produjo un error, por favor intentelo nuevamente");
                                    $('.adminplanes-loader').addClass('no-display-2');
                                    $('tr.descuento_cuota').hide();
                                }
                            });
                        }

                        $('#decidir-token-request-key').val(response.prk);

                        console.log(response);
                        if(response.rk != 0 && response.prk !=0)
                        {
                            $('.adminplanes-loader').addClass('no-display-2');
                            $('.sps-codigo-seguridad-token').removeClass('no-display-2');
                            $('button.aplicar-cuotas-token').hide();
                            $('#sps-pagar-btn-token').show();

                            var responseKeys = {rk:response.rk,prk:response.prk};

                            new	Payment().init(
                                {
                                    id:	'decidir_spsdecidir-form-token',
                                    fieldsId:
                                    {
                                        CardToken               : 'decidir-token',
                                        CardSecurityCode        : 'decidir-token-codigo-seguridad',
                                        PublicRequestKey        : 'decidir-token-request-key',
                                        CardSecurityCodeHelper  : 'decidir-token-codigo-seguridad-helper'
                                    },
                                    callback: function(responsePayment)
                                    {
                                        if("Error" in responsePayment && responsePayment.Error.length > 0)
                                            return false;

                                        var detallesPago = 'Pago realizado con ';

                                        $('.detalles-plan-seleccionado-token > li > span').each(function(index,value)
                                        {
                                            if(index == 0)
                                                detallesPago += $(this).text() + ' y ';
                                            if(index == 1)
                                                detallesPago += $(this).text() + ' en ';
                                            if(index == 2)
                                                detallesPago += $(this).text();
                                        });

                                        responseKeys.pak    = responsePayment.PublicAnswerKey;
                                        responseKeys.status = responsePayment.Status;
                                        responseKeys.tarjeta= $("#decidir-token-tarjeta").val();
                                        responseKeys.banco  = $("#decidir-token-banco").val();
                                        responseKeys.detallesPago = detallesPago;
                                        responseKeys.token = $('#decidir-token').val();

                                        $.ajax('/spsdecidir/payment/authorizeAnswer',
                                        {
                                            type    : 'post',
                                            data    : responseKeys,
                                            success : function (response)
                                            {
                                                console.log(response);
                                                $('#terminar-pedido-sps-token').trigger('click');
                                            },
                                            error   : function (e, status)
                                            {
                                                alert("Se produjo un error, por favor intentelo nuevamente");
                                                $('.adminplanes-loader').addClass('no-display-2');
                                                console.log(e);
                                            }
                                        });
                                    },
                                    options:
                                    {
                                        cardType: $('#decidir-token-tarjeta').attr('data-id-sps')
                                    }
                                });
                        }
                    },
                    error    : function()
                    {
                        /**$('#sps-pagar-btn').hide();**/
                        $('.adminplanes-loader').addClass('no-display-2');
                        alert("Disculpe, tuvimos inconveniente. Intente nuevamente");
                    }
                });
            },
            cambiarPlan: function ()
            {
                $('[name="tarjeta"]').prop('checked',false);
                $('.box-tarjeta').removeClass('tarjeta-seleccionada');
                $('.leyenda-reintegro').remove();
                $('.tarjetas-disponibles').removeClass('no-display-2');
                $('.selector-cuotas').removeClass('no-display-2');

                $('.plan-seleccionado').addClass('no-display-2');
                $('.sps-datos-tarjeta').hide();

                $('#sps-tarjeta-nombre').val('');
                $('#sps-tarjeta-numero').val('');
                $('#sps-tarjeta-vencimiento').val('');
                $('#sps-tarjeta-codigo-seguridad').val('');
                $('#sps-email').val('');
                $('#sps-request-key').val('');
                $('#sps-tarjeta-codigo-seguridad-helper').val('');

                $('.adminplanes-loader').removeClass('no-display-2');

                $.ajax('/rest/V1/descuento/reset',
                {
                    method  : 'GET',
                    context : this,
                    success : function (response)
                    {
                        var deferred = $.Deferred();

                        totals.isLoading(true);
                        getPaymentInformationAction(deferred);
                        $.when(deferred).done(function () {
                            totals.isLoading(false);
                        });
                        $('.adminplanes-loader').addClass('no-display-2');
                        $('tr.descuento_cuota').hide();
                    },
                    error   : function (e, status)
                    {
                        alert("Se produjo un error, por favor intentelo nuevamente");
                        $('.adminplanes-loader').addClass('no-display-2');
                        $('tr.descuento_cuota').hide();
                    }
                });
            },
            getDetalleReintegro: function (reintegro,tipo)
            {
                var trHtml = '';
                $('.leyenda-reintegro').remove();

                if(reintegro > 0)
                {
                    var valor = 0;

                    /**1 = porcentual**/
                    if(tipo == 1)
                    {
                        var leyenda = reintegro +'%';
                        var totalSinCostoEnvio = (quote.getCalculatedTotal() - quote.shippingMethod().amount);

                        valor = priceUtils.formatPrice((reintegro * totalSinCostoEnvio)/100, quote.getPriceFormat());
                    }
                    else
                    {
                        valor       = priceUtils.formatPrice(reintegro, quote.getPriceFormat());
                        var leyenda = valor;
                    }

                    trHtml += "<tr class='leyenda-reintegro'>" +
                        "<th class='mark'>" +
                        "<span class='destacado'>"+leyenda+" de reintegro</span><span class='value'>" +
                        "(el reintegro se verá reflejado en el resumen bancario, sujeto a condiciones y topes del banco)</span>" +
                        "</th>" +
                        "<td class='amount'>" +
                        "<span class='price'>- "+valor+"</span>" +
                        "</td>" +
                        "</tr>";
                }

                return trHtml;
            },
            aplicarToken: function ()
            {
                var tokenId = $('[name="tarjeta-token"]').val();
                var token   = this.getToken(tokenId);

                if(!jQuery.isEmptyObject(token))
                {
                    $('.adminplanes-loader').removeClass('no-display-2');
                    $('#decidir-token').val(token.token);

                    $('.cuotas-disponibles').removeClass('no-display-2');

                    $('[name="tarjeta-token"]').hide();
                    $('.decidir-token-descripcion').hide();
                    /**$('ul.tarjetas-tokenizadas').hide();**/

                    $('#selector-planes').show();
                    $('.tarjetas-disponibles').hide();


                    $('.tarjetas-tokenizadas').hide();
                    $('.sps-datos-tarjeta-tokenizada').hide();
                    $('#aplicar-tarjeta').hide();

                    /**var banco       = 'Texto generico de banco';**/
                    var banco_id    = token.banco_id;
                    var tarjeta_id  = token.tarjeta_id; /**jQuery('[name="tarjeta"]:checked').val();*/

                    $('#decidir-token-tarjeta').val(token.tarjeta_id);
                    $('#decidir-token-tarjeta').attr('data-id-sps',token.sps_tarjeta_id);
                    $('#decidir-token-banco').val(token.banco_id);

                    $('button.aplicar-plan').addClass('no-display-2');
                    $('.banco-seleccionado').removeClass('banco-seleccionado');

                    var planesDisponibles = plan.getPlanesDisponibles();
                    var planId = null;

                    $.each(planesDisponibles,function(index, val)
                    {
                        if(val.tarjeta_id == tarjeta_id && val.banco_id == banco_id)
                        {
                            planId = val.plan_pago_id;
                        }
                    });

                    $.ajax(
                    {
                        url     : '/rest/default/V1/carts/mine/payment-information',
                        type    : 'get',
                        context : this,
                        success : function (response)
                        {
                            var grandTotal  = response.totals.grand_total;

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
                                            " onclick='jQuery(this).parent().parent().parent().children(\".reintegro-pop\").toggle()' >" +
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
                                            interesHtml = val.cuota + ' cuota <strong style="display: inline;">sin inter&eacute;s</strong> de '+'<strong>'+valorCuota+'</strong>';
                                        else
                                            interesHtml = val.cuota + ' cuotas <strong style="display: inline;">sin inter&eacute;s</strong> de '+'<strong>'+valorCuota+'</strong>';
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

                                    var onClick = "onclick = \"jQuery(this).children(\'input\').prop(\'checked\',true);" +
                                        "jQuery(\'.box-plan-cuota\').removeClass(\'cuota-seleccionada\');" +
                                        "jQuery('.aplicar-cuotas-token').show();jQuery(this).addClass(\'cuota-seleccionada\')\"";

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
                                /**
                                 * ERROR INTERNO: La combinacion de banco y tarjeta no tiene planes disponibles.'
                                 */
                                alert('La tarjeta de crédito seleccionada no tiene planes de pago disponibles. Por favor seleccione otro método de pago.')
                            }
                        },
                        error   : function (e, status)
                        {
                            $('.adminplanes-loader').addClass('no-display-2');
                            alert("Se produjo un error, por favor intentelo nuevamente");
                        }
                    });
                }

                return false;
            },
            cambiarTarjeta: function ()
            {
                $.ajax('/rest/V1/descuento/reset',
                {
                    method  : 'GET',
                    context : this,
                    success : function (response)
                    {
                        var deferred = $.Deferred();

                        totals.isLoading(true);
                        getPaymentInformationAction(deferred);
                        $.when(deferred).done(function () {
                            totals.isLoading(false);
                        });
                        $('.adminplanes-loader').addClass('no-display-2');
                        $('tr.descuento_cuota').hide();
                    },
                    error   : function (e, status)
                    {
                        alert("Se produjo un error, por favor intentelo nuevamente");
                        $('.adminplanes-loader').addClass('no-display-2');
                        $('tr.descuento_cuota').hide();
                    }
                });

                $('.tarjeta-almacenada').hide();
                $('#selector-planes').show();
                $('.tarjetas-disponibles').show();
                $('.cuotas-disponibles').addClass('no-display-2');
                $('script[src*="'+window.checkoutConfig.payment.paymentTokenJsDecidir+'"]').remove();

                var paymentJsDecidir = document.createElement('script');
                paymentJsDecidir.setAttribute('type', 'text/javascript');
                paymentJsDecidir.setAttribute('src', window.checkoutConfig.payment.paymentJsDecidir + '?noxhr='
                    + (new Date()).getTime());
                $('head').append(paymentJsDecidir);

            },
            /**
             * Si no existe un token generado para el usuario, muestra el formulario de tarjetas y bancos, caso
             * contrario muestra el formulario de tokenizacion.
             */
            noExisteToken: function ()
            {
                if(this.tarjetasTokenizadas.length)
                    return false;
                else
                    return true;
            },
            cambiarTarjetaTokenizada: function ()
            {
                var tokenId = this.tarjeta_tokenizada();
                var datosToken = this.getToken(tokenId);

                $('.tarjeta-container').hide();
                $('#tarjeta-token_'+tokenId).show();
                $('#decidir-token').val(datosToken.token);
            }
        });
    }
);
