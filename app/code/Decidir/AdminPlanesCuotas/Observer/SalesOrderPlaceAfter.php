<?php
namespace Decidir\AdminPlanesCuotas\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class SalesEventQuoteSubmitBeforeObserver
 */
class SalesOrderPlaceAfter implements ObserverInterface
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * SalesOrderPlaceBefore constructor.
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession
    )
    {
        $this->_checkoutSession = $checkoutSession;
    }

    /**
     * Resetea la variable que condiciona el 'reset' de descuentos aplicados por una cuota en el checkout
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->_checkoutSession->setFinalizacionCompra(false);

        return $this;
    }
}
