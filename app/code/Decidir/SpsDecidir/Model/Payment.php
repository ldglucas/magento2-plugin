<?php

namespace Decidir\SpsDecidir\Model;

use Magento\Sales\Model\Order\Payment\Transaction;
use Magento\Sales\Api\Data\OrderPaymentInterface;


/**
 * Class Payment
 *
 * @description Modelo representativo del metodo de pago SpsDecidir
 */
class Payment extends \Magento\Payment\Model\Method\AbstractMethod
{
    const CODE = 'decidir_spsdecidir';

    /**
     * @var string
     */
    protected $_code = self::CODE;

    /**
     * @var bool
     */
    protected $_canCapture = true;

    /**
     * @var bool
     */
    protected $_isGateway = true;

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canRefund = true;

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canRefundInvoicePartial = true;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var bool
     */
    protected $_isOffline = false;

    /**
     * @var bool
     */
    protected $_canOrder = true;

    /**
     * @var Transaction\BuilderInterface
     */
    protected $transactionBuilder;

    /**
     * Availability option
     *
     * @var bool
     */
    protected $_isInitializeNeeded = false;

    /**
     * @var \Decidir\SpsDecidir\Helper\Data
     */
    protected $_spsHelper;

    /**
     * @var \Decidir\SpsDecidir\Helper\EstadoTransaccion
     */
    protected $_spsTransaccionesHelper;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $_messageManager;

    /**
     * Payment constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory
     * @param \Magento\Payment\Helper\Data $paymentData
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Payment\Model\Method\Logger $logger
     * @param \Magento\Framework\Module\ModuleListInterface $moduleList
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param Transaction\BuilderInterface $transactionBuilder
     * @param \Decidir\SpsDecidir\Helper\Data $spsHelper
     * @param \Decidir\SpsDecidir\Helper\EstadoTransaccion $spsTransaccionesHelper
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface $transactionBuilder,
        \Decidir\SpsDecidir\Helper\Data $spsHelper,
        \Decidir\SpsDecidir\Helper\EstadoTransaccion $spsTransaccionesHelper,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        /**
         * Cuando se agregan dependencias en el metodo de pago, siempre tienen que estar antes del AbstractResource y
         * AbstractDb, ya que sino tira un fatal error...
         */
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        $this->_checkoutSession         = $checkoutSession;
        $this->transactionBuilder       = $transactionBuilder;
        $this->_spsHelper               = $spsHelper;
        $this->_spsTransaccionesHelper  = $spsTransaccionesHelper;
        $this->_messageManager          = $messageManager;

        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * @description Captura los datos del metodo de la orden, luego de ser finalizada desde el checkout y verifica
     *
     * @param \Magento\Framework\DataObject|\Magento\Payment\Model\InfoInterface|Payment $payment
     * @param float $amount
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function order(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        $order                  = $payment->getOrder();
        $helper                 = $this->_spsHelper;
        $spsTransaccionesHelper = $this->_spsTransaccionesHelper;

        $infoOperacionSps = $this->_spsHelper->getInfoTransaccionSPS();

        if($this->_scopeConfig->getValue('payment/decidir_spsdecidir/mode') == \Decidir\SpsDecidir\Model\Webservice::MODE_DEV)
        {
            $helper->log(print_r($infoOperacionSps,true),'info_operacion_sps_post_order.log');
        }

        if(is_array($infoOperacionSps))
        {
            if($infoOperacionSps['estado_transaccion'] == $spsTransaccionesHelper::TRANSACCION_OK
                && $infoOperacionSps['respuesta_sps']['payload']['Answer']['IDESTADO'] == $spsTransaccionesHelper::ESTADO_OPERACION_AUTORIZADA)
            {
                $orderTransactionIdSps = $infoOperacionSps['respuesta_sps']['payload']['Request']['NROOPERACION'];

                $payment->setTransactionId($orderTransactionIdSps);
                $payment->setAdditionalInformation('detalles_pago',$infoOperacionSps['detalles_pago']);

                $status = $this->_scopeConfig->getValue('payment/decidir_spsdecidir/order_status');

                $transaction = $this->transactionBuilder
                    ->setPayment($payment)
                    ->setOrder($order)
                    ->setTransactionId($payment->getTransactionId())
                    ->build(Transaction::TYPE_AUTH);

                $mensajeEstado = sprintf(__('La transacción con número %s para el pedido %s ha sido exitosa.'),
                    $orderTransactionIdSps,$order->getIncrementId());

                $payment->addTransactionCommentsToOrder($transaction, $mensajeEstado);

                $this->invoice($payment,$infoOperacionSps['detalles_pago']);

                $this->_messageManager->addSuccessMessage($mensajeEstado);
            }
            else
            {
                $codigo  = $infoOperacionSps['respuesta_sps']['payload']['Answer']['IDMOTIVO'];
                $tarjeta = $infoOperacionSps['tarjeta_id'];

                $detalleRechazoSPS = $spsTransaccionesHelper->getDetalleRechazo($codigo,$tarjeta);

                $infoPagoRechazado = sprintf(__('Pago rechazado. Nro Orden: %s. Nro. Operacion Decidir: %s Motivo: %s .'),
                    $order->getIncrementId(),$infoOperacionSps['respuesta_sps']['payload']['Request']['NROOPERACION'],
                    $detalleRechazoSPS['descripcion']);

                if($this->_scopeConfig->getValue('payment/decidir_spsdecidir/mode') == \Decidir\SpsDecidir\Model\Webservice::MODE_DEV)
                {
                    $helper->log($infoPagoRechazado, 'pagos_rechazados_sps_' . date('Y_m_d') . '.log');
                }

                $status = \Magento\Sales\Model\Order::STATE_CANCELED;

                $mensajeEstado = sprintf(__('Su pago con tarjeta de crédito fue rechazado. Mensaje devuelto por DECIDIR: %s.'),
                    $detalleRechazoSPS['error_usuario']);

                $this->_messageManager->addErrorMessage($mensajeEstado);

                $order->registerCancellation($mensajeEstado);
                $order->cancel();
            }

            $order->save();

            /**
             * Deja guardado el estado de la compra en la orden de manera que sea visible por el cliente
             */
            $history = $order->addStatusHistoryComment($mensajeEstado, $status);
            $history->setIsVisibleOnFront(true);
            $history->setIsCustomerNotified(true);
            $history->save();
        }

        return $this;
    }

    /**
     * @param OrderPaymentInterface $payment
     * @param String $comment
     * @return \Magento\Sales\Model\Order\Invoice
     */
    protected function invoice(OrderPaymentInterface $payment, $comment)
    {
        /** @var \Magento\Sales\Model\Order\Invoice $invoice */
        $invoice = $payment->getOrder()->prepareInvoice();

        $invoice->register();
        if ($payment->getMethodInstance()->canCapture()) {
            $invoice->capture();
        }

        $payment->getOrder()->addRelatedObject($invoice);

        $invoice->addComment(
            $comment,
            true,
            true
        );

        return $invoice;
    }

    /**
     * Determine method availability based on quote amount and config data
     *
     * @param \Magento\Quote\Api\Data\CartInterface|null $quote
     * @return bool
     */
    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        return true;
    }
}
