<?php

namespace PandaGroup\StoreLocator\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /**
         * Create tables 'storelocator_data_countries' and 'storelocator_data_regions'
         */
        $countriesTableExistChecker = $setup->getTable('storelocator_data_countries');
        $regionsTableExistChecker = $setup->getTable('storelocator_data_regions');
        if (($setup->tableExists($countriesTableExistChecker) !== true) || ($setup->tableExists($regionsTableExistChecker) !== true)) {
            $filename = __DIR__ . '/world_informations.sql';

            $contents = file_get_contents($filename);
            echo "\nStart importing SQL file...\n";

            $sql = explode(";", $contents);
            foreach($sql as $query){
                if (true === empty($query)) continue;
                try {
                    $setup->run($query);
                    echo "DONE: Query imported\n";
                } catch (\Exception $e) {
                    $message = substr($e->getMessage(), 0, strpos($e->getMessage(), ','));

                    echo "ERROR: " . $message ."\n";
                }
            }
        }

        /**
         * Create table 'storelocator_states'
         */
        $tableExistChecker = $setup->getTable('storelocator_states');
        if ($setup->tableExists($tableExistChecker) !== true) {

            $storeLocatorRegionsTable = $setup->getConnection()->newTable(
                $setup->getTable('storelocator_states')
            )->addColumn(
                'state_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'StoreLocator State Id'
            )->addColumn(
                'state_source_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'StoreLocator State Id'
            )->addColumn(
                'state_name',
                Table::TYPE_TEXT,
                255,
                ['default' => '', 'nullable' => false],
                'State Name'
            )->addColumn(
                'state_short_name',
                Table::TYPE_TEXT,
                null,
                ['default' => '', 'nullable' => false],
                'State Short Name'
            )->addColumn(
                'country',
                Table::TYPE_TEXT,
                null,
                ['default' => '', 'nullable' => false],
                'Country'
            )->addColumn(
                'latitude',
                Table::TYPE_TEXT,
                30,
                ['default' => 0, 'nullable' => true],
                'Latitude'
            )->addColumn(
                'longtitude',
                Table::TYPE_TEXT,
                30,
                ['default' => 0, 'nullable' => true],
                'Longtitude'
            )->addColumn(
                'zoom_level',
                Table::TYPE_INTEGER,
                11,
                ['nullable' => true],
                'Zoom Level'
            )->setComment(
                'StoreLocator States'
            );

            $setup->getConnection()->createTable($storeLocatorRegionsTable);
        }

        /**
         * Create table 'storelocator'
         */
        $tableExistChecker = $setup->getTable('storelocator');
        if ($setup->tableExists($tableExistChecker) !== true) {

            $storeLocatorTable = $setup->getConnection()->newTable(
                $setup->getTable('storelocator')
            )->addColumn(
                'storelocator_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'StoreLocator Id'
            )->addColumn(
                'name',
                Table::TYPE_TEXT,
                255,
                ['default' => '', 'nullable' => false],
                'Name'
            )->addColumn(
                'rewrite_request_path',
                Table::TYPE_TEXT,
                255,
                ['default' => '', 'nullable' => false],
                'Rewrite Request Path'
            )->addColumn(
                'address',
                Table::TYPE_TEXT,
                255,
                ['default' => '', 'nullable' => false],
                'Address'
            )->addColumn(
                'city',
                Table::TYPE_TEXT,
                255,
                ['default' => '', 'nullable' => false],
                'City'
            )->addColumn(
                'country',
                Table::TYPE_TEXT,
                255,
                ['default' => '', 'nullable' => false],
                'Country'
            )->addColumn(
                'zipcode',
                Table::TYPE_TEXT,
                25,
                ['default' => '', 'nullable' => true],
                'Zipcode'
            )->addColumn(
                'state',
                Table::TYPE_TEXT,
                255,
                ['default' => '', 'nullable' => true],
                'State'
            )->addColumn(
                'state_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => true, 'unsigned' => true],
                'State Id'
            )->addColumn(
                'email',
                Table::TYPE_TEXT,
                255,
                ['default' => '', 'nullable' => true],
                'Email'
            )->addColumn(
                'phone',
                Table::TYPE_TEXT,
                25,
                ['default' => '', 'nullable' => true],
                'Phone'
            )->addColumn(
                'fax',
                Table::TYPE_TEXT,
                25,
                ['default' => '', 'nullable' => true],
                'Fax'
            )->addColumn(
                'description',
                Table::TYPE_TEXT,
                null,
                ['default' => '', 'nullable' => false],
                'Description'
            )->addColumn(
                'meta_keywords',
                Table::TYPE_TEXT,
                null,
                ['default' => '', 'nullable' => false],
                'Meta Keywords'
            )->addColumn(
                'meta_title',
                Table::TYPE_TEXT,
                null,
                ['default' => '', 'nullable' => false],
                'Meta Title'
            )->addColumn(
                'meta_contents',
                Table::TYPE_TEXT,
                null,
                ['default' => '', 'nullable' => false],
                'Meta Contents'
            )->addColumn(
                'status',
                Table::TYPE_BOOLEAN,
                25,
                ['default' => '0', 'nullable' => false],
                'Status'
            )->addColumn(
                'sort',
                Table::TYPE_INTEGER,
                10,
                ['default' => 0, 'nullable' => true],
                'Sort'
            )->addColumn(
                'link',
                Table::TYPE_TEXT,
                255,
                ['default' => '', 'nullable' => true],
                'Lik'
            )->addColumn(
                'latitude',
                Table::TYPE_TEXT,
                30,
                ['default' => 0, 'nullable' => true],
                'Latitude'
            )->addColumn(
                'longtitude',
                Table::TYPE_TEXT,
                30,
                ['default' => 0, 'nullable' => true],
                'Longtitude'
            )->addColumn(
                'zoom_level',
                Table::TYPE_INTEGER,
                11,
                ['nullable' => true],
                'Zoom Level'
            )

                ->addColumn(
                    'monday_status',
                    Table::TYPE_SMALLINT,
                    6,
                    ['default' => '1', 'nullable' => false],
                    'Monday Status'
                )->addColumn(
                    'monday_open',
                    Table::TYPE_TEXT,
                    5,
                    ['default' => '', 'nullable' => false],
                    'Monday Open'
                )->addColumn(
                    'monday_open_break',
                    Table::TYPE_TEXT,
                    5,
                    ['nullable' => false],
                    'Monday Open Break'
                )->addColumn(
                    'monday_close',
                    Table::TYPE_TEXT,
                    5,
                    ['default' => '', 'nullable' => false],
                    'Monday Close'
                )->addColumn(
                    'monday_close_break',
                    Table::TYPE_TEXT,
                    5,
                    ['nullable' => false],
                    'Monday Close Break'
                )->addColumn(
                    'tuesday_status',
                    Table::TYPE_SMALLINT,
                    6,
                    ['default' => '1', 'nullable' => false],
                    'Tuesday Status'
                )->addColumn(
                    'tuesday_open',
                    Table::TYPE_TEXT,
                    5,
                    ['default' => '', 'nullable' => false],
                    'Tuesday Open'
                )->addColumn(
                    'tuesday_open_break',
                    Table::TYPE_TEXT,
                    5,
                    ['nullable' => false],
                    'Tuesday Open Break'
                )->addColumn(
                    'tuesday_close',
                    Table::TYPE_TEXT,
                    5,
                    ['default' => '', 'nullable' => false],
                    'Tuesday Close'
                )->addColumn(
                    'tuesday_close_break',
                    Table::TYPE_TEXT,
                    5,
                    ['nullable' => false],
                    'Tuesday Close Break'
                )->addColumn(
                    'wednesday_status',
                    Table::TYPE_SMALLINT,
                    6,
                    ['default' => '1', 'nullable' => false],
                    'Wednesday Status'
                )->addColumn(
                    'wednesday_open',
                    Table::TYPE_TEXT,
                    5,
                    ['default' => '', 'nullable' => false],
                    'Wednesday Open'
                )->addColumn(
                    'wednesday_open_break',
                    Table::TYPE_TEXT,
                    5,
                    ['nullable' => false],
                    'Wednesday Open Break'
                )->addColumn(
                    'wednesday_close',
                    Table::TYPE_TEXT,
                    5,
                    ['default' => '', 'nullable' => false],
                    'Wednesday Close'
                )->addColumn(
                    'wednesday_close_break',
                    Table::TYPE_TEXT,
                    5,
                    ['nullable' => false],
                    'Wednesday Close Break'
                )->addColumn(
                    'thursday_status',
                    Table::TYPE_SMALLINT,
                    6,
                    ['default' => '1', 'nullable' => false],
                    'Thursday Status'
                )->addColumn(
                    'thursday_open',
                    Table::TYPE_TEXT,
                    5,
                    ['default' => '', 'nullable' => false],
                    'Thursday Open'
                )->addColumn(
                    'thursday_open_break',
                    Table::TYPE_TEXT,
                    5,
                    ['nullable' => false],
                    'Thursday Open Break'
                )->addColumn(
                    'thursday_close',
                    Table::TYPE_TEXT,
                    5,
                    ['default' => '', 'nullable' => false],
                    'Thursday Close'
                )->addColumn(
                    'thursday_close_break',
                    Table::TYPE_TEXT,
                    5,
                    ['nullable' => false],
                    'Thursday Close Break'
                )->addColumn(
                    'friday_status',
                    Table::TYPE_SMALLINT,
                    6,
                    ['default' => '1', 'nullable' => false],
                    'Friday Status'
                )->addColumn(
                    'friday_open',
                    Table::TYPE_TEXT,
                    5,
                    ['default' => '', 'nullable' => false],
                    'Friday Open'
                )->addColumn(
                    'friday_open_break',
                    Table::TYPE_TEXT,
                    5,
                    ['nullable' => false],
                    'Friday Open Break'
                )->addColumn(
                    'friday_close',
                    Table::TYPE_TEXT,
                    5,
                    ['default' => '', 'nullable' => false],
                    'Friday Close'
                )->addColumn(
                    'friday_close_break',
                    Table::TYPE_TEXT,
                    5,
                    ['nullable' => false],
                    'Friday Close Break'
                )->addColumn(
                    'saturday_status',
                    Table::TYPE_SMALLINT,
                    6,
                    ['default' => '1', 'nullable' => false],
                    'Saturday Status'
                )->addColumn(
                    'saturday_open',
                    Table::TYPE_TEXT,
                    5,
                    ['default' => '', 'nullable' => false],
                    'Saturday Open'
                )->addColumn(
                    'saturday_open_break',
                    Table::TYPE_TEXT,
                    5,
                    ['nullable' => false],
                    'Saturday Open Break'
                )->addColumn(
                    'saturday_close',
                    Table::TYPE_TEXT,
                    5,
                    ['default' => '', 'nullable' => false],
                    'Saturday Close'
                )->addColumn(
                    'saturday_close_break',
                    Table::TYPE_TEXT,
                    5,
                    ['nullable' => false],
                    'Saturday Close Break'
                )->addColumn(
                    'sunday_status',
                    Table::TYPE_SMALLINT,
                    6,
                    ['default' => '1', 'nullable' => false],
                    'Sunday Status'
                )->addColumn(
                    'sunday_open',
                    Table::TYPE_TEXT,
                    5,
                    ['default' => '', 'nullable' => false],
                    'Sunday Open'
                )->addColumn(
                    'sunday_open_break',
                    Table::TYPE_TEXT,
                    5,
                    ['nullable' => false],
                    'Sunday Open Break'
                )->addColumn(
                    'sunday_close',
                    Table::TYPE_TEXT,
                    5,
                    ['default' => '', 'nullable' => false],
                    'Sunday Close'
                )->addColumn(
                    'sunday_close_break',
                    Table::TYPE_TEXT,
                    5,
                    ['nullable' => false],
                    'Sunday Close Break'
                )

                ->addColumn(
                    'image_icon',
                    Table::TYPE_TEXT,
                    255,
                    ['default' => '', 'nullable' => true],
                    'Image Icon'
                )

                ->addForeignKey(
                    $setup->getFkName(
                        'storelocator',
                        'state_id',
                        'storelocator_states',
                        'state_id'
                    ),
                    'state_id',
                    $setup->getTable('storelocator_states'),
                    'state_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_SET_NULL
                )

                ->setComment(
                    'StoreLocator'
                );
            $setup->getConnection()->createTable($storeLocatorTable);
        }

        /**
         * Create table 'storelocator_image'
         */
        $tableExistChecker = $setup->getTable('storelocator_image');
        if ($setup->tableExists($tableExistChecker) !== true) {

            $storeLocatorImageTable = $setup->getConnection()->newTable(
                $setup->getTable('storelocator_image')
            )->addColumn(
                'image_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Image Id'
            )->addColumn(
                'image_delete',
                Table::TYPE_INTEGER,
                null,
                [],
                'Image Delete'
            )->addColumn(
                'options',
                Table::TYPE_INTEGER,
                null,
                [],
                'Options'
            )->addColumn(
                'name',
                Table::TYPE_TEXT,
                255,
                [],
                'Name'
            )->addColumn(
                'statuses',
                Table::TYPE_INTEGER,
                null,
                [],
                'Statuses'
            )->addColumn(
                'storelocator_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Storelocator Id'
            )->addForeignKey(
                $setup->getFkName(
                    'storelocator_image',
                    'storelocator_id',
                    'storelocator',
                    'storelocator_id'
                ),
                'storelocator_id',
                $setup->getTable('storelocator'),
                'storelocator_id',
                Table::ACTION_CASCADE
            )->setComment(
                'StoreLocator Image'
            );

            $setup->getConnection()->createTable($storeLocatorImageTable);
        }

        /**
         * Create table 'storelocator_value'
         */
        $tableExistChecker = $setup->getTable('storelocator_value');
        if ($setup->tableExists($tableExistChecker) !== true) {

            $storeLocatorValueTable = $setup->getConnection()->newTable(
                $setup->getTable('storelocator_value')
            )->addColumn(
                'value_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Value Id'
            )->addColumn(
                'storelocator_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'StoreLocator Id'
            )->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                5,
                ['unsigned' => true, 'nullable' => false],
                'Store Id'
            )->addColumn(
                'attribute_code',
                Table::TYPE_TEXT,
                63,
                ['default' => '', 'nullable' => false],
                'Attribute Code'
            )->addColumn(
                'value',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Value'
            )->addIndex(
                $setup->getIdxName(
                    $tableExistChecker,
                    ['storelocator_id', 'store_id', 'attribute_code'],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['storelocator_id', 'store_id', 'attribute_code'],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            )->addForeignKey(
                $setup->getFkName(
                    'storelocator_value',
                    'storelocator_id',
                    'storelocator',
                    'storelocator_id'
                ),
                'storelocator_id',
                $setup->getTable('storelocator'),
                'storelocator_id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $setup->getFkName(
                    'storelocator_value',
                    'store_id',
                    'core/store',
                    'store_id'
                ),
                'store_id',
                $setup->getTable('core/store'),
                'store_id',
                Table::ACTION_CASCADE
            )->setComment(
                'StoreLocator Value'
            );

            $setup->getConnection()->createTable($storeLocatorValueTable);
        }

        /**
         * Create table 'storelocator_tag'
         */
        $tableExistChecker = $setup->getTable('storelocator_tag');
        if ($setup->tableExists($tableExistChecker) !== true) {

            $storeLocatorTagTable = $setup->getConnection()->newTable(
                $setup->getTable('storelocator_tag')
            )->addColumn(
                'tag_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Tag Id'
            )->addColumn(
                'storelocator_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'StoreLocator Id'
            )->addColumn(
                'value',
                Table::TYPE_TEXT,
                255,
                [],
                'Value'
            )->addIndex(
                $setup->getIdxName(
                    $tableExistChecker,
                    'storelocator_id'
                ),
                'storelocator_id'
            )->addForeignKey(
                $setup->getFkName(
                    'storelocator_tag',
                    'storelocator_id',
                    'storelocator',
                    'storelocator_id'
                ),
                'storelocator_id',
                $setup->getTable('storelocator'),
                'storelocator_id',
                Table::ACTION_CASCADE
            )->setComment(
                'StoreLocator Tag'
            );

            $setup->getConnection()->createTable($storeLocatorTagTable);
        }

        /**
         * Create table 'storelocator_specialday'
         */
        $tableExistChecker = $setup->getTable('storelocator_specialday');
        if ($setup->tableExists($tableExistChecker) !== true) {

            $storeLocatorSpecialDayTable = $setup->getConnection()->newTable(
                $setup->getTable('storelocator_specialday')
            )->addColumn(
                'storelocator_specialday_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Special Day Id'
            )->addColumn(
                'specialday_name',
                Table::TYPE_TEXT,
                255,
                ['default' => '', 'nullable' => false],
                'Specialday Name'
            )->addColumn(
                'store_id',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Store Id'
            )->addColumn(
                'date',
                Table::TYPE_DATE,
                null,
                ['nullable' => false],
                'Date'
            )->addColumn(
                'specialday_date_to',
                Table::TYPE_DATE,
                null,
                ['nullable' => false],
                'Special Day Date To'
            )->addColumn(
                'specialday_time_open',
                Table::TYPE_TEXT,
                5,
                ['nullable' => false],
                'Special Day Date Open'
            )->addColumn(
                'specialday_time_close',
                Table::TYPE_TEXT,
                5,
                ['nullable' => false],
                'Special Day Date Close'
            )->addColumn(
                'comment',
                Table::TYPE_TEXT,
                255,
                ['default' => null, 'nullable' => true],
                'Comment'
            )->setComment(
                'StoreLocator Special Day'
            );

            $setup->getConnection()->createTable($storeLocatorSpecialDayTable);
        }

        /**
         * Create table 'storelocator_holiday'
         */
        $tableExistChecker = $setup->getTable('storelocator_holiday');
        if ($setup->tableExists($tableExistChecker) !== true) {

            $storeLocatorHolidayTable = $setup->getConnection()->newTable(
                $setup->getTable('storelocator_holiday')
            )->addColumn(
                'storelocator_holiday_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'StoreLocator Holiday Id'
            )->addColumn(
                'holiday_name',
                Table::TYPE_TEXT,
                255,
                ['default' => '', 'nullable' => false],
                'Holiday Name'
            )->addColumn(
                'store_id',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Store Id'
            )->addColumn(
                'date',
                Table::TYPE_DATE,
                null,
                ['nullable' => false],
                'Date'
            )->addColumn(
                'holiday_date_to',
                Table::TYPE_DATE,
                null,
                ['nullable' => false],
                'Holiday Date To'
            )->addColumn(
                'comment',
                Table::TYPE_TEXT,
                255,
                ['default' => null, 'nullable' => true],
                'Comment'
            )->setComment(
                'StoreLocator Holiday'
            );

            $setup->getConnection()->createTable($storeLocatorHolidayTable);
        }

        $setup->endSetup();
    }
}