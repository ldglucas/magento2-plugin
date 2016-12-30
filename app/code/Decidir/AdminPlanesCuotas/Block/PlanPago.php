<?php

namespace Decidir\AdminPlanesCuotas\Block;

use Magento\Framework\View\Element\Template;

/**
 * Class PlanPago
 *
 * @description Bloque para renderizar los planes de pago en el checkout
 */
class PlanPago extends Template
{
    const ACTIVO    = 1;
    const INACTIVO  = 0;

    /**
     * @var \Decidir\AdminPlanesCuotas\Model\TarjetaFactory
     */
    protected $_tarjetasFactory;

    /**
     * @var \Decidir\AdminPlanesCuotas\Model\BancoFactory
     */
    protected $_bancoFactory;

    /**
     * @var \Decidir\AdminPlanesCuotas\Model\PlanPagoFactory
     */
    protected $_planPagoFactory;

    /**
     * @var \Decidir\AdminPlanesCuotas\Model\CuotaFactory
     */
    protected $_cuotaFactory;

    /**
     * @var \Decidir\AdminPlanesCuotas\Helper\Data
     */
    protected $_helper;

    /**
     * @var Template\Context
     */
    protected $_context;

    private $planesPago;

    /**
     * PlanPago constructor.
     * @param Template\Context $context
     * @param array $data
     * @param \Decidir\AdminPlanesCuotas\Model\TarjetaFactory $medioPagoFactory
     * @param \Decidir\AdminPlanesCuotas\Model\BancoFactory $bancoFactory
     * @param \Decidir\AdminPlanesCuotas\Model\CuotaFactory $cuotaFactory
     * @param \Decidir\AdminPlanesCuotas\Model\PlanPagoFactory $planPagoFactory
     */
    public function __construct(
        Template\Context $context,
        array $data = [],
        \Decidir\AdminPlanesCuotas\Model\TarjetaFactory $medioPagoFactory,
        \Decidir\AdminPlanesCuotas\Model\BancoFactory $bancoFactory,
        \Decidir\AdminPlanesCuotas\Model\CuotaFactory $cuotaFactory,
        \Decidir\AdminPlanesCuotas\Model\PlanPagoFactory $planPagoFactory,
        \Decidir\AdminPlanesCuotas\Helper\Data $helper
    )
    {
        $this->_tarjetasFactory     = $medioPagoFactory;
        $this->_bancoFactory        = $bancoFactory;
        $this->_planPagoFactory     = $planPagoFactory;
        $this->_cuotaFactory        = $cuotaFactory;
        $this->_helper              = $helper;
        $this->_context             = $context;

        parent::__construct($context, $data);
    }

    /**
     * @description Retorna el listado de tarjetas activas
     *
     * @return array
     */
    public function getTarjetas()
    {
        $helper = $this->_helper;

        $tarjetasDisponibles = $this->_planPagoFactory
            ->create()
            ->getCollection();

        $connection = $this->_planPagoFactory->create()->getConnection();
        $tbl_tarjeta = $this->_tarjetasFactory->create()->getCollection()->getMainTable();

        $tarjetasDisponibles->getSelect()
            ->where('FIND_IN_SET(?, dias)', date('N'));

        $tarjetasDisponibles->getSelect()
            ->join(
                ['t' =>$tbl_tarjeta],
                'main_table.tarjeta_id = t.tarjeta_id',
                []);

        $tarjetasDisponibles
            ->addFieldToFilter('main_table.activo', ['eq' => self::ACTIVO])
            ->addFieldToFilter('main_table.vigente_desde', ['lteq' => date('Y-m-d h:i:s')])
            ->addFieldToFilter('main_table.vigente_hasta', ['gteq' => date('Y-m-d h:i:s')]);

        $tarjetasDisponibles->getSelect()
            ->reset(\Magento\Framework\DB\Select::COLUMNS)
            ->columns(
                [
                    'tarjeta_nombre'=> 't.nombre',
                    'tarjeta_logo'  => 't.logo_src',
                    'tarjeta_id'    => 't.tarjeta_id',
                    'sps_id'        => 't.sps_tarjeta_id',
                    'nps_id'        => 't.nps_tarjeta_id'
                ]
            )
            ->group('t.tarjeta_id')
            ->order('t.prioridad_orden ASC');

        $tarjetasDisponibles = $tarjetasDisponibles->getData();

        for($i = 0; $i < count($tarjetasDisponibles); $i++)
        {
            $tarjetasDisponibles[$i]['tarjeta_logo'] = $helper->getImageUrl($tarjetasDisponibles[$i]['tarjeta_logo'],$helper::PATH_TARJETAS);
        }

        return $tarjetasDisponibles;
    }

    /**
     * @description Retorna el listado de bancos activos
     *
     * @return array
     */
    public function getBancos()
    {
        $helper = $this->_helper;

        $bancosDisponibles = $this->_planPagoFactory
            ->create()
            ->getCollection();

        $connection = $this->_planPagoFactory->create()->getConnection();
        $tbl_banco =  $this->_bancoFactory->create()->getCollection()->getMainTable();

        $bancosDisponibles->getSelect()
            ->where('FIND_IN_SET(?, dias)', date('N'));

        $bancosDisponibles->getSelect()
            ->join(
                ['b' =>$tbl_banco],
                'main_table.banco_id = b.banco_id',
                []);

        $bancosDisponibles
            ->addFieldToFilter('main_table.activo', ['eq' => self::ACTIVO])
            ->addFieldToFilter('main_table.vigente_desde', ['lteq' => date('Y-m-d h:i:s')])
            ->addFieldToFilter('main_table.vigente_hasta', ['gteq' => date('Y-m-d h:i:s')]);

        $bancosDisponibles->getSelect()
            ->reset(\Magento\Framework\DB\Select::COLUMNS)
            ->columns(['banco_nombre'=>'b.nombre','banco_logo'=> 'b.logo_src','main_table.tarjeta_id','main_table.nombre','b.banco_id'])
            ->group('b.banco_id')
            ->order('b.prioridad_orden ASC');

        $bancosDisponibles = $bancosDisponibles->getData();

        for($i = 0; $i < count($bancosDisponibles); $i++)
        {
            $bancosDisponibles[$i]['banco_logo'] = $helper->getImageUrl($bancosDisponibles[$i]['banco_logo'],$helper::PATH_BANCOS);
        }

        return $bancosDisponibles;
    }

    /**
     * @description Retorna los planes de pago vigentes, junto a sus tarjetas y bancos. Ordena las prioridades de cada
     *              plan de manera ascendente, y en el caso que haya vigentes mas de 1  sol
     *
     * @return array
     */
    public function getPlanesPago()
    {
        $planesPagoDisponibles = $this->_planPagoFactory
            ->create()
            ->getCollection();

        $tbl_tarjeta = $this->_tarjetasFactory->create()->getCollection()->getMainTable();
        $tbl_banco =  $this->_bancoFactory->create()->getCollection()->getMainTable();

        $planesPagoDisponiblesSubquery = clone  $planesPagoDisponibles;

        $planesPagoDisponiblesSubquery->getSelect()
            ->join(
                ['b' => $tbl_banco],
                'main_table.banco_id = b.banco_id',
                ['banco_nombre'=>'b.nombre','banco_logo'=> 'b.logo_src'])
            ->join(
                ['t' => $tbl_tarjeta],
                'main_table.tarjeta_id = t.tarjeta_id',
                ['tarjeta_nombre'=>'t.nombre','tarjeta_logo'=> 't.logo_src']);

        $planesPagoDisponiblesSubquery
            ->addFieldToFilter('main_table.activo', ['eq' => self::ACTIVO])
            ->addFieldToFilter('main_table.vigente_desde', ['lteq' => date('Y-m-d h:i:s')])
            ->addFieldToFilter('main_table.vigente_hasta', ['gteq' => date('Y-m-d h:i:s')]);

        $planesPagoDisponiblesSubquery->getSelect()
            ->where('FIND_IN_SET(?, dias)', date('N'))
            ->order('main_table.prioridad asc');

        $planesPagoDisponibles->getSelect()
            ->reset(\Magento\Framework\DB\Select::COLUMNS)
            ->reset(\Magento\Framework\DB\Select::FROM)
            ->from($planesPagoDisponiblesSubquery->getSelect())
            ->group(['tarjeta_id','banco_id']);

        $this->planesPago = $planesPagoDisponibles->getData();

        return $this->planesPago;
    }


    /**
     * @description Retorna las cuotas de todos los planes de pago activos en el checkout
     *
     * @return array
     */
    public function getCuotas()
    {
        $planes = $this->planesPago;
        $idsPlanes = [];

        foreach($planes as $_plan)
        {
            $idsPlanes[] = $_plan['plan_pago_id'];
        }

        $cuotaCollection = $this->_cuotaFactory
            ->create()
            ->getCollection()
            ->addFieldToFilter('plan_pago_id', ['in' => $idsPlanes]);

        $cuotas = [];

        foreach($cuotaCollection->getData() as $_cuota)
        {
            if(!isset($cuotas[$_cuota['plan_pago_id']]))
            {
                $cuotas[$_cuota['plan_pago_id']] = [];
                $cuotas[$_cuota['plan_pago_id']][] = $_cuota;
            }
            else
            {
                $cuotas[$_cuota['plan_pago_id']][] = $_cuota;
            }
        }

        return $cuotas;
    }



}