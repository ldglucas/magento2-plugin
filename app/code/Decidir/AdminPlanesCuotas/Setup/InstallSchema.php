<?php

namespace Decidir\AdminPlanesCuotas\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallSchema
 *
 * @description Instalador de tablas. Equivalente a los installer de magento 1.
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @description Instalador de las tablas:
     *                                      - decidir_tarjeta
     *                                      - decidir_banco
     *                                      - decidir_plan_cuota
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $decidirTarjeta = $installer->getConnection()
            ->newTable($installer->getTable('decidir_tarjeta'))
            ->addColumn(
                'tarjeta_id',
                Table::TYPE_SMALLINT,
                6,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Id autoincremental para que magento pueda manejar las collecciones'
            )
            ->addColumn('sps_tarjeta_id' , Table::TYPE_SMALLINT  ,null, ['nullable' => true, 'default' => null])
            ->addColumn('nps_tarjeta_id' , Table::TYPE_SMALLINT  ,null, ['nullable' => true, 'default' => null])
            ->addColumn('nombre'         , Table::TYPE_TEXT      , 100, ['nullable' => true, 'default' => null])
            ->addColumn('activo'         , Table::TYPE_SMALLINT  , 4  , ['nullable' => false,'default'=>0])
            ->addColumn('logo_src'       , Table::TYPE_TEXT      , 255, ['nullable' => true,'default'=>null])
            ->addColumn('prioridad_orden', Table::TYPE_SMALLINT  , 4  , ['nullable' => false,'default'=>0])
            ->setComment('Tabla de tarjetas de credito, con los respectivos id que identifican a cada una en sps y nps.');

        $installer->getConnection()->createTable($decidirTarjeta);

        $decidirBanco = $installer->getConnection()
            ->newTable($installer->getTable('decidir_banco'))
            ->addColumn(
                'banco_id',
                Table::TYPE_SMALLINT,
                6,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Id autoincremental para el banco'
            )
            ->addColumn('nombre'         , Table::TYPE_TEXT      , 200, ['nullable' => true, 'default' => null])
            ->addColumn('activo'         , Table::TYPE_SMALLINT  , 4  , ['nullable' => false,'default'=>0])
            ->addColumn('logo_src'       , Table::TYPE_TEXT      , 255, ['nullable' => true,'default'=>null])
            ->addColumn('prioridad_orden', Table::TYPE_SMALLINT  , 4  , ['nullable' => false,'default'=>0])
            ->setComment('Tabla de bancos disponibles .');

        $installer->getConnection()->createTable($decidirBanco);


        $decidirPlanPago = $installer->getConnection()
            ->newTable($installer->getTable('decidir_plan_pago'))
            ->addColumn(
                'plan_pago_id',
                Table::TYPE_SMALLINT,
                6,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Id autoincremental para el plan de cuotas'
            )
            ->addColumn('nombre'       , Table::TYPE_TEXT    , 255  , ['nullable' => false])
            ->addColumn('tarjeta_id'   , Table::TYPE_SMALLINT, 6    , ['nullable' => false])
            ->addColumn('banco_id'     , Table::TYPE_SMALLINT, 6    , ['nullable' => false])
            ->addColumn('vigente_desde', Table::TYPE_DATETIME, 6    , ['nullable' => true,'default'=>null])
            ->addColumn('vigente_hasta', Table::TYPE_DATETIME, 6    , ['nullable' => true,'default'=>null])
            ->addColumn('prioridad'    , Table::TYPE_SMALLINT, 6    , ['nullable' => true,'default'=>0])
            ->addColumn('dias'         , Table::TYPE_TEXT    , 50   , ['nullable' => false])
            ->addColumn('salesrule_id_no_acumulables'        , Table::TYPE_TEXT    , 100  , ['nullable' => true,'default'=>null])
            ->addColumn('activo'       , Table::TYPE_SMALLINT, 6    , ['nullable' => true,'default'=>1])
            ->addIndex(
                $installer->getIdxName('decidir_tarjeta', ['tarjeta_id']),
                ['tarjeta_id']
            )
            ->addForeignKey(
                $installer->getFkName('decidir_plan_pago', 'tarjeta_id', 'decidir_tarjeta', 'tarjeta_id'),
                'tarjeta_id',
                $installer->getTable('decidir_tarjeta'),
                'tarjeta_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_RESTRICT
            )
            ->addIndex(
                $installer->getIdxName('decidir_banco', ['banco_id']),
                ['banco_id']
            )
            ->addForeignKey(
                $installer->getFkName('decidir_plan_pago', 'banco_id', 'decidir_banco', 'banco_id'),
                'banco_id',
                $installer->getTable('decidir_banco'),
                'banco_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_RESTRICT
            )
            ->setComment('Tabla de planes de pago.');

        $installer->getConnection()->createTable($decidirPlanPago);

        $decidirCuota = $installer->getConnection()
            ->newTable($installer->getTable('decidir_cuota'))
            ->addColumn(
                'plan_pago_id',
                Table::TYPE_SMALLINT,
                6,
                ['primary' => true, 'nullable' => false],
                'Id referencial al plan de pago'
            )
            ->addColumn('cuota',
                Table::TYPE_SMALLINT,
                6,
                ['primary' => true, 'nullable' => false])

            ->addColumn('interes'       , Table::TYPE_FLOAT   , null   , ['nullable' => false, 'default' => 0])
            ->addColumn('reintegro'     , Table::TYPE_FLOAT   , null   , ['nullable' => false, 'default' => 0])
            ->addColumn('cuota_gateway' , Table::TYPE_SMALLINT, 6      , ['nullable' => true, 'default' => null])
            ->addColumn('tipo_reintegro', Table::TYPE_SMALLINT, 1      , ['nullable' => true, 'default' => null])
            ->addColumn('descuento'     , Table::TYPE_FLOAT   , null   , ['nullable' => false, 'default' => 0])
            ->addColumn('tipo_descuento', Table::TYPE_SMALLINT, 1      , ['nullable' => true, 'default' => null])
            ->addIndex(
                $installer->getIdxName('decidir_cuota', ['plan_pago_id']),
                ['plan_pago_id']
            )
            ->addForeignKey(
                $installer->getFkName('decidir_cuota', 'plan_pago_id', 'decidir_plan_pago', 'plan_pago_id'),
                'plan_pago_id',
                $installer->getTable('decidir_plan_pago'),
                'plan_pago_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Tabla de cuotas para cada plan. No se puede repetir la combinacion (plan_pago_id,cuota)');

        $installer->getConnection()->createTable($decidirCuota);

        $decidirPlanPagoProducto = $installer->getConnection()
            ->newTable($installer->getTable('decidir_plan_pago_producto'))
            ->addColumn(
                'plan_pago_id',
                Table::TYPE_SMALLINT,
                6,
                ['primary' => true, 'nullable' => false],
                'Id referencial al plan de pago'
            )
            ->addColumn('entity_id_producto',
                Table::TYPE_INTEGER,
                10,
                ['primary' => true, 'unsigned' => true,'nullable' => false])
            ->addIndex(
                $installer->getIdxName('decidir_plan_pago_producto', ['plan_pago_id']),
                ['plan_pago_id']
            )
            ->addForeignKey(
                $installer->getFkName('decidir_plan_pago_producto', 'plan_pago_id', 'decidir_plan_pago', 'plan_pago_id'),
                'plan_pago_id',
                $installer->getTable('decidir_plan_pago'),
                'plan_pago_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('decidir_plan_pago_producto', 'entity_id_producto', 'catalog_product_entity', 'entity_id'),
                'entity_id_producto',
                $installer->getTable('catalog_product_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Tabla de productos asociados a un plan de pago. No se puede repetir la combinacion (plan_pago_id,entity_id_producto)');

        $installer->getConnection()->createTable($decidirPlanPagoProducto);

        $setup->getConnection()->addColumn(
            $setup->getTable('quote_payment'),
            'plan_pago_id',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => true,
                'default' => null,
                'comment' => 'Id del plan de pago seleccionado en la compra'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('quote_payment'),
            'numero_cuotas',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => true,
                'default' => null,
                'comment' => 'Numero de cuotas que se utilizo en la compra'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('quote_payment'),
            'descuento_cuota',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                'nullable' => true,
                'default' => null,
                'comment' => 'Descuento aplicado por cuota'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('quote_payment'),
            'tipo_descuento_cuota',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => true,
                'default' => null,
                'comment' => 'Tipo de descuento por cuota aplicada (porcentual o fijo)'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('sales_order_payment'),
            'plan_pago_id',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => true,
                'default' => null,
                'comment' => 'Id del plan de pago seleccionado en la compra'
            ]
        );
        $setup->getConnection()->addColumn(
            $setup->getTable('sales_order_payment'),
            'numero_cuotas',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => true,
                'default' => null,
                'comment' => 'Numero de cuotas que se utilizo en la compra'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('sales_order_payment'),
            'descuento_cuota',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                'nullable' => true,
                'default' => null,
                'comment' => 'Descuento aplicado por cuota'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('sales_order_payment'),
            'tipo_descuento_cuota',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => true,
                'default' => null,
                'comment' => 'Tipo de descuento por cuota aplicada (porcentual o fijo)'
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('quote'),
            'descuento_cuota',
            [
                'type'    => Table::TYPE_DECIMAL,
                'nullable'=> true,
                'comment' => 'Descuento nominal por cuota',
                'default' => '0.0000',
                'length'  => '12,4'
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('quote'),
            'descuento_cuota_descripcion',
            [
                'type'    => Table::TYPE_TEXT,
                'nullable'=> true,
                'comment' => 'Descripcion de descuento por cuota',
                'default' => null,
                'length'  => 255
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order'),
            'descuento_cuota',
            [
                'type'    => Table::TYPE_DECIMAL,
                'nullable'=> true,
                'comment' => 'Descuento nominal por cuota',
                'default' => '0.0000',
                'length'  => '12,4'
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order'),
            'descuento_cuota_descripcion',
            [
                'type'    => Table::TYPE_TEXT,
                'nullable'=> true,
                'comment' => 'Descripcion de descuento por cuota',
                'default' => null,
                'length'  => 255
            ]
        );

        $installer->endSetup();
    }

}
