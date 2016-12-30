<?php

namespace Decidir\AdminPlanesCuotas\Model\ResourceModel;

/**
 * Class Banco
 *
 * @description ResourceModel para la tabla Banco
 */
class Banco extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
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
        $this->_init('decidir_banco','banco_id');
    }

}