<?php

namespace Decidir\AdminPlanesCuotas\Model\ResourceModel;

/**
 * Class Cuota
 *
 * @description ResourceModel para la tabla Cuota
 */
class Cuota extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
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
        $this->_init('decidir_cuota','cuota_id');
    }

}