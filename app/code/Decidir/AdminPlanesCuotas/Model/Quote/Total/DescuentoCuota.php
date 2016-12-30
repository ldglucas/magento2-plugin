<?php

namespace Decidir\AdminPlanesCuotas\Model\Quote\Total;

/**
 * Class DescuentoCuota
 *
 */
class DescuentoCuota extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    /**
     * Collect grand total address amount
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this
     */
    protected $quoteValidator = null;

    /**
     * @var string
     */
    protected $_code = 'descuento_cuota';

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $_quoteRepository;

    /**
     * DescuentoCuota constructor.
     * @param \Magento\Quote\Model\QuoteValidator $quoteValidator
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     */
    public function __construct(
        \Magento\Quote\Model\QuoteValidator $quoteValidator,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
    )
    {
        $this->quoteValidator    = $quoteValidator;
        $this->_checkoutSession  = $checkoutSession;
        $this->_quoteRepository  = $quoteRepository;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        $descuento = $quote->getDescuentoCuota();

        if($descuento > 0)
        {
            if($this->_checkoutSession->getAplicarDescuento())
            {
                if($shippingAssignment->getShipping()->getAddress()->getAddressType() == 'shipping')
                    $this->_checkoutSession->setCollectTotalShipping(true);
                if($shippingAssignment->getShipping()->getAddress()->getAddressType() == 'billing')
                    $this->_checkoutSession->setCollectTotalBilling(true);

                $total->addTotalAmount($this->getCode(), -$descuento);
                $total->addBaseTotalAmount($this->getCode(), -$descuento);

                $total->setDescuentoCuota($descuento);
                $total->setBaseDescuentoCuota($descuento);

                $total->setGrandTotal($total->getGrandTotal() + $descuento);
                $total->setBaseGrandTotal($total->getBaseGrandTotal() + $descuento);

                $total->setSubtotalWithDiscount($total->getSubtotal() + $total->getDiscountAmount());
                $total->setBaseSubtotalWithDiscount($total->getBaseSubtotal() + $total->getBaseDiscountAmount());

                if($this->_checkoutSession->getCollectTotalBilling() && $this->_checkoutSession->getCollectTotalShipping())
                {
                    $this->_checkoutSession->setCollectTotalShipping(false);
                    $this->_checkoutSession->setCollectTotalBilling(false);
                    $this->_checkoutSession->setAplicarDescuento(false);
                }
            }
            else
            {
                if(!$this->_checkoutSession->getFinalizacionCompra())
                {
                    $quote->setGrandTotal($quote->getGrandTotal() + $descuento);
                    $quote->setBaseGrandTotal($quote->getBaseGrandTotal() + $descuento);
                    $quote->setDescuentoCuota(0);
                    $quote->setDescuentoCuotaDescripcion('');

                    $this->_quoteRepository->save($quote);
                }
                else
                {
                    if($shippingAssignment->getShipping()->getAddress()->getAddressType() == 'shipping')
                        $this->_checkoutSession->setCollectTotalShipping(true);
                    if($shippingAssignment->getShipping()->getAddress()->getAddressType() == 'billing')
                        $this->_checkoutSession->setCollectTotalBilling(true);

                    if($this->_checkoutSession->getCollectTotalBilling() && $this->_checkoutSession->getCollectTotalShipping())
                    {
                        $this->_checkoutSession->setCollectTotalShipping(false);
                        $this->_checkoutSession->setCollectTotalBilling(false);
                        //$this->_checkoutSession->setFinalizacionCompra(false);
                    }
                }
            }
       }

        return $this;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return array
     */
    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        $descuento = $quote->getDescuentoCuota();

        if($descuento !=0)
            $descuento = '-'.$descuento;

        return [
            'code'  => $this->getCode(),
            'title' => $quote->getDescuentoCuotaDescripcion(),
            'value' => $descuento
        ];
    }

    /**
     * Get Subtotal label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __('Descuento por cuota');
    }
}