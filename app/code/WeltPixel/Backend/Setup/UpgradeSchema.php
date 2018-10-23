<?php
namespace WeltPixel\Backend\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class UpgradeSchema
 * @package WeltPixel\Backend\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.1.0') < 0) {
            /**
             * Create table 'weltpixel_license'
             */
            $table = $installer->getConnection()->newTable(
                $installer->getTable('weltpixel_license')
            )->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )->addColumn(
                'module_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Module Name'
            )->addColumn(
                'license_key',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                1024,
                [],
                'License Key'
            )->setComment(
                'WeltPixel License'
            );

            $installer->getConnection()->createTable($table);

        }

        $installer->endSetup();
    }
}
