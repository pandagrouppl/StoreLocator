<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

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
        $installer = $setup;
        $installer->startSetup();

        /**
         * Create table 'guest_wishlist'
         */
        $tableName = $installer->getTable('guest_wishlist');

        if ($installer->tableExists($tableName) !== true) {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'wishlist_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Guest Wishlist ID'
                )
                ->addColumn(
                    'cookie',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    64,
                    [],
                    'Cookie'
                )
                ->addColumn(
                    'shared',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                    'Sharing flag (0 or 1)'
                )
                ->addColumn(
                    'sharing_code',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    32,
                    [],
                    'Sharing encrypted code'
                )
                ->addColumn(
                    'created_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    [],
                    'Created date'
                )
                ->addColumn(
                    'updated_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    [],
                    'Last updated date'
                )
                ->addIndex(
                    $installer->getIdxName(
                        $tableName,
                        'cookie',
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                    ),
                    'cookie',
                    ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
                )
                ->setComment('Guest Wishlist main Table');

            $installer->getConnection()->createTable($table);
        }

        /**
         * Create table 'guest_wishlist_item'
         */
        $tableName = $installer->getTable('guest_wishlist_item');

        if ($installer->tableExists($tableName) !== true) {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'wishlist_item_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Guest Wishlist item ID'
                )
                ->addColumn(
                    'wishlist_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                    'Wishlist ID'
                )
                ->addColumn(
                    'product_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                    'Product ID'
                )
                ->addColumn(
                    'store_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    ['unsigned' => true, 'nullable' => true],
                    'Store ID'
                )
                ->addColumn(
                    'added_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    [],
                    'Add date and time'
                )
                ->addColumn(
                    'description',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '64k',
                    [],
                    'Short description of wish list item'
                )
                ->addColumn(
                    'qty',
                    \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    '12,4',
                    ['nullable' => false],
                    'Qty'
                )
                ->addIndex(
                    $installer->getIdxName('guest_wishlist_item', 'wishlist_id'),
                    'wishlist_id'
                )
                ->addForeignKey(
                    $installer->getFkName(
                        'guest_wishlist_item',
                        'wishlist_id',
                        'guest_wishlist',
                        'wishlist_id'
                    ),
                    'wishlist_id',
                    $installer->getTable('guest_wishlist'),
                    'wishlist_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )
                ->addIndex(
                    $installer->getIdxName('guest_wishlist_item', 'product_id'),
                    'product_id'
                )
                ->addForeignKey(
                    $installer->getFkName(
                        'guest_wishlist_item',
                        'product_id',
                        'catalog_product_entity',
                        'entity_id'
                    ),
                    'product_id',
                    $installer->getTable('catalog_product_entity'),
                    'entity_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )
                ->addIndex(
                    $installer->getIdxName('guest_wishlist_item', 'store_id'),
                    'store_id'
                )
                ->addForeignKey(
                    $installer->getFkName(
                        'guest_wishlist_item',
                        'store_id',
                        'store',
                        'store_id'
                    ),
                    'store_id',
                    $installer->getTable('store'),
                    'store_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_SET_NULL
                )
                ->setComment('Guest Wishlist items');

            $installer->getConnection()->createTable($table);
        }

        /**
         * Create table 'guest_wishlist_item_option'
         */
        $tableName = $installer->getTable('guest_wishlist_item_option');

        if ($installer->tableExists($tableName) !== true) {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'option_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Option Id'
                )
                ->addColumn(
                    'wishlist_item_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'Guest Wishlist Item Id'
                )
                ->addColumn(
                    'product_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'Product Id'
                )
                ->addColumn(
                    'code',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Code'
                )
                ->addColumn(
                    'value',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '64k',
                    ['nullable' => true],
                    'Value'
                )
                ->addIndex(
                    $installer->getIdxName('guest_wishlist_item_option', 'wishlist_item_id'),
                    'wishlist_item_id'
                )
                ->addForeignKey(
                    $installer->getFkName(
                        'guest_wishlist_item_option',
                        'wishlist_item_id',
                        'guest_wishlist_item',
                        'wishlist_item_id'
                    ),
                    'wishlist_item_id',
                    $installer->getTable('guest_wishlist_item'),
                    'wishlist_item_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )
                ->setComment('Guest Wishlist Item Option Table');

            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}