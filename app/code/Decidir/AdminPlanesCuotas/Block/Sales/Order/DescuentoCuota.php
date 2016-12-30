<?php

namespace Decidir\AdminPlanesCuotas\Block\Sales\Order;


class DescuentoCuota extends \Magento\Framework\View\Element\Template
{
    /**
     * Tax configuration model
     *
     * @var \Magento\Tax\Model\Config
     */
    protected $_config;

    /**
     * @var Order
     */
    protected $_order;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_source;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Tax\Model\Config $taxConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Tax\Model\Config $taxConfig,
        array $data = []
    ) {
        $this->_config = $taxConfig;
        parent::__construct($context, $data);
    }

    /**
     * Get data (totals) source model
     *
     * @return \Magento\Framework\DataObject
     */
    public function getSource()
    {
        return $this->_source;
    }

    /**
     * @return mixed
     */
    public function getStore()
    {
        return $this->_order->getStore();
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * @return array
     */
    public function getLabelProperties()
    {
        return $this->getParentBlock()->getLabelProperties();
    }

    /**
     * @return array
     */
    public function getValueProperties()
    {
        return $this->getParentBlock()->getValueProperties();
    }

    /**
     * Initialize all order totals relates with tax
     *
     * @return \Magento\Tax\Block\Sales\Order\Tax
     */
    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $this->_order  = $parent->getOrder();
        $this->_source = $parent->getSource();

        $order = $this->getOrder();

        if($order->getDescuentoCuota()>0)
        {
            $descuentoCuota = new \Magento\Framework\DataObject(
                [
                    'code'   => 'descuento',
                    'strong' => false,
                    'value'  => -$order->getDescuentoCuota(),
                    'base_value' => -$order->getDescuentoCuota(),
                    'label'  => $order->getDescuentoCuotaDescripcion() ,
                ]
            );

            $parent->addTotal($descuentoCuota, 'descuento');
            $parent->getTotal('grand_total')->setValue($parent->getTotal('grand_total')->getValue() - $order->getDescuentoCuota());

            if($parent->getTotal('paid'))
                $parent->getTotal('paid')->setValue($parent->getTotal('paid')->getValue() - $order->getDescuentoCuota());

        }

        return $this;
    }

}