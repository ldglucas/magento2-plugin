<?php
namespace Decidir\SpsDecidir\Block;

use Decidir\AdminPlanesCuotas\Helper\Data;
use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\Session as CustomerSession;

/**
 * Class DecidirHead
 *
 * @description Bloque encargado de generar contenido para el metodo de pago en el checkout.
 *
 */
class DecidirHead extends Template
{
    /**
     * @var array
     */
    protected $_planPago = [] ;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Decidir\SpsDecidir\Model\DecidirTokenFactory
     */
    protected $_decidirTokenFactory;

    /**
     * @var CustomerSession
     */
    protected $_customerSession;

    /**
     * @var array
     */
    protected $_token = [];

    /**
     * @var Data
     */
    protected $_helperAdminPlanes;

    /**
     * DecidirHead constructor.
     * @param Template\Context $context
     * @param array $data
     * @param \Decidir\SpsDecidir\Model\DecidirTokenFactory $decidirTokenFactory
     * @param CustomerSession $customerSession
     * @param Data $heperAdminPlanes
     */
    public function __construct
    (
        Template\Context $context,
        array $data,
        \Decidir\SpsDecidir\Model\DecidirTokenFactory $decidirTokenFactory,
        \Decidir\AdminPlanesCuotas\Model\TarjetaFactory $medioPagoFactory,
        \Decidir\AdminPlanesCuotas\Model\BancoFactory $bancoFactory,        
        CustomerSession $customerSession,
        Data $heperAdminPlanes
    ) {
        $this->_scopeConfig         = $context->getScopeConfig();
        $this->_decidirTokenFactory = $decidirTokenFactory;
        $this->_tarjetasFactory     = $medioPagoFactory;
        $this->_bancoFactory        = $bancoFactory;        
        $this->_customerSession     = $customerSession;
        $this->_helperAdminPlanes   = $heperAdminPlanes;

        parent::__construct($context, $data);
    }

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    /**
     * @description Devuelve el JS requerido por decidir, segun el modo elegido en la configuracion del modulo.
     *              (PRODUCCION o DESARROLLO).
     * @return string
     */
    public function getDecidirPaymentJs()
    {
        if($this->_scopeConfig->getValue('payment/decidir_spsdecidir/mode') == \Decidir\SpsDecidir\Model\Webservice::MODE_DEV)
        {
            return "https://sandbox.decidir.com/custom/callback/1.1/payment.js";
        }
        elseif($this->_scopeConfig->getValue('payment/decidir_spsdecidir/mode') == \Decidir\SpsDecidir\Model\Webservice::MODE_PROD)
        {
            return "https://sps.decidir.com/custom/callback/1.1/payment.js";
        }
    }

    public function getDecidirTokenJs()
    {
        if($this->_scopeConfig->getValue('payment/decidir_spsdecidir/mode') == \Decidir\SpsDecidir\Model\Webservice::MODE_DEV)
        {
            return $this->_scopeConfig->getValue('payment/decidir_spsdecidir/dev/javascript_token');
        }
        elseif($this->_scopeConfig->getValue('payment/decidir_spsdecidir/mode') == \Decidir\SpsDecidir\Model\Webservice::MODE_PROD)
        {
            return $this->_scopeConfig->getValue('payment/decidir_spsdecidir/prod/javascript_token');
        }
    }

    /**
     * @return array
     */
    public function getToken()
    {
        if($this->_customerSession->isLoggedIn())
        {
            $customer = $this->_customerSession->getCustomer();

            $token = $this->_decidirTokenFactory->create()
                ->getCollection()
                ->addFieldToFilter('customer_id',['eq'=>$customer->getId()]);

            $tbl_tarjeta = $this->_tarjetasFactory->create()->getCollection()->getMainTable();
            $tbl_banco =  $this->_bancoFactory->create()->getCollection()->getMainTable();

            $token->getSelect()
                ->join(
                    ['t' => $tbl_tarjeta],
                    'main_table.card_type = t.sps_tarjeta_id',
                    ['nombre_tarjeta'=>'t.nombre','logo_tarjeta'=>'t.logo_src','t.tarjeta_id','t.sps_tarjeta_id'])
                ->join(
                    ['b' => $tbl_banco],
                    'main_table.banco_id = b.banco_id',
                    ['nombre_banco'=>'b.nombre','logo_banco'=>'b.logo_src']);

            $tokenData = $token->getData();

            $i = 0;

            foreach ($tokenData as $_token)
            {
                $tokenData[$i]['logo_tarjeta'] = $this->_helperAdminPlanes->getImageUrl($_token['logo_tarjeta'],'tarjetas');

                $i++;
            }

            $this->_token = $tokenData;
        }

        return $this->_token;

    }

}