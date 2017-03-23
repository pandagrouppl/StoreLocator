<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket_SocialLoginFree
 * @copyright   Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

namespace Plumrocket\SocialLoginFree\Setup;

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
         * Create table 'plumrocket_sociallogin_account'
         */
		$table = $installer->getConnection()
		    ->newTable($installer->getTable('plumrocket_sociallogin_account'))
		    ->addColumn('id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
		        'identity'  => true,
		        'unsigned'  => true,
		        'nullable'  => false,
		        'primary'   => true,
		        ], 'Id')
		    ->addColumn('type', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 30, [
		        'nullable'  => false,
		        ], 'Login type')
		    // ->addColumn('user_id', \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT, null, [
		    ->addColumn('user_id', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, [
		        // 'unsigned'  => true,
		        'nullable'  => false,
		        // 'default'   => '0',
		        ], 'User Id')
		    ->addColumn('customer_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
		        'unsigned'  => true,
		        'nullable'  => false,
		        'default'   => '0',
		        ], 'Customer Id')
		    ->addIndex($installer->getIdxName('plumrocket_sociallogin_account', ['type']), ['type'])
		    ->addIndex($installer->getIdxName('plumrocket_sociallogin_account', ['user_id']), ['user_id'])
		    ->addIndex($installer->getIdxName('plumrocket_sociallogin_account', ['customer_id']), ['customer_id'])
		    ->addForeignKey($installer->getFkName('plumrocket_sociallogin_account', 'customer_id', 'customer_entity', 'entity_id'),
		        'customer_id', $installer->getTable('customer_entity'), 'entity_id',
		        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE,
		        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE)
		    ->setComment('Social Login Customer');
		$installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}