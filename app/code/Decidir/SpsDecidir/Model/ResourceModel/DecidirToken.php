<?php

namespace Decidir\SpsDecidir\Model\ResourceModel;

/**
 * Class DecidirToken
 *
 * @description ResourceModel para la tabla de token de pago de decidir

 */
class DecidirToken extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
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
        $this->_init('decidir_token','decidir_token_id');
    }

}