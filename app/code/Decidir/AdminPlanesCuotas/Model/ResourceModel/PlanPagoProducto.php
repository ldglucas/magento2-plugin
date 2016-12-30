<?php

namespace Decidir\AdminPlanesCuotas\Model\ResourceModel;

/**
 * Class PlanPagoProducto
 *
 * @description ResourceModel para la tabla de Plan de pagos y productos asociados
 */
class PlanPagoProducto extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Construct
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param string|null $resourcePrefix
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
    }

    /**
     * @note Primary Key de la tabla NO UTILIZADA, POR ESO SE PONE UNA FICTICIA.
     */
    public function _construct()
    {
        $this->_init('decidir_plan_pago_producto','plan_pago_id_entity_id_producto');
    }

}