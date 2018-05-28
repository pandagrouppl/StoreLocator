<?php

namespace PandaGroup\Westfield\Setup;

use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{

    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $installer = $setup;

        $installer->startSetup();

        /* === Create `light4website_westfield_color` table === */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('light4website_westfield_color'))
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Entity ID'
            )
            ->addColumn(
                'magento_color_value',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Magento Color Value'
            )
            ->addColumn(
                'westfield_color_value',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Westfield Color Value'
            );
        $installer->getConnection()->createTable($table);

        /* === Create `light4website_westfield_status` table === */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('light4website_westfield_status'))
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER, 
                null,
                ['unsigned'  => true, 'nullable'  => false, 'primary'   => true, 'identity'  => true ],
                'Entity ID'
            )
            ->addColumn(
                'retailer_origin', 
                Table::TYPE_TEXT,
                255,
                [ 'nullable' => true ],
                'Retailer Origin'
            )
            ->addColumn(
                'status_url',
                Table::TYPE_TEXT,
                255,
                [ 'nullable' => false ],
                'Status Url'
            )
            ->addColumn(
                'status_id',
                Table::TYPE_INTEGER,
                10,
                [ 'nullable' => false, 'unsigned' => true ],
                'Status Id'
            )
            ->addColumn(
                'status_code',
                Table::TYPE_TEXT,
                255,
                [ 'nullable' => false ],
                'Status Code'
            )
            ->addColumn(
                'job_type',
                Table::TYPE_TEXT,
                255,
                [ 'nullable' => false ],
                'Job Type'
            )
            ->addColumn(
                'job_source_url',
                Table::TYPE_TEXT,
                255,
                [ 'nullable' => false ],
                'Job Source Url'
            )
            ->addColumn(
                'job_source_size',
                Table::TYPE_INTEGER,
                10,
                [ 'nullable' => true ],
                'Job Source Size'
            )
            ->addColumn(
                'validation_errors',
                Table::TYPE_TEXT,
                null,
                [ 'nullable' => false ],
                'Validation Errors'
            )
            ->addColumn(
                'products_count',
                Table::TYPE_INTEGER,
                10,
                ['nullable' => true],
                'Product Count'
            )
            ->addColumn(
                'success_count',
                Table::TYPE_INTEGER,
                10,
                [ 'nullable' => true ],
                'Success Count'
            )
            ->addColumn(
                'created_count',
                Table::TYPE_INTEGER,
                10,
                [ 'nullable' => true ],
                'Created Count'
            )
            ->addColumn(
                'updated_count',
                Table::TYPE_INTEGER,
                10,
                [ 'nullable' => true ],
                'Updated Count'
            )
            ->addColumn(
                'deleted_count',
                Table::TYPE_INTEGER,
                10,
                [ 'nullable' => true ],
                'Deleted Count'
            )
            ->addColumn(
                'errors_count',
                Table::TYPE_INTEGER,
                10,
                [ 'nullable' => true ],
                'Errors Count'
            )
            ->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                [ 'nullable' => false, 'default' => Table::TIMESTAMP_INIT ],
                'Created At'
            )
            ->addColumn(
                'updated_at',
                Table::TYPE_TIMESTAMP,
                null,
                [ 'nullable' => true ],
                'Updated At'
            )
            ->addColumn(
                'mode',
                Table::TYPE_INTEGER,
                1,
                [ 'nullable' => false ],
                'Mode'
            );
        $installer->getConnection()->createTable($table);

        /* === Create `light4website_westfield_status_product` table === */
        $tableName = $installer->getTable('light4website_westfield_status_product');
        $table = $installer->getConnection()
            ->newTable($tableName)
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                [ 'unsigned'  => true, 'nullable'  => false, 'primary'   => true, 'identity'  => true ],
                'Entity ID'
            )
            ->addColumn(
                'westfield_status_id',
                Table::TYPE_INTEGER,
                10,
                [ 'nullable' => false, 'unsigned' => true ],
                'Westfield Status Id'
            )
            ->addColumn(
                'sku',
                Table::TYPE_TEXT,
                255,
                [ 'nullable' => false ],
                'Product Sku'
            )
            ->addColumn(
                'response_code',
                Table::TYPE_INTEGER,
                10,
                [ 'nullable' => true ],
                'Response Code'
            )
            ->addColumn(
                'response_message',
                Table::TYPE_TEXT,
                null,
                [ 'nullable' => false ],
                'Response Message'
            )
            ->addIndex($installer->getIdxName($tableName, [ 'westfield_status_id' ]), [ 'westfield_status_id' ])
            ->addForeignKey(
                $installer->getFkName($tableName, 'westfield_status_id', 'light4website_westfield_status', 'entity_id'),
                'westfield_status_id',
                $installer->getTable('light4website_westfield_status'),
                'entity_id',
                Table::ACTION_CASCADE
            );

        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
