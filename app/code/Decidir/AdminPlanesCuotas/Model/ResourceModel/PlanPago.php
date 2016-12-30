<?php

namespace Decidir\AdminPlanesCuotas\Model\ResourceModel;

/**
 * Class PlanPago
 *
 * @description ResourceModel para la tabla de Plan de pagos
 */
class PlanPago extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
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
        $this->_init('decidir_plan_pago','plan_pago_id');
    }


    /**
     * Process post data before saving. Se podrian agregar validaciones antes de grabar un plan.
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
//    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
//    {
//
//        if (!$this->isValidPostUrlKey($object)) {
//            throw new \Magento\Framework\Exception\LocalizedException(
//                __('The post URL key contains capital letters or disallowed symbols.')
//            );
//        }
//
//        if ($this->isNumericPostUrlKey($object)) {
//            throw new \Magento\Framework\Exception\LocalizedException(
//                __('The post URL key cannot be made of only numbers.')
//            );
//        }
//
//        if ($object->isObjectNew() && !$object->hasCreationTime()) {
//            $object->setCreationTime($this->_date->gmtDate());
//        }
//
//        $object->setUpdateTime($this->_date->gmtDate());
//
//        return parent::_beforeSave($object);
//    }


}