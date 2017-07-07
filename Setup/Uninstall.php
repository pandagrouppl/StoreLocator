<?php

namespace PandaGroup\StoreLocator\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;

class Uninstall implements UninstallInterface
{
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /**
         * Delete table 'storelocator_image'
         */
        $setup->getConnection()->dropTable('storelocator_image');

        /**
         * Delete table 'storelocator_value'
         */
        $setup->getConnection()->dropTable('storelocator_value');

        /**
         * Delete table 'storelocator_tag'
         */
        $setup->getConnection()->dropTable('storelocator_tag');

        /**
         * Delete table 'storelocator_specialday'
         */
        $setup->getConnection()->dropTable('storelocator_specialday');

        /**
         * Delete table 'storelocator_holiday'
         */
        $setup->getConnection()->dropTable('storelocator_holiday');

        /**
         * Delete table 'storelocator'
         */
        $setup->getConnection()->dropTable('storelocator');

        /**
         * Delete table 'storelocator_states'
         */
        $setup->getConnection()->dropTable('storelocator_states');

        /**
         * Delete table 'storelocator_states'
         */
        $setup->getConnection()->dropTable('storelocator_data_countries');

        /**
         * Delete table 'storelocator_states'
         */
        $setup->getConnection()->dropTable('storelocator_data_regions');

        $setup->endSetup();
    }
}