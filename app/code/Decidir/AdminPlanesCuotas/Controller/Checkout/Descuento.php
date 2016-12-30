<?php

namespace Decidir\AdminPlanesCuotas\Controller\Checkout;

/**
 * Class Descuento
 *
 * @description
 *
 */
class Descuento extends \Magento\Framework\App\Action\Action
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var OrderRepositoryInterface
     */
    protected $_orderRepository;

    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var Cart
     */
    protected $_cart;

    /**
     * @var \Decidir\AdminPlanesCuotas\Model\CuotaFactory
     */
    protected $_cuotaFactory;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;


    /**
     * Descuento constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepositoryInterface
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Checkout\Model\Cart $cart
     * @param \Decidir\AdminPlanesCuotas\Model\CuotaFactory $cuotaFactory
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepositoryInterface,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Checkout\Model\Cart $cart,
        \Decidir\AdminPlanesCuotas\Model\CuotaFactory $cuotaFactory,
        \Magento\Checkout\Model\Session $checkoutSession
    )
    {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_orderRepository   = $orderRepositoryInterface;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_cart              = $cart;
        $this->_cuotaFactory      = $cuotaFactory;
        $this->_checkoutSession   = $checkoutSession;

        parent::__construct($context);
    }

    /**
     * @TODO Ver la manera de aplicar el descuento al carrito cuando se selecciona un plan y cuota que lo posean.
     *
     * @return $this
     */
    public function execute()
    {
//        $layout = $this->_view->getLayout();
//
//        $this->_checkoutSession->setCartWasUpdated(true);
//
//        $totalsBlock = $layout->createBlock('Decidir\AdminPlanesCuotas\Block\Sales\Order\DescuentoCuota')
//            ->setTemplate('Magento_Checkout::cart/totals.phtml');
//
//        return $totalsBlock->toHtml();


//        $resultPage = $this->_resultPageFactory->create();
//        $this->_checkoutSession->setCartWasUpdated(true);
//        $resultPage->getLayout()->getBlock('checkout.cart.totals');
//        return $resultPage;

//        $request = $this->getRequest();
        $result = $this->_resultJsonFactory->create();

//        if ($request->isXmlHttpRequest() && ($planPagoId = $request->getParam('plan_pago_id'))
//            && ($cuota = $request->getParam('cuota')))
//        {
//            $cuotaFactory = $this->_cuotaFactory->create()
//                ->getCollection()
//                ->addFieldToFilter('plan_pago_id',['eq'=>$planPagoId])
//                ->addFieldToFilter('cuota',['eq'=>$cuota])
//                ->getFirstItem();
//        }

        return $result->setData([]);
    }
}