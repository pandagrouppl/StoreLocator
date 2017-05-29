<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */
namespace Amasty\Shopby\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;


class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $this->addPriceSliderColumnsToFilterSettings($setup);
        }

        if (version_compare($context->getVersion(), '1.2.2.1', '<')) {
            $this->addIndexModeColumnsToFilterSettings($setup);
        }

        if (version_compare($context->getVersion(), '1.3.1', '<')) {
            $this->addHideOneOptionColumnToFilterSettings($setup);
        }

        if (version_compare($context->getVersion(), '1.5.0', '<')) {
            $this->createOptionSettingTable($setup);
        }

        if (version_compare($context->getVersion(), '1.6.1', '<')) {
            $this->addCollapsedColumn($setup);
        }

        if (version_compare($context->getVersion(), '1.6.2', '<')) {
            $this->addDisplayProperties($setup);
        }

        if (version_compare($context->getVersion(), '1.6.3', '<')) {
            $this->addTooltips($setup);
        }

        if (version_compare($context->getVersion(), '1.6.4', '<')) {
            $this->renameCollapsedColumn($setup);
        }

        if (version_compare($context->getVersion(), '1.7.2', '<')) {
            $this->addUseAndLogicField($setup);
        }

        if (version_compare($context->getVersion(), '1.7.3', '<')) {
            $this->addFromToFilterSetting($setup);
        }

        if (version_compare($context->getVersion(), '1.7.4', '<')) {
            $this->addVisibleInCategoryFilterSetting($setup);
        }

        if (version_compare($context->getVersion(), '1.7.5', '<')) {
            $this->addAttributeFilterSetting($setup);
        }

        if (version_compare($context->getVersion(), '1.9.0', '<')) {
            $this->addBrandSliderSetting($setup);
        }

        if (version_compare($context->getVersion(), '1.10.0', '<')) {
            $this->addPlacedBlockToFilterSetting($setup);
        }

        if (version_compare($context->getVersion(), '1.13.2', '<')) {
            $this->addCmsPageTable($setup);
        }

        if (version_compare($context->getVersion(), '1.14.7', '<')) {
            $this->addRangeSliderColumnsToFilterSettings($setup);
            $this->addRelNofollowColumnToFilterSettings($setup);
        }

        $setup->endSetup();
    }

    protected function addPriceSliderColumnsToFilterSettings(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('amasty_amshopby_filter_setting'),
            'slider_step',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                'length' => '12,4',
                'nullable' => false,
                'default' => '1.00',
                'comment' => 'Slider Step'
            ]
        );
        $setup->getConnection()->addColumn(
            $setup->getTable('amasty_amshopby_filter_setting'),
            'units_label_use_currency_symbol',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                'nullable' => false,
                'default' => true,
                'comment' => 'is Units label used currency symbol'
            ]
        );
        $setup->getConnection()->addColumn(
            $setup->getTable('amasty_amshopby_filter_setting'),
            'units_label',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => '255',
                'nullable' => false,
                'default' => '',
                'comment' => 'Units label'
            ]
        );
    }

    protected function addIndexModeColumnsToFilterSettings(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('amasty_amshopby_filter_setting'),
            'index_mode',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'nullable' => false,
                'default' => '0',
                'comment' => 'Robots Index Mode'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('amasty_amshopby_filter_setting'),
            'follow_mode',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'nullable' => false,
                'default' => '0',
                'comment' => 'Robots Follow Mode'
            ]
        );
    }

    protected function addHideOneOptionColumnToFilterSettings(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('amasty_amshopby_filter_setting'),
                'hide_one_option',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'nullable' => false,
                'default' => '0',
                'comment' => 'Hide filter when only one option available'
            ]
        );
    }

    protected function createOptionSettingTable(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable('amasty_amshopby_option_setting');
        $table = $setup->getConnection()
            ->newTable($tableName)
            ->addColumn(
                'option_setting_id',
                Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true]
            )
            ->addColumn('filter_code', Table::TYPE_TEXT, 255, ['nullable' => false])
            ->addColumn('value', Table::TYPE_INTEGER, 11, ['nullable' => false])
            ->addColumn('store_id', Table::TYPE_INTEGER, null, ['nullable' => false, 'default'=>0])
            ->addColumn('url_alias', Table::TYPE_TEXT, 255, ['nullable' => false])
            ->addColumn('is_featured', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default'=>0])
            ->addColumn('meta_title', Table::TYPE_TEXT, 1000, ['nullable' => false])
            ->addColumn('meta_description', Table::TYPE_TEXT, 10000)
            ->addColumn('meta_keywords', Table::TYPE_TEXT, 10000)
            ->addColumn('title', Table::TYPE_TEXT, 1000, ['nullable' => false])
            ->addColumn('description', Table::TYPE_TEXT, 10000)
            ->addColumn('image', Table::TYPE_TEXT, 255)
            ->addColumn('top_cms_block_id', Table::TYPE_INTEGER)
            ->addColumn('bottom_cms_block_id', Table::TYPE_INTEGER);

        $setup->getConnection()->createTable($table);
    }

    protected function addCollapsedColumn(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('amasty_amshopby_filter_setting'),
            'is_collapsed',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'nullable' => false,
                'default' => '1',
                'comment' => 'Is filter collapsed'
            ]
        );
    }

    protected function addDisplayProperties(SchemaSetupInterface $setup)
    {
        $table = $setup->getTable('amasty_amshopby_filter_setting');
        $connection = $setup->getConnection();

        $connection->addColumn(
            $table,
            'sort_options_by',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => false,
                'default' => '0',
                'comment' => 'Sort Options By'
            ]
        );

        $connection->addColumn(
            $table,
            'show_product_quantities',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => false,
                'default' => '0',
                'comment' => 'Show Product Quantities'
            ]
        );

        $connection->addColumn(
            $table,
            'is_show_search_box',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => false,
                'default' => '0',
                'comment' => 'Show Search Box'
            ]
        );

        $connection->addColumn(
            $table,
            'number_unfolded_options',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => false,
                'default' => '0',
                'comment' => 'Number of unfolded options'
            ]
        );
    }

    protected function addTooltips(SchemaSetupInterface $setup)
    {
        $table = $setup->getTable('amasty_amshopby_filter_setting');
        $connection = $setup->getConnection();

        $connection->addColumn(
            $table,
            'tooltip',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => false,
                'default' => '',
                'comment' => 'Tooltip'
            ]
        );
    }

    protected function renameCollapsedColumn(SchemaSetupInterface $setup)
    {
        $table = $setup->getTable('amasty_amshopby_filter_setting');

        $sql = "ALTER TABLE `$table` CHANGE `is_collapsed` `is_expanded` INT(11) NOT NULL DEFAULT '0' COMMENT 'Is filter expanded'";
        $setup->getConnection()->query($sql);

        $sql = "UPDATE `$table` SET `is_expanded` = 1 - `is_expanded`;";
        $setup->getConnection()->query($sql);
    }


    protected function addFromToFilterSetting(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('amasty_amshopby_filter_setting'),
            'add_from_to_widget',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                'nullable' => false,
                'default' => false,
                'comment' => 'Add From To Widget'
            ]
        );
    }

    protected function addUseAndLogicField(SchemaSetupInterface $setup)
    {
        $table = $setup->getTable('amasty_amshopby_filter_setting');
        $connection = $setup->getConnection();

        $connection->addColumn(
            $table,
            'is_use_and_logic',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => false,
                'default' => 0,
                'comment' => 'Is Use And Logic'
            ]
        );
    }

    protected function addVisibleInCategoryFilterSetting(SchemaSetupInterface $setup)
    {
        $table = $setup->getTable('amasty_amshopby_filter_setting');
        $connection = $setup->getConnection();

        $connection->addColumn(
            $table,
            'visible_in_categories',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => '255',
                'nullable' => false,
                'default' => \Amasty\Shopby\Model\Source\VisibleInCategory::VISIBLE_EVERYWHERE,
                'comment' => 'Visible In Categories'
            ]
        );

        $connection->addColumn(
            $table,
            'categories_filter',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => '255',
                'nullable' => false,
                'default' => '',
                'comment' => 'Categories Filter'
            ]
        );
    }

    protected function addAttributeFilterSetting(SchemaSetupInterface $setup)
    {
        $table = $setup->getTable('amasty_amshopby_filter_setting');
        $connection = $setup->getConnection();

        $connection->addColumn(
            $table,
            'attributes_filter',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => '255',
                'nullable' => false,
                'default' => '',
                'comment' => 'Attributes Filter'
            ]
        );

        $connection->addColumn(
            $table,
            'attributes_options_filter',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => '255',
                'nullable' => false,
                'default' => '',
                'comment' => 'Attributes Options Filter'
            ]
        );
    }

    protected function addBrandSliderSetting(SchemaSetupInterface $setup)
    {
        $table = $setup->getTable('amasty_amshopby_option_setting');
        $connection = $setup->getConnection();

        $connection->addColumn(
            $table,
            'slider_position',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'nullable' => false,
                'default' => '0',
                'comment' => 'Slider Position'
            ]
        );
        $connection->addColumn(
            $table,
            'slider_image',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => '255',
                'comment' => 'Slider Image'
            ]
        );
    }

    protected function addPlacedBlockToFilterSetting(SchemaSetupInterface $setup)
    {
        $table = $setup->getTable('amasty_amshopby_filter_setting');
        $connection = $setup->getConnection();

        $connection->addColumn(
            $table,
            'block_position',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => false,
                'default' => '0',
                'comment' => 'Show in the Block'
            ]
        );

    }

    public function addCmsPageTable(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable('amasty_amshopby_cms_page');
        $table = $setup->getConnection()
            ->newTable($tableName)
            ->addColumn(
                'entity_id',
                Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true]
            )
            ->addColumn('page_id', Table::TYPE_SMALLINT, null, ['nullable' => false])
            ->addColumn('enabled', Table::TYPE_BOOLEAN, null, ['nullable' => false, 'default' => false])
            ->addForeignKey(
                $setup->getFkName('amasty_amshopby_cms_page', 'page_id', 'cms_page', 'page_id'),
                'page_id',
                $setup->getTable('cms_page'),
                'page_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            );

        $setup->getConnection()->createTable($table);
    }

    protected function addRangeSliderColumnsToFilterSettings(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('amasty_amshopby_filter_setting'),
            'slider_min',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                'length' => '12,4',
                'nullable' => true,
                'comment' => 'Slider Min Value'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('amasty_amshopby_filter_setting'),
            'slider_max',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                'length' => '12,4',
                'nullable' => true,
                'comment' => 'Slider Max Value'
            ]
        );
    }

    protected function addRelNofollowColumnToFilterSettings(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('amasty_amshopby_filter_setting'),
            'rel_nofollow',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => false,
                'default' => '1',
                'comment' => 'Add rel="nofollow"',
            ]
        );
    }
}
