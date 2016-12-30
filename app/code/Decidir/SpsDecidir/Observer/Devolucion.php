<?php

namespace Decidir\SpsDecidir\Observer;

use Decidir\SpsDecidir\Model\Webservice;
use Magento\Framework\Event\Observer;
use Magento\Framework\DataObject as Object;
use Magento\Framework\Event\ObserverInterface;
use Decidir\SpsDecidir\Helper\Data;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\ResponseFactory;
use Magento\Framework\App\RequestInterface;

/**
 * Class Devolucion
 *
 * @description Observer que devuelve un monto de dinero al comprador si la orden fue realizada por el modulo de sps. Se
 *              conecta al webservice enviando los datos de la operacion, y DECIDIR devuelve su aprobacion o denegacion.
 *              En caso de ser negativa su respuesta, no se genera el creditmemo de magento.
 *
 */
class Devolucion implements ObserverInterface
{
    /**
     * @var \Decidir\SpsDecidir\Helper\Data
     */
    private $_helper;

    /**
     * @var ManagerInterface
     */
    private $_messageManager;

    /**
     * @var Webservice
     */
    private $_webservice;

    /**
     * @var UrlInterface
     */
    protected $_urlInterface;

    /**
     * @var ResponseFactory
     */
    protected $_responseFactory;

    /**
     * @var RequestInterface
     */
    protected $_request;

    /**
     * Devolucion constructor.
     * @param Data $helper
     * @param ManagerInterface $messageManager
     * @param Webservice $webservice
     * @param ResponseFactory $responseFactory
     * @param UrlInterface $url
     * @param RequestInterface $requestInterface
     */
    public function __construct(
        Data $helper,
        ManagerInterface $messageManager,
        Webservice $webservice,
        ResponseFactory $responseFactory,
        UrlInterface $url,
        RequestInterface $requestInterface
    )
    {
        $this->_helper          = $helper;
        $this->_messageManager  = $messageManager;
        $this->_webservice      = $webservice;
        $this->_urlInterface    = $url;
        $this->_responseFactory = $responseFactory;
        $this->_request         = $requestInterface;

    }

    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        /**
         * @var \Magento\Sales\Model\Order\Creditmemo
         */
        $creditmemo = $observer->getCreditmemo();

        /**
         * @var \Magento\Sales\Model\Order\Invoice
         */
        $invoice    = $creditmemo->getInvoice();

        /**
         * @var \Magento\Sales\Model\Order
         */
        $order      = $invoice->getOrder();

        if($order->getPayment()->getMethod() == \Decidir\SpsDecidir\Model\Payment::CODE)
        {
            $ws = $this->_webservice;

            try {

                if ($order->getGrandTotal() == $creditmemo->getGrandTotal())
                {
                    $devolucion = $ws->devolver($invoice->getTransactionId(),true,$creditmemo->getGrandTotal());
                }
                else
                {
                    $devolucion = $ws->devolver($invoice->getTransactionId(),false,$creditmemo->getGrandTotal());
                }

                $this->_helper->log(print_r($devolucion,true),'devoluciones.log');
                /**
                 * Guardo en el historico de comentarios la respuesta afirmativa de DECIDIR
                 */
                $creditmemo->addComment(
                    $devolucion->getStatusMessage(),
                    true,
                    true
                );

                $this->_messageManager->addSuccessMessage($devolucion->getStatusMessage());

            }
            catch(\Exception $e)
            {
                $this->_helper->log($e,'devoluciones.log');

                $this->_messageManager->addErrorMessage($e->getMessage());

                $RedirectUrl = $this->_urlInterface->getUrl('sales/*/new',[
                    'order_id'=>$order->getId(),
                    'invoice_id'=>$invoice->getId()
                ]);

                $this->_responseFactory->create()->setRedirect($RedirectUrl)->sendResponse();

                exit();
            }
        }

        return $this;
    }
}
