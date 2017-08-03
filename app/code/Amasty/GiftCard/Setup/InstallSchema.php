<?php

namespace Amasty\GiftCard\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'amasty_amgiftcard_price'
         */

        $table = $installer->getConnection()->newTable(
            $installer->getTable('amasty_amgiftcard_price')
        )->addColumn(
            'price_id',
            Table::TYPE_INTEGER,
            11,
            ['unsigned' => true, 'identity' => true, 'nullable' => false, 'primary' => true]
        )->addColumn(
            'product_id',
            Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'nullable' => false, 'default' => 0]
        )->addColumn(
            'website_id',
            Table::TYPE_SMALLINT,
            5,
            ['unsigned' => true, 'nullable' => false, 'default' => 0]
        )->addColumn(
            'attribute_id',
            Table::TYPE_SMALLINT,
            5,
            ['unsigned' => true, 'nullable' => false]
        )->addColumn(
            'value',
            Table::TYPE_DECIMAL,
            '12,2',
            ['nullable' => false, 'default' => '0.0000']
        )->addIndex(
            $installer->getIdxName('amasty_amgiftcard_price', ['product_id']),
            ['product_id']
        )->addIndex(
            $installer->getIdxName('amasty_amgiftcard_price', ['website_id']),
            ['website_id']
        )->addIndex(
            $installer->getIdxName('amasty_amgiftcard_price', ['attribute_id']),
            ['attribute_id']
        )->addForeignKey(
            $installer->getFkName('amasty_amgiftcard_price', 'product_id', 'catalog_product_entity', 'entity_id'),
            'product_id',
            $installer->getTable('catalog_product_entity'),
            'entity_id',
            Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('amasty_amgiftcard_price', 'website_id', 'store_website', 'website_id'),
            'website_id',
            $installer->getTable('store_website'),
            'website_id',
            Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('amasty_amgiftcard_price', 'attribute_id', 'eav_attribute', 'attribute_id'),
            'attribute_id',
            $installer->getTable('eav_attribute'),
            'attribute_id',
            Table::ACTION_CASCADE
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'amasty_amgiftcard_code_set'
         */

        $table = $installer->getConnection()->newTable(
            $installer->getTable('amasty_amgiftcard_code_set')
        )->addColumn(
            'code_set_id',
            Table::TYPE_INTEGER,
            11,
            ['unsigned' => true, 'identity' => true, 'nullable' => false, 'primary' => true]
        )->addColumn(
            'title',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'default' => '']
        )->addColumn(
            'template',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'default' => '']
        )->addColumn(
            'enabled',
            Table::TYPE_SMALLINT,
            1,
            ['nullable' => false, 'default' => 1]
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'amasty_amgiftcard_code'
         */

        $table = $installer->getConnection()->newTable(
            $installer->getTable('amasty_amgiftcard_code')
        )->addColumn(
            'code_id',
            Table::TYPE_INTEGER,
            11,
            ['unsigned' => true, 'identity' => true, 'nullable' => false, 'primary' => true]
        )->addColumn(
            'code_set_id',
            Table::TYPE_INTEGER,
            11,
            ['unsigned' => true, 'nullable' => true]
        )->addColumn(
            'code',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'default' => '']
        )->addColumn(
            'used',
            Table::TYPE_SMALLINT,
            1,
            ['nullable' => false, 'default' => 0]
        )->addColumn(
            'enabled',
            Table::TYPE_SMALLINT,
            1,
            ['nullable' => false, 'default' => 1]
        )->addIndex(
            $setup->getIdxName(
                'amasty_amgiftcard_code',
                ['code'],
                AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['code'],
            ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
        )->addIndex(
            $installer->getIdxName('amasty_amgiftcard_code', ['code_set_id']),
            ['code_set_id']
        )->addForeignKey(
            $installer->getFkName('amasty_amgiftcard_code', 'code_set_id', 'amasty_amgiftcard_code_set', 'code_set_id'),
            'code_set_id',
            $installer->getTable('amasty_amgiftcard_code_set'),
            'code_set_id',
            Table::ACTION_CASCADE
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'amasty_amgiftcard_image'
         */

        $table = $installer->getConnection()->newTable(
            $installer->getTable('amasty_amgiftcard_image')
        )->addColumn(
            'image_id',
            Table::TYPE_INTEGER,
            11,
            ['unsigned' => true, 'identity' => true, 'nullable' => false, 'primary' => true]
        )->addColumn(
            'title',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'default' => '']
        )->addColumn(
            'active',
            Table::TYPE_SMALLINT,
            1,
            ['nullable' => false, 'default' => 1]
        )->addColumn(
            'code_pos_x',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'default' => '']
        )->addColumn(
            'code_pos_y',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'default' => '']
        )->addColumn(
            'image_path',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true, 'default' => null]
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'amasty_amgiftcard_account'
         */

        $table = $installer->getConnection()->newTable(
            $installer->getTable('amasty_amgiftcard_account')
        )->addColumn(
            'account_id',
            Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'identity' => true, 'nullable' => false, 'primary' => true]
        )->addColumn(
            'code_id',
            Table::TYPE_INTEGER,
            11,
            ['unsigned' => true, 'nullable' => false]
        )->addColumn(
            'image_id',
            Table::TYPE_INTEGER,
            11,
            ['unsigned' => true, 'nullable' => true]
        )->addColumn(
            'order_id',
            Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'nullable' => true, 'default' => null]
        )->addColumn(
            'website_id',
            Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'nullable' => true]
        )->addColumn(
            'product_id',
            Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'nullable' => true]
        )->addColumn(
            'status_id',
            Table::TYPE_INTEGER,
            1,
            ['unsigned' => true, 'nullable' => false]
        )->addColumn(
            'initial_value',
            Table::TYPE_DECIMAL,
            '12,4',
            ['nullable' => false, 'default' => '0.0000']
        )->addColumn(
            'current_value',
            Table::TYPE_DECIMAL,
            '12,4',
            ['nullable' => false, 'default' => '0.0000']
        )->addColumn(
            'expired_date',
            Table::TYPE_DATETIME,
            null,
            ['nullable' => true, 'default' => null]
        )->addColumn(
            'comment',
            Table::TYPE_TEXT,
            null,
            ['nullable' => true, 'default' => null]
        )->addColumn(
            'sender_name',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true, 'default' => null]
        )->addColumn(
            'sender_email',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true, 'default' => null]
        )->addColumn(
            'recipient_name',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true, 'default' => null]
        )->addColumn(
            'recipient_email',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true, 'default' => null]
        )->addColumn(
            'sender_message',
            Table::TYPE_TEXT,
            null,
            ['nullable' => true, 'default' => null]
        )->addColumn(
            'image_path',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true, 'default' => null]
        )->addColumn(
            'date_delivery',
            Table::TYPE_DATETIME,
            null,
            ['nullable' => true, 'default' => null]
        )->addColumn(
            'is_sent',
            Table::TYPE_SMALLINT,
            1,
            ['nullable' => false, 'default' => 0]
        )->addIndex(
            $installer->getIdxName('amasty_amgiftcard_account', ['code_id']),
            ['code_id']
        )->addIndex(
            $installer->getIdxName('amasty_amgiftcard_account', ['product_id']),
            ['product_id']
        )->addForeignKey(
            $installer->getFkName('amasty_amgiftcard_account', 'code_id', 'amasty_amgiftcard_code', 'code_id'),
            'code_id',
            $installer->getTable('amasty_amgiftcard_code'),
            'code_id',
            Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('amasty_amgiftcard_account', 'product_id', 'catalog_product_entity', 'entity_id'),
            'product_id',
            $installer->getTable('catalog_product_entity'),
            'entity_id',
            Table::ACTION_CASCADE
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'amasty_amgiftcard_customer_card'
         */

        $table = $installer->getConnection()->newTable(
            $installer->getTable('amasty_amgiftcard_customer_card')
        )->addColumn(
            'customer_card_id',
            Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'identity' => true, 'nullable' => false, 'primary' => true]
        )->addColumn(
            'account_id',
            Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'nullable' => true]
        )->addColumn(
            'customer_id',
            Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'nullable' => false]
        )->addIndex(
            $installer->getIdxName('amasty_amgiftcard_customer_card', ['account_id']),
            ['account_id']
        )->addIndex(
            $installer->getIdxName('amasty_amgiftcard_customer_card', ['customer_id']),
            ['customer_id']
        )->addForeignKey(
            $installer->getFkName('amasty_amgiftcard_customer_card', 'account_id', 'amasty_amgiftcard_account', 'account_id'),
            'account_id',
            $installer->getTable('amasty_amgiftcard_account'),
            'account_id',
            Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('amasty_amgiftcard_customer_card', 'customer_id', 'customer_entity', 'entity_id'),
            'customer_id',
            $installer->getTable('customer_entity'),
            'entity_id',
            Table::ACTION_CASCADE
        );
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()->newTable(
            $installer->getTable('amasty_amgiftcard_quote')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Entity ID'
        )->addColumn(
            'quote_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Quote ID'
        )->addColumn(
            'code_id',
            Table::TYPE_INTEGER,
            11,
            ['unsigned' => true, 'nullable' => false],
            'Code Id'
        )->addColumn(
            'account_id',
            Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'nullable' => false]
        )->addColumn(
            'gift_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            ['nullable' => false, 'default' => '0.0000'],
            'Gift Amount'
        )->addColumn(
            'base_gift_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            ['nullable' => false, 'default' => '0.0000'],
            'Base Gift Amount'
        )->addIndex(
            $installer->getIdxName('amasty_amgiftcard_quote', ['account_id']),
            ['account_id']
        )->addIndex(
            $installer->getIdxName('amasty_amgiftcard_account', ['code_id']),
            ['code_id']
        )->addForeignKey(
            $installer->getFkName('amasty_amgiftcard_quote', 'account_id', 'amasty_amgiftcard_account', 'account_id'),
            'account_id',
            $installer->getTable('amasty_amgiftcard_account'),
            'account_id',
            Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('amasty_amgiftcard_quote', 'code_id', 'amasty_amgiftcard_code', 'code_id'),
            'code_id',
            $installer->getTable('amasty_amgiftcard_code'),
            'code_id',
            Table::ACTION_CASCADE
        );

        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }

}
