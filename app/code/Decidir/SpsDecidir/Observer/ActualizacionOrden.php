<?php

namespace Decidir\SpsDecidir\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\DataObject as Object;
use Magento\Framework\Event\ObserverInterface;
use Decidir\SpsDecidir\Helper\EstadoTransaccion;
use Decidir\SpsDecidir\Helper\Data;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;


/**
 * Class ActualizacionOrden
 *
 * @description Observer que cancela la orden en caso de que SPS devuelva que la operacion no fue concretada.
 */
class ActualizacionOrden implements ObserverInterface
{
    /**
     * @var \Decidir\SpsDecidir\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Decidir\SpsDecidir\Helper\EstadoTransaccion
     */
    protected $_helperEstadoTransaccion;

    /**
     * @var ManagerInterface
     */
    protected $_messageManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * ActualizacionOrden constructor.
     * @param EstadoTransaccion $helperEstadoTransaccion
     * @param Data $helper
     * @param ManagerInterface $messageManager
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        EstadoTransaccion $helperEstadoTransaccion,
        Data $helper,
        ManagerInterface $messageManager,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->_helperEstadoTransaccion = $helperEstadoTransaccion;
        $this->_helper                  = $helper;
        $this->_messageManager          = $messageManager;
        $this->_scopeConfig             = $scopeConfig;
    }

    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $order   = $observer->getOrder();
        $payment = $order->getPayment();

        if($payment->getMethod() == \Decidir\SpsDecidir\Model\Payment::CODE)
        {
            $helper  = $this->_helper;
            $helperTransaccion  = $this->_helperEstadoTransaccion;

            $infoOperacionSps =  $helper->getInfoTransaccionSPS();

            $estadoOperacion = $infoOperacionSps['respuesta_sps']['payload']['Answer']['IDESTADO'];

            if($infoOperacionSps['estado_transaccion'] != $helperTransaccion::TRANSACCION_OK
                || $estadoOperacion != $helperTransaccion::ESTADO_OPERACION_AUTORIZADA)
            {
                $codigo  = $infoOperacionSps['respuesta_sps']['payload']['Answer']['IDMOTIVO'];
                $tarjeta = $infoOperacionSps['tarjeta_id'];

                $detalleRechazoSPS = $helperTransaccion->getDetalleRechazo($codigo,$tarjeta);

                $order->registerCancellation($detalleRechazoSPS['error_usuario']);
                $order->cancel();

                $payment->setState(\Magento\Sales\Model\Order::STATE_CANCELED);
                $order->setState(\Magento\Sales\Model\Order::STATE_CANCELED)
                    ->setStatus($order->getConfig()->getStateDefaultStatus(\Magento\Sales\Model\Order::STATE_CANCELED));

                $order->save();
            }
            else
            {
                $orderStatus = $this->_scopeConfig->getValue('payment/decidir_spsdecidir/order_status');
                $order->setStatus($orderStatus);
                $order->save();
            }
        }

        return $this;
    }
}
