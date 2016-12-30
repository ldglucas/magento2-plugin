<?php

namespace Decidir\AdminPlanesCuotas\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class Banco
 *
 */
class Banco extends AbstractModel
{
    protected $_eventPrefix = 'decidir_banco';

    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'adminplanescuotas_banco';

    /**
     * True if data changed
     *
     * @var bool
     */
    protected $_isStatusChanged = false;

    protected $_cresource;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\ResourceConnection $cresource,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_cresource = $cresource;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Inicia el resource model
     */
    protected function _construct()
    {
        $this->_init('Decidir\AdminPlanesCuotas\Model\ResourceModel\Banco');
    }

    /**
     * @return false|\Magento\Framework\DB\Adapter\AdapterInterface
     */
    public function getConnection()
    {
        return $this->_cresource->getConnection();
    }


}