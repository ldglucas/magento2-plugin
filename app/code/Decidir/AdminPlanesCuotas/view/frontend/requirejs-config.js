/**
 *
 * @description Sobreescritura del js que manejan la logica y renderizacion de los metodos de pago en el checkout.
 *
 * @file Magento_Checkout/js/view/payment/default Se sobreescribe para ejecutar una logica de "limpieza" de los formularios
 * y planes de pago seleccionados en el metodo de tarjetas de credito.
 *
 * @type {{map: {*: {Magento_Checkout/js/view/payment/default: string}}}}
 **/
var config = {
    map: {
        '*': {
            'Magento_Checkout/js/view/payment/default':
                'Decidir_AdminPlanesCuotas/js/view/payment/default'
        }
    }
};