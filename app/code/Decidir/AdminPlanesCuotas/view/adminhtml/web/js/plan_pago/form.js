/*browser:true*/
/*global define*/
define(
    [
        'ko',
        'uiComponent',
        'jquery',
        'jquery/validate'
        ,'mage/calendar'
    ],
    function (
        ko,
        Component,
        $,
        validate,
        calendar
    ) {
        'use strict';

        return Component.extend({
            defaults:{
                template: 'Decidir_AdminPlanesCuotas/plan_pago/form',
                options: {}
            },

            nombre:ko.observable(''),
            tarjeta:ko.observableArray([]),
            banco:ko.observableArray([]),
            vigente_desde:ko.observable(''),
            vigente_hasta:ko.observable(''),
            diasVigente:ko.observableArray([]),
            promocionesNoAcumulables:ko.observableArray([]),
            promocionesNoAcumulablesCheck:ko.observable(false),
            prioridad:ko.observable(''),
            activo:ko.observable(false),
            initialize: function ()
            {
                this._super();

                this.optionsDatepicker =
                {
                    showsTime   : true,
                    timeText    : 'Hora',
                    hourText    : 'Hora',
                    minuteText  : 'Minuto',
                    showSecond  : false,
                    dateFormat  : "dd-MM-yy",
                    timeFormat  : 'HH:mm:ss',
                    minDate     : new Date(2016, 0, 1),
                    maxDate     : '+15Y',
                    defaultDate : ''
                };

                this.tarjetas = window.adminPlanesCuotasConfig.tarjetas;
                this.bancos   = window.adminPlanesCuotasConfig.bancos;
                this.dias     = window.adminPlanesCuotasConfig.dias;
                this.adminUrl = window.adminPlanesCuotasConfig.admin_url;
                this.saveUrl  = window.adminPlanesCuotasConfig.save_url;

                this.plan_pago= window.adminPlanesCuotasConfig.plan_pago;
                this.cuotas   = window.adminPlanesCuotasConfig.cuotas;
                this.promocionesCarrito = window.adminPlanesCuotasConfig.promocionesCarrito;

                if(this.plan_pago)
                {
                    var dias = this.plan_pago.dias.split(',');
                    var promocionesCarrito = '';

                    if(this.plan_pago.salesrule_id_no_acumulables)
                    {
                        promocionesCarrito = this.plan_pago.salesrule_id_no_acumulables.split(',');

                        if(this.plan_pago.salesrule_id_no_acumulables.length)
                            this.promocionesNoAcumulablesCheck(true);
                    }

                    this.nombre(this.plan_pago.nombre);
                    this.tarjeta([this.plan_pago.tarjeta_id]);
                    this.banco([this.plan_pago.banco_id]);
                    this.vigente_desde(this.plan_pago.vigente_desde);
                    this.vigente_hasta(this.plan_pago.vigente_hasta);
                    this.diasVigente(dias);
                    this.promocionesNoAcumulables(promocionesCarrito);
                    this.prioridad(this.plan_pago.prioridad);

                    if(this.plan_pago.activo == 1)
                        this.activo(true);
                    else
                        this.activo(false);
                }

            },
            getProductosSeleccionados: function ()
            {
                var productos = [];

                $.each(gridAgregarProductosPlanJsObject.rows,function()
                {
                    var checkbox = $(this).find('input[type="checkbox"]');

                    if(checkbox.prop('checked'))
                    {
                        productos.push(checkbox.val());
                    }
                });

                return productos;
            },
            getPlanPagoId: function ()
            {
                if(this.plan_pago)
                    return this.plan_pago.plan_pago_id;
                else
                    return 0;
            },
            getTarjetasDisponibles: function()
            {
                /**
                 * Uso de http://underscorejs.org/#map para formar el objeto necesario en el select.
                 */
                return _.map(this.tarjetas, function(value) {
                    return {
                        'value': value.tarjeta_id,
                        'text' : value.nombre
                    }
                });
            },
            getBancosDisponibles: function ()
            {
                return _.map(this.bancos, function(value) {
                    return {
                        'value': value.banco_id,
                        'text' : value.nombre
                    }
                });
            },
            getCuotas: function ()
            {
                if(this.cuotas)
                    return this.cuotas;
                else
                {
                    var cuota = [];
                    cuota[0] = {cuota:'',interes:'',reintegro:'',tipo_reintegro:1,cuota_enviar:''};
                    return cuota;
                }
            },
            getPromocionesCarrito: function ()
            {
                return _.map(this.promocionesCarrito, function(value,key) {
                    return {
                        'value': value.rule_id,
                        'text' : value.name
                    }
                });
            },
            getDias: function ()
            {
                return _.map(this.dias, function(value,key) {
                    return {
                        'value': key,
                        'text' : value
                    }
                });
            },
            getPlanesPago: function (data, event)
            {

                return true;
            },
            agregarCuota : function ()
            {
                var inputCuotas      = "<input type='number' min='1' class='plan-cuota input-text admin__control-text' name='cuota' required='' aria-required='true'/>";
                var inputInteres     = "<input type='text'   class='plan-cuota input-text admin__control-text' name='interes' required='' aria-required='true' />";
                var inputReintegro   = "<input type='text'   class='plan-cuota input-text admin__control-text' name='reintegro' />";
                var inputDescuento   = "<input type='text'   class='plan-cuota input-text admin__control-text' name='descuento' />";
                var inputCuotaEnviar = "<input type='number' min='0' class='plan-cuota input-text admin__control-text cuota-enviar' name='cuota_enviar' />";

                var tipoReintegro    = "<select name='tipo_reintegro' class='plan-cuota selector-reintegro select admin__control-select'>" +
                    "<option value='1'>%</option><option value='2'>$</option></select>";
                var tipoDescuento    = "<select name='tipo_descuento' class='plan-cuota selector-descuento select admin__control-select'>" +
                    "<option value='1'>%</option><option value='2'>$</option></select>";

                var labelCuotas      = "<label for='cuota'>Cuota:</label>";
                var labelInteres     = "<label for='interes'>% Interés:</label>";
                var labelReintegro   = "<label for='reintegro'>Reintegro:</label>";
                var labelDescuento   = "<label for='reintegro'>Descuento:</label>";
                var labelCuotaEnviar = "<label for='reintegro'>Cuota a enviar:</label>";

                var eliminarCuota   = "<button onclick='jQuery(this).parent().remove()' class='action-delete eliminar-cuota' type='button'><span>Delete</span></button>";

                var liPlanCuota     = "<li class='plan-cuota'>"+
                    labelCuotas + inputCuotas+
                    labelInteres + inputInteres+
                    labelReintegro + inputReintegro + tipoReintegro+
                    labelDescuento + inputDescuento  + tipoDescuento+
                    labelCuotaEnviar + inputCuotaEnviar+
                    eliminarCuota+
                    "</li>";

                $('ul.cuotas > li.plan-cuota').last().after(liPlanCuota);
            },
            validarCampos: function (form)
            {
                console.log(this.nombre());
                console.log(this.tarjeta());
                console.log(this.banco());
                console.log(this.vigente_desde());
                console.log(this.vigente_hasta());
                console.log(this.prioridad());
                console.log(this.activo());

                if(this.promocionesNoAcumulablesCheck())
                {

                }

                return true;
            },
            guardarPlan: function (dataForm)
            {
                var form = $(dataForm);

                if(!this.validarCampos())
                    return false;

                $('.adminplanes-loader').show();

                var datos  = $('ul.agregar-plan').find('input.plan-dato');
                var cuotas = $('ul.cuotas li.plan-cuota');

                var datosGrabar = new Object();

                $.each(datos,function(key,val)
                {
                    var input = $(val);
                    if(input.attr('type')=='checkbox')
                    {
                        var checked = 0;
                        if(input.is(':checked'))
                            checked = 1;

                        /*console.log(input.attr('name')+' => '+checked);*/
                        datosGrabar[input.attr('name')] = checked;
                    }
                    else
                    {
                        /*console.log(input.attr('name')+' => '+input.val());*/
                        datosGrabar[input.attr('name')] = input.val();
                    }
                });

                datosGrabar['dias']         = $('[name="dias"]').val();
                datosGrabar['tarjeta_id']   = $('select.selector-tarjeta').val();
                datosGrabar['banco_id']     = $('select.selector-banco').val();
                datosGrabar['plan_pago_id'] = form.attr('data-plan-id');
                datosGrabar['salesrule_id_no_acumulables'] = $('[name="promociones_no_acumulables"]').val();

                datosGrabar.cuotas = [];

                $.each(cuotas,function(i,val)
                {
                    var li              = $(val);
                    var cuota           = li.children("input[name='cuota']");
                    var interes         = li.children("input[name='interes']");
                    var reintegro       = li.children("input[name='reintegro']");
                    var tipo_reintegro  = li.children("select[name='tipo_reintegro']");
                    var descuento       = li.children("input[name='descuento']");
                    var tipo_descuento  = li.children("select[name='tipo_descuento']");
                    var cuota_enviar    = li.children("input[name='cuota_enviar']");

                    datosGrabar.cuotas[i] = new Object();
                    datosGrabar.cuotas[i][cuota.attr('name')]           = cuota.val();
                    datosGrabar.cuotas[i][interes.attr('name')]         = interes.val();
                    datosGrabar.cuotas[i][reintegro.attr('name')]       = reintegro.val();
                    datosGrabar.cuotas[i][tipo_reintegro.attr('name')]  = tipo_reintegro.val();
                    datosGrabar.cuotas[i][descuento.attr('name')]       = descuento.val();
                    datosGrabar.cuotas[i][tipo_descuento.attr('name')]  = tipo_descuento.val();
                    datosGrabar.cuotas[i][cuota_enviar.attr('name')]    = cuota_enviar.val();
                });

                /** @TODO Descomentar cuando se implemente la asignacion de un plan a determinados productos unicamente **/
                /**datosGrabar['productos_asociados'] = this.getProductosSeleccionados();*/

                /*console.log(datosGrabar);*/

                $.ajax(this.saveUrl,
                    {
                        type    : "post",
                        data    : datosGrabar,
                        context : this,
                        success : function (response)
                        {
                            if(response.estado==1)
                            {
                                window.location.href = this.adminUrl;
                                console.log('Plan guardado correctamente');
                            }
                            else
                            {
                                $('.adminplanes-loader').hide();
                                alert('Se produjo un error al intentar guardar el plan, por favor intentelo nuevamente');
                            }
                        },
                        error   : function (e, status)
                        {
                            $('.adminplanes-loader').hide();
                            console.log(e);
                            alert("Se produjo un error al intentar guardar el plan, por favor intentelo nuevamente");
                        }
                    });
            },
            mostrarInfoPrioridad: function() 
            {
                $('#info-prioridad').show();
            },
            ocultarInfoPrioridad: function() 
            {
                $('#info-prioridad').hide();
            },
            mostrarInfoInteres: function() 
            {
                $('#info-interes').show();
            },
            ocultarInfoInteres: function() 
            {
                $('#info-interes').hide();
            },
            mostrarInfoCuota: function() 
            {
                $('#info-cuota').show();
            },
            ocultarInfoCuota: function() 
            {
                $('#info-cuota').hide();
            },
            limpiarSelectPromociones: function(data, event)
            {
                var checkbox = $('#' + event.target.id);

                if(!checkbox.prop('checked'))
                {
                    checkbox.parent().children('.promociones-box')
                        .children('[name="promociones_no_acumulables"]').val('');
                }
                return true;
            }
        });
    }
);
