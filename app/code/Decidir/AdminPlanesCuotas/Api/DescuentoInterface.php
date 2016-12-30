<?php
namespace Decidir\AdminPlanesCuotas\Api;

/**
 * Interface DescuentoInterface
 *
 */
interface DescuentoInterface
{
    /**
     * @description Agrega un descuento al quote dependiendo del plan de pago
     *
     * @param int $planPagoId ID del plan de pago.
     * @param int $cuota numero de cuota.
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException The specified cart does not exist.
     * @throws \Magento\Framework\Exception\CouldNotSaveException The specified coupon could not be added.
     */
    public function set($planPagoId,$cuota);

    /**
     * @description Elimina los descuentos por cuota del quote
     *
     * @return mixed
     */
    public function reset();
}