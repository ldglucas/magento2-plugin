<?php

namespace Decidir\SpsDecidir\Controller\Payment;

use Magento\Checkout\Model\Cart;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Zend\Crypt\BlockCipher;
use Decidir\SpsDecidir\Model\Webservice;
use Decidir\AdminPlanesCuotas\Model\CuotaFactory;

/**
 * Class AuthorizeRequest
 *
 * @description Action que se encarga de generar el primer paso de integracion con SPS. Se conecta con el WS, y envia
 *              los datos del comercio, junto al medio de pago elegido, cantidad de cuotas y monto. Si estos datos son
 *              correctos, SPS devuelve una request key y una public rekest key para poder iniciar el formulario de pago,
 *              y proceder con la transaccion.
 *
 */
class AuthorizeRequest extends Action
{
    /**
     * @var PageFactory
     */
    protected   $_resultPageFactory;

    /**
     * @var OrderRepositoryInterface
     */
    protected   $_orderRepository;

    /**
     * @var JsonFactory
     */
    protected   $_resultJsonFactory;

    /**
     * @var Webservice
     */
    protected   $_webservice;

    /**
     * @var CuotaFactory
     */
    protected   $_cuotaFactory;

    /**
     * @var Context
     */
    protected   $_context;

    /**
     * @var Cart
     */
    protected   $_cart;

    /**
     * AuthorizeRequest constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param OrderRepositoryInterface $orderRepositoryInterface
     * @param JsonFactory $resultJsonFactory
     * @param Webservice $webservice
     * @param CuotaFactory $cuotaFactory
     */
    public function __construct
    (
        Context $context,
        PageFactory $resultPageFactory,
        OrderRepositoryInterface $orderRepositoryInterface,
        JsonFactory $resultJsonFactory,
        Webservice $webservice,
        CuotaFactory $cuotaFactory,
        Cart $cart
    ) {
        $this->_resultPageFactory   = $resultPageFactory;
        $this->_orderRepository     = $orderRepositoryInterface;
        $this->_resultJsonFactory   = $resultJsonFactory;
        $this->_webservice          = $webservice;
        $this->_cuotaFactory        = $cuotaFactory;
        $this->_context             = $context;
        $this->_cart                = $cart;

        parent::__construct($context);
    }

    /**
     * @return $this
     */
    public function execute()
    {
        $request = $this->getRequest();

        /**
         * Manera elegante de devolver un JSON con magento2
         */
        $result = $this->_resultJsonFactory->create();

        if ($request->isXmlHttpRequest() && $request->getParam('tarjeta_id') && $request->getParam('cantidad_cuotas')
        && $request->getParam('plan_pago_id'))
        {
            $ws = $this->_webservice;

            if($cuotaEnviar = $request->getParam('cuota_enviar'))
                $cantidadCuotas = $cuotaEnviar;
            else
                $cantidadCuotas = $request->getParam('cantidad_cuotas');

            $cuotaCollection = $this->_cuotaFactory->create()
                ->getCollection()
                ->addFieldToFilter('plan_pago_id',['eq'=>$request->getParam('plan_pago_id')])
                ->addFieldToFilter('cuota',['eq'=>$request->getParam('cantidad_cuotas')]);

            if(!$cuotaCollection->getSize())
                return $result->setData(['rk'=>0,'prk'=>0]);

            $detallesCuota = $cuotaCollection->getFirstItem();
            $quote = $this->_cart->getQuote();

            if(!$detallesCuota->getInteres())
                $montoTransaccion = $quote->getGrandTotal();
            else
                $montoTransaccion = $quote->getGrandTotal() + ($quote->getGrandTotal() * ($detallesCuota->getInteres()/100));

            $authorizeRequest = $ws->sendAuthorizeRequest(
                [
                    'tarjeta_id'        => $request->getParam('tarjeta_id'),
                    'cantidad_cuotas'   => $cantidadCuotas,
                    'monton_transaccion'=> $montoTransaccion
                ]
            );

            if($authorizeRequest->getStatusCode() == $ws::OPERACION_OK)
            {
                /**
                 * @NOTE Segun la documentacion de Decidir, los datos que se envian son unicamente visibles entre el
                 *       sitio de ecommerce y decidir, y nunca se deben mostrar en el browser. Para evitar esto se
                 *       encripta la informacion con un hash secreto.
                 */
                $blockCipher = BlockCipher::factory('mcrypt', ['algo' => 'aes']);
                $blockCipher->setKey(\Decidir\SpsDecidir\Helper\Data::SECRET_WORD);

                $response = [
                    'rk'  => $blockCipher->encrypt($authorizeRequest->getRequestKey()),
                    'prk' => $authorizeRequest->getPublicRequestKey()
                ];

                return $result->setData($response);
            }
            else
            {
                //loguear el mensaje de error con el numero de quote, cliente, etc
                //$authorizeRequest->getStatusMessage();
                /**
                 * @TODO Mejorar los errores cuando decidir devuelve una respuesta diferente de OK.
                 */
                return $result->setData(['rk'=>0,'prk'=>0]);
            }
        }

        return $result->setData(['rk'=>0,'prk'=>0]);
    }
}