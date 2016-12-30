define(
    [
        'ko',
        'Decidir_AdminPlanesCuotas/js/view/checkout/summary/descuento'
    ],
    function (ko,Component)
    {
        'use strict';

        return Component.extend(
        {
            defaults:
            {
                descuentoCuotaVisible : ko.observable(false),
                title: ko.observable('Descuento por cuota')
            },
            initialize: function ()
            {
                this._super();
            },
            /**
             * @override
             */
            isDisplayed: function () {
                return false;
            }
        });
    }
);