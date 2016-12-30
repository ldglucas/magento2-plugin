<?php

namespace Decidir\AdminPlanesCuotas\Block\Catalog\Product\View;

use Decidir\AdminPlanesCuotas\Block\PlanPago;
use Magento\Framework\View\Element\Template;

/**
 * Class CalculadorCuotas
 *
 * @description
 *
 */
class CalculadorCuotas extends PlanPago
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * CalculadorCuotas constructor.
     * @param Template\Context $context
     * @param array $data
     * @param \Decidir\AdminPlanesCuotas\Model\TarjetaFactory $medioPagoFactory
     * @param \Decidir\AdminPlanesCuotas\Model\BancoFactory $bancoFactory
     * @param \Decidir\AdminPlanesCuotas\Model\CuotaFactory $cuotaFactory
     * @param \Decidir\AdminPlanesCuotas\Model\PlanPagoFactory $planPagoFactory
     * @param \Decidir\AdminPlanesCuotas\Helper\Data $helper
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct
    (
        Template\Context $context,
        array $data,
        \Decidir\AdminPlanesCuotas\Model\TarjetaFactory $medioPagoFactory,
        \Decidir\AdminPlanesCuotas\Model\BancoFactory $bancoFactory,
        \Decidir\AdminPlanesCuotas\Model\CuotaFactory $cuotaFactory,
        \Decidir\AdminPlanesCuotas\Model\PlanPagoFactory $planPagoFactory,
        \Decidir\AdminPlanesCuotas\Helper\Data $helper,
        \Magento\Framework\Registry $registry
    )
    {
        $this->_registry = $registry;

        parent::__construct($context, $data, $medioPagoFactory, $bancoFactory, $cuotaFactory, $planPagoFactory, $helper);
    }

    /**
     * @return float
     */
    public function getMontoFinanciacion()
    {
        return $this->_registry->registry('current_product')->getFinalPrice();
    }

}