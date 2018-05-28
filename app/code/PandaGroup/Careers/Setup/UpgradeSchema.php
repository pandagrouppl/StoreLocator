<?php

namespace PandaGroup\Careers\Setup;

use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{
    public function upgrade(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $setup->startSetup();

        /**
         * Create table 'pandagroup_careers_email_queue'
         */
        $careersEmailQueue = $setup->getTable('pandagroup_careers_email_queue');
        if ($setup->tableExists($careersEmailQueue) !== true) {
            $careersEmailQueueTable = $setup->getConnection()->newTable(
                $setup->getTable('pandagroup_careers_email_queue')
            )->addColumn(
                'email_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Email Id'
            )->addColumn(
                'first_name',
                Table::TYPE_TEXT,
                null,
                ['default' => '', 'nullable' => false],
                'First Name'
            )->addColumn(
                'last_name',
                Table::TYPE_TEXT,
                null,
                ['default' => '', 'nullable' => false],
                'Last Name'
            )->addColumn(
                'email',
                Table::TYPE_TEXT,
                null,
                ['default' => '', 'nullable' => false],
                'Email'
            )->addColumn(
                'phone',
                Table::TYPE_TEXT,
                30,
                ['default' => '', 'nullable' => false],
                'Phone'
            )->addColumn(
                'filename',
                Table::TYPE_TEXT,
                null,
                ['default' => '', 'nullable' => false],
                'Filename'
            )->addColumn(
                'send_status',
                Table::TYPE_INTEGER,
                1,
                ['default' => '0', 'nullable' => false],
                'Send Status'
            )->setComment(
                'Career Emails Queue Table'
            );

            $setup->getConnection()->createTable($careersEmailQueueTable);
            echo "\nModule 'PandaGroup_Careers': Table 'pandagroup_careers_email_queue' installed successfully.";
        }

        $setup->getConnection()->addColumn(
            $careersEmailQueue,
            'message',
            [
                'type'      => Table::TYPE_TEXT,
                'length'    => 255,
                'default'   => '',
                'nullable'  => true,
                'comment'   => 'Error Message'
            ]
        );

        $setup->endSetup();
    }
}
