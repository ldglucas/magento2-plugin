<?php

namespace Decidir\AdminPlanesCuotas\Model\ResourceModel;

/**
 * Class Tarjeta
 *
 * @description ResourceModel para la tabla Tarjeta
 */
class Tarjeta extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
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

    public function _construct()
    {
        $this->_init('decidir_tarjeta','tarjeta_id');
    }

}