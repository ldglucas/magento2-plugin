define(
    [
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/quote',
        'Magento_Catalog/js/price-utils',
        'Magento_Checkout/js/model/totals'
    ],
    function (
        Component,
        quote,
        priceUtils,
        totals
    )
    {
        "use strict";
        return Component.extend({
            defaults: {
                isFullTaxSummaryDisplayed: false
            },
            totals: quote.getTotals(),
            isTaxDisplayedInGrandTotal: false,
            isDisplayed: function()
            {
                return totals.getSegment('descuento');
            },
            getValue: function()
            {
                var price = 0;
                if (this.getDescuentoCuotaDisponible())
                {
                    price = totals.getSegment('descuento').value;
                }
                return this.getFormattedPrice(price);
            },
            getDescuentoCuotaDisponible: function ()
            {
                return true;
            }
        });
    }
);