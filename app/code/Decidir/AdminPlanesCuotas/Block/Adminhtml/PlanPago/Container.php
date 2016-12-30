<?php

namespace Decidir\AdminPlanesCuotas\Block\Adminhtml\PlanPago;

use Magento\Backend\Block\Template;

/**
 * Class Container
 *
 * @description Bloque para administrar los metodos de pago disponibles desde el administrador¡
 */
class Container extends Template
{
    /**
     * @var \Decidir\AdminPlanesCuotas\Model\TarjetaFactory
     */
    protected $_tarjetasFactory;

    /**
     * @var \Decidir\AdminPlanesCuotas\Model\BancoFactory
     */
    protected $_bancoFactory;

    /**
     * @var \Decidir\AdminPlanesCuotas\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\SalesRule\Model\RuleFactory
     */
    protected $_ruleFactory;

    /**
     * Container constructor.
     * @param Template\Context $context
     * @param array $data
     * @param \Decidir\AdminPlanesCuotas\Model\TarjetaFactory $medioPagoFactory
     * @param \Decidir\AdminPlanesCuotas\Model\BancoFactory $bancoFactory
     * @param \Decidir\AdminPlanesCuotas\Helper\Data $helper
     */
    public function __construct(
        Template\Context $context,
        array $data = [],
        \Decidir\AdminPlanesCuotas\Model\TarjetaFactory $medioPagoFactory,
        \Decidir\AdminPlanesCuotas\Model\BancoFactory $bancoFactory,
        \Decidir\AdminPlanesCuotas\Helper\Data $helper,
        \Magento\SalesRule\Model\RuleFactory $ruleFactory
    )
    {
        $this->_tarjetasFactory     = $medioPagoFactory;
        $this->_bancoFactory        = $bancoFactory;
        $this->_ruleFactory         = $ruleFactory;
        $this->_helper              = $helper;

        parent::__construct($context, $data);
    }

    /**
     * @description Retorna el listado de tarjetas
     *
     * @return array
     */
    public function getTarjetas()
    {
        $tarjetaCollection = $this->_tarjetasFactory
            ->create()
            ->getCollection()
            ->addFieldToFilter('activo', ['eq' => 1]);

        return $tarjetaCollection->getData();
    }

    /**
     * @description Retorna el listado de bancos
     *
     * @return array
     */
    public function getBancos()
    {
        $bancoCollection = $this->_bancoFactory
            ->create()
            ->getCollection();

        return $bancoCollection->getData();
    }

    /**
     * @description Retorna el listado de dias
     *
     * @return array
     */
    public function getDias()
    {
        return $this->_helper->getDiasSemana();
    }

    /**
     * @description Retorna las reglas de carrito que haya en el sitio
     *
     * @return array
     */
    public function getPromocionesCarrito()
    {
        $reglasCarrito = $this->_ruleFactory
            ->create()
            ->getCollection()
            ->addFieldToFilter('is_active', ['eq' => 1]);

        $reglasCarrito->getSelect()
            ->reset(\Magento\Framework\DB\Select::COLUMNS)
            ->columns(['rule_id','name']);

        return $reglasCarrito->getData();
    }

}