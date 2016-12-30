<?php

namespace Decidir\SpsDecidir\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Checkout\Model\Cart;
use Psr\Log\LoggerInterface;
use Decidir;

require_once(__DIR__.'/../Test/vendor/autoload.php');

/**
 * Class Webservice
 *
 * @description Modelo de conexiones con el ws de Decidir. Contiene cada metodo para operar con el servicio.

 */
class Webservice
{
    const MODE_DEV       = 'dev';
    const MODE_PROD      = 'prod';
    const ENCODINGMETHOD = 'XML';
    const CURRENCYCODE   = 032;
    const OPERACION_OK   = -1;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var Decidir\Connector
     */
    protected $_connector;

    /**
     * @var mixed
     */
    protected $_merchant;

    /**
     * @var mixed
     */
    protected $_security;

    /**
     * @var
     */
    protected $_requestKey;

    /**
     * @var
     */
    protected $_publicRequestKey;

    /**
     * @var Cart
     */
    protected $_cart;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * Webservice constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param Cart $cart
     * @param LoggerInterface $logger
     */
    public function __construct(ScopeConfigInterface $scopeConfig,Cart $cart,LoggerInterface $logger)
    {
        $this->_scopeConfig = $scopeConfig;
        $this->_cart        = $cart;
        $this->_logger      = $logger;

        /**
         * El numero de merchant y comercio es el mismo.
         */
        $this->_merchant = $this->_scopeConfig->getValue('payment/decidir_spsdecidir/idsite');

        /**
         * Token de Seguridad Generado en el Portal	de DECIDIR,	es necesario enviarlo solo si no
         * se puede transportar en el header http. El Token de seguridad se obtiene enviando un mail a
         * hd@decidir.com.ar.
         **/
        $this->_security = $this->_scopeConfig->getValue('payment/decidir_spsdecidir/token');

        $httpHeader = array(
            'Authorization' => 'PRISMA '.$this->_security,
            'user_agent'    => 'PHPSoapClient'
        );

        if($this->_scopeConfig->getValue('payment/decidir_spsdecidir/mode') == self::MODE_DEV)
        {
            $this->_connector = new Decidir\Connector($httpHeader, $this->_scopeConfig->getValue('payment/decidir_spsdecidir/dev/endpoint_authorize'));
        }
        elseif($this->_scopeConfig->getValue('payment/decidir_spsdecidir/mode') == self::MODE_PROD)
        {
            $this->_connector = new Decidir\Connector($httpHeader, $this->_scopeConfig->getValue('payment/decidir_spsdecidir/prod/endpoint_authorize'));
        }
    }

    /**
     * @return string
     */
    public function getSecurity()
    {
        return $this->_security;
    }

    /**
     * @return mixed
     */
    public function getMerchant()
    {
        return $this->_merchant;
    }

    /**
     * @return mixed
     */
    public function getRequestKey()
    {
        return $this->_requestKey;
    }

    /**
     * @return mixed
     */
    public function getPublicRequestKey()
    {
        return $this->_publicRequestKey;
    }

    /**
     * @description Genera el numero de operacion unico para cada transaccion.
     * @return string
     */
    public function getOperationNumber()
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVXYZ';

        return 'OP'.date('Ymdhis').$chars[rand(0,24)].$chars[rand(0,24)];
    }

    /**
     * @return Quote
     */
    public function getQuote()
    {
        return $this->_cart->getQuote();
    }

    /**
     * @description Primer paso para iniciar el proceso de pago con Decidir, en el cual se envian los datos de la
     *              operacion (tarjeta,cuotas,monto,etc) y decidir retorna la autorizacion para continuar operando.
     *
     * @param array $parametrosTransaccion
     * @return Decidir\Authorize\SendAuthorizeRequest\Response
     */
    public function sendAuthorizeRequest(array $parametrosTransaccion)
    {
        $quote = $this->getQuote();

        $medioPago = new Decidir\Data\Mediopago\TarjetaCredito(
            [
                'medio_pago' => $parametrosTransaccion['tarjeta_id'],
                'cuotas'     => $parametrosTransaccion['cantidad_cuotas']
            ]
        );

        $sarData = new Decidir\Authorize\SendAuthorizeRequest\Data(
            [
                'security'        => $this->getSecurity(),
                'encoding_method' => self::ENCODINGMETHOD,
                'merchant'        => $this->getMerchant(),
                'nro_operacion'   => $this->getOperationNumber(),
                'monto'           => $parametrosTransaccion['monton_transaccion'],
                'email_cliente'   => $quote->getCustomerEmail(),
                'tokenizar'       => 'TRUE'
            ]
        );

        $sarData->setMedioPago($medioPago);

        if($this->_scopeConfig->getValue('payment/decidir_spsdecidir/mode') == self::MODE_DEV)
        {
            $this->_logger->addDebug(print_r($sarData,true));
        }

        return $this->_connector->Authorize()->sendAuthorizeRequest($sarData);
    }

    /**
     * @description
     *
     * @param $answerKey
     * @param $requestKey
     * @return \Decidir\Authorize\GetAuthorizeAnswer\Response
     */
    public function getAuthorizeAnswer($answerKey,$requestKey)
    {
        $answerData = new \Decidir\Authorize\GetAuthorizeAnswer\Data(
            [
                'security'   => $this->getSecurity(),
                'merchant'   => $this->getMerchant(),
                'requestKey' => $requestKey,
                'answerKey'  => $answerKey
            ]
        );

        return $this->_connector->Authorize()->getAuthorizeAnswer($answerData);

    }

    /**
     * @param $operacionSps
     * @param bool $total
     * @param int $monto
     * @return Decidir\Authorize\Execute\Response
     */
    public function devolver($operacionSps,$total = false, $monto = 0)
    {
        $anular = false;

        $status_data = new \Decidir\Operation\GetByOperationId\Data(             
            [
                "idsite" => $this->getMerchant(),
                "idtransactionsit" => $operacionSps
            ]
        );

        $get_rta = $this->_connector->Operation()->getByOperationId($status_data);

        if($get_rta->getId_estado() == 4) {
            $anular = true;
        }

        if($total)
        {
            if($anular) {
                $operation = new \Decidir\Authorize\Execute\Anulacion(
                    [
                        'security'      => $this->getSecurity(),
                        'merchant'      => $this->getMerchant(),
                        'nro_operacion' => $operacionSps
                    ]
                );
            } else {
                $operation = new \Decidir\Authorize\Execute\Devolucion\Total(
                    [
                        'security'      => $this->getSecurity(),
                        'merchant'      => $this->getMerchant(),
                        'nro_operacion' => $operacionSps
                    ]
                );                
            }
        }
        else
        {
            if($anular) {
                throw new \Exception("Por favor, reintente realizar una devolución parcial luego del cierre de lote.", 1001);
            } else {
                $operation = new \Decidir\Authorize\Execute\Devolucion\Parcial(
                    [
                        'security'      => $this->getSecurity(),
                        'merchant'      => $this->getMerchant(),
                        'nro_operacion' => $operacionSps,
                        'monto'         => $monto
                    ]
                );
            }
        }

        return $this->_connector->Authorize()->execute($operation);
    }



}
