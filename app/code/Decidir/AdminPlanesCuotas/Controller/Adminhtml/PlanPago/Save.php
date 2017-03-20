<?php

namespace Decidir\AdminPlanesCuotas\Controller\Adminhtml\PlanPago;

/**
 * Class Save
 *
 * @description Action para guardar planes de pago via ajax.
 *
 */
class Save extends  \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected   $_resultPageFactory;

    /**
     * @var \Decidir\AdminPlanesCuotas\Model\PlanPagoFactory
     */
    protected   $_planPagoFactory;

    /**
     * @var \Decidir\AdminPlanesCuotas\Model\CuotaFactory
     */
    protected   $_cuotaFactory;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected   $_resultJsonFactory;

    /**
     * @var \Decidir\AdminPlanesCuotas\Model\PlanPagoProductoFactory
     */
    protected $_planPagoProductoFactory;

    const OPERACION_EXITOSA = 1;
    const OPERACION_FALLIDA = 0;

    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Decidir\AdminPlanesCuotas\Model\PlanPagoFactory $planPagoFactory
     * @param \Decidir\AdminPlanesCuotas\Model\CuotaFactory $cuotaFactory
     * @param \Decidir\AdminPlanesCuotas\Model\PlanPagoProductoFactory $productosAsociadosFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(\Magento\Backend\App\Action\Context $context,
                                \Magento\Framework\View\Result\PageFactory $resultPageFactory,
                                \Decidir\AdminPlanesCuotas\Model\PlanPagoFactory $planPagoFactory,
                                \Decidir\AdminPlanesCuotas\Model\CuotaFactory $cuotaFactory,
                                \Decidir\AdminPlanesCuotas\Model\PlanPagoProductoFactory $productosAsociadosFactory,
                                \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->_resultPageFactory         = $resultPageFactory;
        $this->_planPagoFactory           = $planPagoFactory;
        $this->_cuotaFactory              = $cuotaFactory;
        $this->_resultJsonFactory         = $resultJsonFactory;
        $this->_planPagoProductoFactory   = $productosAsociadosFactory;

        parent::__construct($context);
    }

    /**
     * @return $this
     */
    public function execute()
    {
        $request    = $this->getRequest();
        $result = $this->_resultJsonFactory->create();

        if($request->isXmlHttpRequest())
        {
            $postParams = $request->getPost()->toArray();

            if(!isset($postParams['nombre']) ||  strlen(trim($postParams['nombre'])) < 5
            || !isset($postParams['vigente_desde']) || !isset($postParams['vigente_hasta']))
                return $result->setData(['estado'=>self::OPERACION_FALLIDA]);

            if(!isset($postParams['cuotas']) || !count($postParams['cuotas']) ||
                !isset($postParams['dias']) || !count($postParams['dias']))
                return $result->setData(['estado'=>self::OPERACION_FALLIDA]);

            foreach($postParams['cuotas'] as $_cuota)
            {
                if(!$_cuota['cuota'])
                    return $result->setData(['estado'=>self::OPERACION_FALLIDA]);
            }

            $planPago = $this->_planPagoFactory->create();

            /**
             * Si viene un plan de pago id distinto de cero es una actualiacion del plan
             */
            if($postParams['plan_pago_id'])
            {
                $planPago = $planPago->load($postParams['plan_pago_id']);
            }

            if(isset($postParams['salesrule_id_no_acumulables']) && count($postParams['salesrule_id_no_acumulables'])
                && $postParams['salesrule_id_no_acumulables']!='')
                $planPago->setSalesruleIdNoAcumulables(implode(',',$postParams['salesrule_id_no_acumulables']));
            else
                $planPago->setSalesruleIdNoAcumulables('');

            $planPago->setNombre(trim($postParams['nombre']));
            $planPago->setTarjetaId($postParams['tarjeta_id']);
            $planPago->setBancoId($postParams['banco_id']);
            $planPago->setVigenteDesde(date('Y-m-d H:i:s',strtotime($postParams['vigente_desde'])));
            $planPago->setVigenteHasta(date('Y-m-d H:i:s',strtotime($postParams['vigente_hasta'])));
            $planPago->setPrioridad($postParams['prioridad']);
            $planPago->setDias(implode(',',$postParams['dias']));
            $planPago->setActivo($postParams['activo']);

            if($planPago->save())
            {
                if($postParams['plan_pago_id'])
                {
                    $cuotasPlan = $this->_cuotaFactory->create();
                    $cuotasPlan->deleteAll($planPago->getId());
                }

                foreach($postParams['cuotas'] as $_cuota)
                {
                    $cuotasPlan = $this->_cuotaFactory->create();

                    $cuotasPlan->setPlanPagoId($planPago->getId());
                    $cuotasPlan->setCuota($_cuota['cuota']);
                    $cuotasPlan->setInteres($_cuota['interes']);
                    $cuotasPlan->setReintegro($_cuota['reintegro']);
                    $cuotasPlan->setTipoReintegro($_cuota['tipo_reintegro']);
                    $cuotasPlan->setDescuento($_cuota['descuento']);
                    $cuotasPlan->setTipoDescuento($_cuota['tipo_descuento']);

                    if(isset($_cuota['cuota_enviar']) && !is_null($_cuota['cuota_enviar']))
                        $cuotasPlan->setCuotaEnviar($_cuota['cuota_enviar']);

                    $cuotasPlan->save();
                }

                $planPagoProducto = $this->_planPagoProductoFactory->create();
                $planPagoProducto->deleteAll($planPago->getId());

                if(isset($postParams['productos_asociados']) && count($postParams['productos_asociados']))
                {
                    foreach($postParams['productos_asociados'] as $_entityId)
                    {
                        $planPagoProducto = $this->_planPagoProductoFactory->create();

                        $planPagoProducto->setPlanPagoId($planPago->getId());
                        $planPagoProducto->setEntityIdProducto($_entityId);

                        $planPagoProducto->save();
                    }
                }

                $this->messageManager->addSuccessMessage(__('La información fue guardada correctamente.'));

                return $result->setData(['estado'=>self::OPERACION_EXITOSA]);
            }
            else
            {
                return $result->setData(['estado'=>self::OPERACION_FALLIDA]);
            }
        }
        return $result->setData(['estado'=>self::OPERACION_FALLIDA]);
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
       return $this->_authorization->isAllowed('Decidir_AdminPlanesCuotas::admin');
    }

}
