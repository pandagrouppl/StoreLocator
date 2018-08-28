<?php

namespace PandaGroup\LooknbuyExtender\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     */
    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();

        $looksTableName = 'md_looks';

        $tableName = $installer->getTable($looksTableName);

        if ($installer->tableExists($tableName) === true) {

            $installer->getConnection()->addColumn(
                $setup->getTable($looksTableName), 'carousel_image', [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '255',
                    'nullable' => false,
                    'comment' => 'Carousel Image',
                ]
            );
        }

        $installer->endSetup();
    }
}
