<?php
namespace Decidir\AdminPlanesCuotas\Helper;

/**
 * Class Data
 *
 * @description Helper base del administrador de planes de pago.
 *
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    protected $_assetRepo;

    /**
     * @var array
     */
    private $_diasSemana = [
        1 =>'Lunes',
        2 =>'Martes',
        3 =>'Miercoles',
        4 =>'Jueves',
        5 =>'Viernes',
        6 =>'Sábado',
        7 =>'Domingo'
    ];

    const PATH_TARJETAS     = 'tarjetas';
    const PATH_BANCOS       = 'bancos';

    /**
     * @description tipo de valor porcentual para el tipo de reintegro y descuento en las cuotas
     */
    const VALOR_PORCENTUAL  = 1;

    /**
     * @description tipo de valor fijo para el tipo de reintegro y descuento en las cuotas
     */
    const VALOR_FIJO        = 2;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\View\Asset\Repository $assetRepo
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->_assetRepo = $assetRepo;
        parent::__construct($context);
    }

    /**
     * Devuelve la url absoluta de la imagen de tarjeta, o banco.
     *
     * @param $img
     * @param null $tipo String
     * @return string
     */
    public function getImageUrl($img,$tipo = null)
    {
        if($img)
        {
            if($tipo == self::PATH_TARJETAS)
            {
                return $this->_assetRepo->getUrl('Decidir_AdminPlanesCuotas::images/'.self::PATH_TARJETAS.'/'.$img);
            }
            if($tipo == self::PATH_BANCOS)
            {
                return $this->_assetRepo->getUrl('Decidir_AdminPlanesCuotas::images/'.self::PATH_BANCOS.'/'.$img);
            }
        }
        else
        {
            return $this->_assetRepo->getUrl('Magento_Catalog::images/product/placeholder/thumbnail.jpg');
        }

        return null;
    }

    /**
     * @param array $numeros
     * @return array
     */
    public function getDiasSemana(array $numeros = [])
    {
        if(count($numeros))
        {
            $dias = [];

            foreach($numeros as $key=>$_num)
            {
                $dias[$_num] = $this->getDia($_num);
            }
            return $dias;
        }

        return $this->_diasSemana;
    }

    /**
     * @param $numero
     * @return mixed
     */
    public function getDia($numero)
    {
        return $this->_diasSemana[$numero];
    }
}

