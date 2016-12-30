<?php

namespace Decidir\AdminPlanesCuotas\Block\Adminhtml\PlanPago\Edit;

use Magento\Backend\Block\Template;

/**
 * Class Container
 *
 * @description Bloque para editar un plan de pago.
 */
class Container extends \Decidir\AdminPlanesCuotas\Block\Adminhtml\PlanPago\Container
{
    /**
     * @var \Decidir\AdminPlanesCuotas\Model\Session
     */
    protected $_session;

    /**
     * @var \Decidir\AdminPlanesCuotas\Model\CuotaFactory
     */
    protected $_cuotasFactory;

    /**
     * @var \Magento\SalesRule\Model\RuleFactory
     */
    protected $_ruleFactory;

    /**
     * @var
     */
    private   $_planPagoId;

    /**
     * Container constructor.
     * @param Template\Context $context
     * @param array $data
     * @param \Decidir\AdminPlanesCuotas\Model\TarjetaFactory $medioPagoFactory
     * @param \Decidir\AdminPlanesCuotas\Model\BancoFactory $bancoFactory
     * @param \Decidir\AdminPlanesCuotas\Model\CuotaFactory $cuotasFactory
     * @param \Decidir\AdminPlanesCuotas\Helper\Data $helper
     * @param \Decidir\AdminPlanesCuotas\Model\Session $adminplanescuotasSession
     * @param \Magento\SalesRule\Model\RuleFactory $ruleFactory
     */
    public function __construct(
        Template\Context $context,
        array $data = [],
        \Decidir\AdminPlanesCuotas\Model\TarjetaFactory $medioPagoFactory,
        \Decidir\AdminPlanesCuotas\Model\BancoFactory $bancoFactory,
        \Decidir\AdminPlanesCuotas\Model\CuotaFactory $cuotasFactory,
        \Decidir\AdminPlanesCuotas\Helper\Data $helper,
        \Decidir\AdminPlanesCuotas\Model\Session $adminplanescuotasSession,
        \Magento\SalesRule\Model\RuleFactory $ruleFactory
    )
    {
        $this->_session       = $adminplanescuotasSession;
        $this->_cuotasFactory = $cuotasFactory;
        $this->_ruleFactory         = $ruleFactory;

        parent::__construct($context,$data,$medioPagoFactory,$bancoFactory,$helper,$ruleFactory);
    }

    /**
     * @return mixed
     */
    public function getPlanPago()
    {
        $planPago = $this->_session->getPlanPago();

        $this->_planPagoId = $planPago->getPlanPagoId();

        return $planPago->getData();
    }

    /**
     * @return mixed
     */
    public function getCuotas()
    {
        $cuotas = $this->_cuotasFactory->create()
            ->getCollection()
            ->addFieldToFilter('plan_pago_id',['eq'=>$this->_planPagoId]);

        return $cuotas->getData();
    }

}