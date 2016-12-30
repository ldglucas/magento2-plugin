
/*browser:true*/
/*global define*/
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'decidir_spsdecidir',
                component: 'Decidir_SpsDecidir/js/view/payment/method-renderer/decidir-method'
            }
        );
        /** Add view logic here if needed */
        return Component.extend({
            test:function ()
            {
                console.log(Math.random());
            }
        });
    }
);