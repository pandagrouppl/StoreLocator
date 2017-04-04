<?php

namespace MagicToolbox\Magic360\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /**
         * Create table 'magic360_config'
         */
        if (!$setup->tableExists('magic360_config')) {
            $table = $setup->getConnection()->newTable(
                $setup->getTable('magic360_config')
            )->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )->addColumn(
                'platform',
                Table::TYPE_BOOLEAN,
                null,
                ['nullable' => false, 'default' => '0'],
                'Platform'
            )->addColumn(
                'profile',
                Table::TYPE_TEXT,
                64,
                ['nullable'  => false],
                'Profile'
            )->addColumn(
                'name',
                Table::TYPE_TEXT,
                64,
                ['nullable'  => false],
                'Name'
            )->addColumn(
                'value',
                Table::TYPE_TEXT,
                null,
                ['nullable'  => false],
                'Value'
            )->addColumn(
                'status',
                Table::TYPE_BOOLEAN,
                null,
                ['nullable' => false, 'default' => '0'],
                'Status'
            )->setComment(
                'Magic 360 configuration'
            );
            $setup->getConnection()->createTable($table);
        }

        /**
         * Create table 'magic360_gallery'
         */
        if (!$setup->tableExists('magic360_gallery')) {
            $table = $setup->getConnection()->newTable(
                $setup->getTable('magic360_gallery')
            )->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Product ID'
            )->addColumn(
                'position',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true],
                'Position'
            )->addColumn(
                'file',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'File'
            )->setComment(
                'Magic 360 gallery'
            );
            $setup->getConnection()->createTable($table);
        }

        /**
         * Create table 'magic360_columns'
         */
        if (!$setup->tableExists('magic360_columns')) {
            $table = $setup->getConnection()->newTable(
                $setup->getTable('magic360_columns')
            )->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0', 'primary' => true],
                'Product ID'
            )->addColumn(
                'columns',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true],
                'Columns'
            )->setComment(
                'Magic 360 columns'
            );
            $setup->getConnection()->createTable($table);
        }

        $setup->endSetup();
    }
}
