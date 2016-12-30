<?php

namespace Decidir\SpsDecidir\Helper;

/**
 * Class Data
 *
 * @description Helper base para el metodo de pago
 *
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @const Palabra secreta para encriptar datos
     */
    const SECRET_WORD           = 'd3c51d1r$$_s3cr3tTsdMMM__';
    const TRANSACCION_OK        = 1;
    const TRANSACCION_ERRONEA   = 0;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Checkout\Model\Session $checkoutSession
    ) {
        $this->_coreRegistry    = $coreRegistry;
        $this->_checkoutSession = $checkoutSession;
        parent::__construct($context);
    }

    /**
     * @description Serializo y encripto la informacion resultante de la operacion para ser almacenada en una
     *              variable de sesion.
     * @param $data
     */
    public function setInfoTransaccionSPS($data)
    {
        $blockCipher = \Zend\Crypt\BlockCipher::factory('mcrypt', array('algo' => 'aes'));
        $blockCipher->setKey(self::SECRET_WORD);

        $transaccionSerializada = $blockCipher->encrypt(serialize($data));

        $this->_checkoutSession->setOperacionSps($transaccionSerializada);
    }

    /**
     * @description desencripto la informacion resultante de la ultima operacion y devuelve un array con su informacion
     *
     * @return array
     */
    public function getInfoTransaccionSPS()
    {
        $blockCipher = \Zend\Crypt\BlockCipher::factory('mcrypt', array('algo' => 'aes'));
        $blockCipher->setKey(self::SECRET_WORD);

        return unserialize($blockCipher->decrypt($this->_checkoutSession->getOperacionSps()));
    }

    /**
     * @param $mensaje String
     * @param $archivo String
     */
    public static function log($mensaje,$archivo)
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/'.$archivo);
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info($mensaje);
    }


}
