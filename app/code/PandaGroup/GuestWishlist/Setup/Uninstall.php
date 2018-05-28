<?php
/**
 * PandaGroup
 *
 * @copyright  Copyright(c) 2017 PandaGroup (http://pandagroup.co)
 * @author     Michal Okupniarek <mokupniarek@light4website.com>
 */

namespace PandaGroup\GuestWishlist\Setup;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * @codeCoverageIgnore
 */
class Uninstall implements UninstallInterface
{
    /**
     * {@inheritdoc}
     */
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $setup->startSetup();

        $tableName = $installer->getTable('guest_wishlist_item_option');
        $setup->getConnection()->dropTable($tableName);

        $tableName = $installer->getTable('guest_wishlist_item');
        $setup->getConnection()->dropTable($tableName);

        $tableName = $installer->getTable('guest_wishlist');
        $setup->getConnection()->dropTable($tableName);

        $setup->endSetup();
    }
}