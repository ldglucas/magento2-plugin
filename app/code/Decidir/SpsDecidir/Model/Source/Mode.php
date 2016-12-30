<?php

namespace Decidir\SpsDecidir\Model\Source;

/**
 * Class Mode
 *
 * @description Opciones customizadas para seleccionar el modo habilitado del metodo de pago.
 */
class Mode implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            'dev'=>'Sandbox',
            'prod'=>'Produccion'
        ];
    }
}
