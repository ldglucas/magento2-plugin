/**
 *
 */
define(
    ['ko'],
    function (ko) {
        'use strict';

        var tarjetas = window.checkoutConfig.payment.tarjetasCreditoDisponibles;
        var planes   = window.checkoutConfig.payment.planesTarjetaCredito;
        var bancos   = window.checkoutConfig.payment.bancosDisponibles;
        var cuotas   = window.checkoutConfig.payment.cuotasPorPlanDisponibles;

        return {
            getTarjetasDisponibles: function()
            {
                return tarjetas;
            },
            getBancosDisponibles: function ()
            {
                return bancos;
            },
            getPlanesDisponibles: function ()
            {
                return planes;
            },
            getCuotasDisponibles: function ()
            {
                return cuotas;
            }
        };
    }
);
