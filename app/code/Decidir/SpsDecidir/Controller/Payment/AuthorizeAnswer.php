<?php

namespace Decidir\SpsDecidir\Controller\Payment;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\TestFramework\Inspection\Exception;
use Zend\Crypt\BlockCipher;
use Decidir\SpsDecidir\Helper\Data as SpsHelper;
use Decidir\SpsDecidir\Model\Webservice;
use Decidir\SpsDecidir\Model\DecidirTokenFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Checkout\Model\Session as CheckoutSession;

/**
 * Class AuthorizeAnswer
 *
 * @description Action que se encarga de generar el segundo y ultimo paso de integracion con SPS. Se conecta con el WS,
 *              y envia los datos de la transaccion para saber si esta fue correcta o no.
 */
class AuthorizeAnswer extends Action
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
     * @var Webservice
     */
    protected $_webservice;

    /**
     * @var SpsHelper
     */
    protected $_spsHelper;

    /**
     * @var DecidirTokenFactory
     */
    protected $_decidirTokenFactory;

    /**
     * @var CustomerSession
     */
    protected $_customerSession;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var CheckoutSession
     */
    protected $_checkoutSession;

    /**
     * AuthorizeAnswer constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param OrderRepositoryInterface $orderRepositoryInterface
     * @param JsonFactory $resultJsonFactory
     * @param SpsHelper $spsHelper
     * @param Webservice $webservice
     * @param DecidirTokenFactory $decidirToken
     * @param CustomerSession $customerSession
     * @param ScopeConfigInterface $scopeConfigInterface
     * @param CheckoutSession $checkoutSession
     */
    public function __construct
    (
        Context $context,
        PageFactory $resultPageFactory,
        OrderRepositoryInterface $orderRepositoryInterface,
        JsonFactory $resultJsonFactory,
        SpsHelper $spsHelper,
        Webservice $webservice,
        DecidirTokenFactory $decidirToken,
        CustomerSession $customerSession,
        ScopeConfigInterface $scopeConfigInterface,
        CheckoutSession $checkoutSession
    )
    {
        $this->_resultPageFactory   = $resultPageFactory;
        $this->_orderRepository     = $orderRepositoryInterface;
        $this->_resultJsonFactory   = $resultJsonFactory;
        $this->_spsHelper           = $spsHelper;
        $this->_webservice          = $webservice;
        $this->_decidirTokenFactory = $decidirToken;
        $this->_customerSession     = $customerSession;
        $this->_scopeConfig         = $scopeConfigInterface;
        $this->_checkoutSession     = $checkoutSession;

        parent::__construct($context);
    }

    /**
     * @return $this
     */
    public function execute()
    {
        $request = $this->getRequest();
        $result = $this->_resultJsonFactory->create();

        if ($this->getRequest()->isXmlHttpRequest())
        {
            $customerSession    = $this->_customerSession;
            $helper             = $this->_spsHelper;
            $publicAnswerKey    = $request->getParam('pak');
            $requestKey         = $request->getParam('rk');
            $tarjetaId          = $request->getParam('tarjeta');
            $bancoId            = $request->getParam('banco');

            if($publicAnswerKey && $requestKey)
            {
                $ws = $this->_webservice;

                /**
                 * @NOTE Segun la documentacion de Decidir, los datos que se envian son unicamente visibles entre el
                 *       sitio de ecommerce y decidir, y nunca se deben mostrar en el browser. Para evitar esto se
                 *       encripta la informacion con un hash secreto.
                 */
                $blockCipher = BlockCipher::factory('mcrypt', array('algo' => 'aes'));
                $blockCipher->setKey(\Decidir\SpsDecidir\Helper\Data::SECRET_WORD);

                $requestKeyDesencriptada = $blockCipher->decrypt($requestKey);

                $respuestaTransaccion = [];

                $this->_checkoutSession->setFinalizacionCompra(true);

                try
                {
                    $authorizeAnswer = $ws->getAuthorizeAnswer($publicAnswerKey,$requestKeyDesencriptada);

                    $respuestaTransaccion['estado_transaccion'] = $helper::TRANSACCION_OK;
                    $respuestaTransaccion['tarjeta_id']         = $tarjetaId;
                    $respuestaTransaccion['respuesta_sps']      = [];

                    $respuestaTransaccion['respuesta_sps']['status_code']       = $authorizeAnswer->getStatusCode();
                    $respuestaTransaccion['respuesta_sps']['status_message']    = $authorizeAnswer->getStatusMessage();
                    $respuestaTransaccion['respuesta_sps']['payload']           = $authorizeAnswer->getPayload();

                    if($this->_scopeConfig->getValue('payment/decidir_spsdecidir/mode') == \Decidir\SpsDecidir\Model\Webservice::MODE_DEV)
                    {
                        $this->_spsHelper->log("RESPUESTA DE GAA: \n" . print_r($respuestaTransaccion, true), 'respuesta_gaa_' . date('Y_m_d').'.log');
                    }

                    if(isset($respuestaTransaccion['respuesta_sps']['payload']['Answer']['TOKENIZATION'])
                        &&  $customerSession->isLoggedIn())
                    {
                        $token = $respuestaTransaccion['respuesta_sps']['payload']['Answer']['TOKENIZATION'];

                        $decidirToken = $this->_decidirTokenFactory->create();

                        $decidirToken->setToken($token['TOKEN']);
                        $decidirToken->setCardHolderName($token['CARDHOLDERNAME']);
                        $decidirToken->setCardNumberLast4Digits($token['CARDNUMBERLAST4DIGITS']);
                        $decidirToken->setCardExpirationMonth($token['CARDEXPIRATIONMONTH']);
                        $decidirToken->setCardExpirationYear($token['CARDEXPIRATIONYEAR']);
                        $decidirToken->setCardBin($token['CARDBIN']);
                        $decidirToken->setCardType($token['CARDTYPE']);
                        $decidirToken->setCustomerId($customerSession->getCustomer()->getId());
                        $decidirToken->setBancoId($bancoId);

                        $decidirToken->save();
                    }
                }
                catch(\Exception $e)
                {
                    if($this->_scopeConfig->getValue('payment/decidir_spsdecidir/mode') == \Decidir\SpsDecidir\Model\Webservice::MODE_DEV)
                    {
                        $this->_spsHelper->log("RESPUESTA DE GAA: \n".print_r($e->getData(),true),'respuesta_gaa_'.date('Y_m_d').'.log');
                    }

                    /**
                     * @var $respuestaDecidir \Decidir\Authorize\GetAuthorizeAnswer\Response
                     */
                    $respuestaDecidir = $e->getData();

                    $respuestaTransaccion['estado_transaccion'] = $helper::TRANSACCION_ERRONEA;
                    $respuestaTransaccion['tarjeta_id']         = $tarjetaId;
                    $respuestaTransaccion['respuesta_sps']      = [];

                    $respuestaTransaccion['respuesta_sps']['status_code']       = $respuestaDecidir->getStatusCode();
                    $respuestaTransaccion['respuesta_sps']['status_message']    = $respuestaDecidir->getStatusMessage();
                    $respuestaTransaccion['respuesta_sps']['payload']           = $respuestaDecidir->getPayload();
                }

                $respuestaTransaccion['detalles_pago'] = $request->getParam('detallesPago');

                $helper->setInfoTransaccionSPS($respuestaTransaccion);

                return $result->setData(['estado_transaccion'=>$respuestaTransaccion['estado_transaccion']]);

            }
        }

        return $result->setData(['estado_transaccion'=>\Decidir\SpsDecidir\Helper\Data::TRANSACCION_ERRONEA]);
    }
}