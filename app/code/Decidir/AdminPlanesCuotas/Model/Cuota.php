<?php

namespace Decidir\AdminPlanesCuotas\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class Cuota
 *
 */
class Cuota extends AbstractModel
{
    const TIPO_DESCUENTO_PORCENTUAL = 1;
    const TIPO_DESCUENTO_NOMINAL    = 2;

    /**
     * @var string
     */
    protected $_eventPrefix = 'decidir_cuota';

    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'adminplanescuotas_cuota';

    /**
     * True if data changed
     *
     * @var bool
     */
    protected $_isStatusChanged = false;

    protected $_cresource;

    /**
     * Cuota constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\ResourceConnection $cresource,
	\Decidir\AdminPlanesCuotas\Model\CuotaFactory $cFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_cresource = $cresource;
	$this->_cFactory = $cFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Inicia el resource model
     */
    protected function _construct()
    {
        $this->_init('Decidir\AdminPlanesCuotas\Model\ResourceModel\Cuota');
    }

    /**
     * @return false|\Magento\Framework\DB\Adapter\AdapterInterface
     */
    public function getConnection()
    {
        return $this->_cresource->getConnection();
    }

    /**
     * @param $planId
     * @return $this
     */
    public function deleteAll($planId)
    {
        $connection = $this->getConnection();
	$tbl = $this->_cFactory->create()->getCollection()->getMainTable();
        $connection->query("DELETE FROM ".$tbl." WHERE plan_pago_id = :plan_pago",['plan_pago'=>$planId]);

        return $this;
    }

    /**
     * @param $planPagoId
     * @param $cuota
     * @return \Magento\Framework\DataObject
     */
    public function getDetalles($planPagoId,$cuota)
    {
        $connection = $this->getConnection();
        $tbl_plan_pago = $connection->getTableName('decidir_plan_pago');
        $tbl_banco = $connection->getTableName('decidir_banco');
        $tbl_tarjeta = $connection->getTableName('decidir_tarjeta');
        $detalleCuotas = $this->getCollection();

        $detalleCuotas->getSelect()
            ->join(
                ['p' => $tbl_plan_pago],
                'main_table.plan_pago_id = p.plan_pago_id',
                [])
            ->join(
                ['b' =>$tbl_banco],
                'p.banco_id = b.banco_id',
                ['banco_nombre'=>'b.nombre'])
            ->join(
                ['t' =>$tbl_tarjeta],
                'p.tarjeta_id = t.tarjeta_id',
                ['tarjeta_nombre'=>'t.nombre']);

        $detalleCuotas
         ->addFieldToFilter('main_table.plan_pago_id',['eq'=>$planPagoId])
         ->addFieldToFilter('main_table.cuota',['eq'=>$cuota]);

        return $detalleCuotas->getFirstItem();
    }

}
