<?php

namespace Decidir\SpsDecidir\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallSchema
 *
 * @description Instalador de tablas.

 * @package Decidir\SpsDecidir\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @description Instalador de las tablas:
     *                                      - decidir_token
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $decidirToken = $installer->getConnection()
            ->newTable($installer->getTable('decidir_token'))
            ->addColumn(
                'decidir_token_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Id autoincremental para los token'
            )
            ->addColumn('token'                     , Table::TYPE_TEXT      , 255  , ['nullable' => false])
            ->addColumn('card_holder_name'          , Table::TYPE_TEXT      , 255  , ['nullable' => false])
            ->addColumn('card_number_last_4_digits' , Table::TYPE_INTEGER   , null , ['nullable' => false])
            ->addColumn('card_expiration_month'     , Table::TYPE_INTEGER   , null , ['nullable' => false])
            ->addColumn('card_expiration_year'      , Table::TYPE_INTEGER   , null , ['nullable' => false])
            ->addColumn('card_bin'                  , Table::TYPE_INTEGER   , null , ['nullable' => false])
            ->addColumn('card_type'                 , Table::TYPE_INTEGER   , null , ['nullable' => false])
            ->addColumn('customer_id'               , Table::TYPE_INTEGER   , null , ['nullable' => false,'unsigned' => true])
            ->addColumn('banco_id'                  , Table::TYPE_SMALLINT  , 6    , ['nullable' => false,])
            ->addForeignKey(
                $installer->getFkName('decidir_token', 'customer_id', 'customer_entity', 'entity_id'),
                'customer_id',
                $installer->getTable('customer_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('decidir_token', 'banco_id', 'decidir_banco', 'banco_id'),
                'banco_id',
                $installer->getTable('decidir_banco'),
                'banco_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Tabla para los token de pago generados por decidir en cada compra.');

        $installer->getConnection()->createTable($decidirToken);
    }

}