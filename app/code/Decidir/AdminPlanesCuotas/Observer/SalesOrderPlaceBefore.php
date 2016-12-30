<?php
namespace Decidir\AdminPlanesCuotas\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class SalesEventQuoteSubmitBeforeObserver
 */
class SalesOrderPlaceBefore implements ObserverInterface
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $_quoteRepository;

    /**
     * SalesOrderPlaceBefore constructor.
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
    )
    {
        $this->_checkoutSession = $checkoutSession;
        $this->_quoteRepository  = $quoteRepository;
    }

    /**
     * Graba en la orden el numero de sucursal andreani que tenga el quote, y el dni en el quote address
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $quote = $this->_quoteRepository->getActive($order->getQuoteId());

        $order->setDescuentoCuota($quote->getDescuentoCuota());
        $order->setDescuentoCuotaDescripcion($quote->getDescuentoCuotaDescripcion());

        return $this;
    }
}
