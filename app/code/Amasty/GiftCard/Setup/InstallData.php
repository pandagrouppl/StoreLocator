<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */


namespace Amasty\GiftCard\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Amasty\Base\Helper\Deploy as DeployHelper;

class InstallData implements InstallDataInterface
{
    /**
     * @var EavSetupFactory
     */
    protected $eavSetupFactory;
    /**
     * @var DeployHelper
     */
    protected $deployHelper;

    public function __construct(
        EavSetupFactory $eavSetupFactory,
        DeployHelper $deployHelper
    )
    {

        $this->eavSetupFactory = $eavSetupFactory;
        $this->deployHelper = $deployHelper;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->deployHelper->deployFolder(dirname(__DIR__) . '/pub');

        $imageData = [
            [
                'title' => 'Gift Card 1',
                'active' => 1,
                'code_pos_x' => '61',
                'code_pos_y' => '148',
                'image_path' => '551d40ec2dc18_gift-card-1.png',
            ],
            [
                'title' => 'Gift Card 2',
                'active' => 1,
                'code_pos_x' => '228',
                'code_pos_y' => '167',
                'image_path' => '551d40fc14c23_gift-card-2.png',
            ],
            [
                'title' => 'Gift Card 3',
                'active' => 1,
                'code_pos_x' => '208',
                'code_pos_y' => '148',
                'image_path' => '551d413453017_gift-card-3.png',
            ],
            [
                'title' => 'Gift Card 4',
                'active' => 1,
                'code_pos_x' => '215',
                'code_pos_y' => '174',
                'image_path' => '551d414d52e85_gift-card-4.png',
            ],
            [
                'title' => 'Gift Card 5',
                'active' => 1,
                'code_pos_x' => '216',
                'code_pos_y' => '165',
                'image_path' => '551d4164a4a3a_gift-card-5.png',
            ],
            [
                'title' => 'Happy Birthday Gift Card 1',
                'active' => 1,
                'code_pos_x' => '233',
                'code_pos_y' => '145',
                'image_path' => '551d41c00c6af_happy-birthday-gift-card-1.png',
            ],
            [
                'title' => 'Happy Birthday Gift Card 2',
                'active' => 1,
                'code_pos_x' => '243',
                'code_pos_y' => '166',
                'image_path' => '551d41de12c53_happy-birthday-gift-card-2.png',
            ],
            [
                'title' => 'Happy New Year Gift Card',
                'active' => 1,
                'code_pos_x' => '157',
                'code_pos_y' => '136',
                'image_path' => '551d41faa1694_happy-new-year-gift-card.png',
            ],
            [
                'title' => 'Xmas Gift Card 1',
                'active' => 1,
                'code_pos_x' => '166',
                'code_pos_y' => '157',
                'image_path' => '551d421dd605e_-xmas-gift-card-1.png',
            ],
            [
                'title' => 'Xmas Gift Card 2',
                'active' => 1,
                'code_pos_x' => '77',
                'code_pos_y' => '177',
                'image_path' => '551d4233783ef_-xmas-gift-card-2.png',
            ]
        ];

        $setup->getConnection()->insertMultiple(
            $setup->getTable('amasty_amgiftcard_image'),
            $imageData
        );


        /** @var \Magento\Eav\Setup\EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $attributeGroupName = 'Gift Card Information';
        $entityType = ProductAttributeInterface::ENTITY_TYPE_CODE;
        $eavSetup->addAttributeGroup($entityType, 'Default', $attributeGroupName, 9);

        $entityTypeId = $eavSetup->getEntityTypeId($entityType);

        # ---------------------- BUG PROBLEM ---------------------- #
//        $group = 'Prices';

        # ---------------------- BUG SOLUTION --------------------- #
        $groupAttribute = $eavSetup->getAttributeGroupByCode($entityTypeId, 'Default', 'gift-card-information');
        $pricesGroup = $eavSetup->getAttributeGroupByCode($entityTypeId, 'Default', 'advanced-pricing');
        if (isset($groupAttribute)) {
            $group = '';
        } else {
            $group = 'Prices';
        }
        # ------------------------ END BUG ------------------------ #

        $eavSetup->addAttribute(
            $entityType,
            'am_giftcard_prices',
            [
                'type' => 'decimal',
                'label' => 'Amounts',
                'backend' => '\Amasty\GiftCard\Model\Attribute\Backend\GiftCard\Price',
                'input' => 'price',
                'source' => '',
                'required' => false,
                'sort_order' => -5,
                'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
                'group' => $group,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'apply_to' => 'amgiftcard',
                'visible'  => true
            ]
        );

        $eavSetup->addAttribute(
            $entityType,
            'am_allow_open_amount',
            [
	            'backend' => '',
	            'frontend' => '',
	            'label' => '',
	            'input' => '',
	            'class' => '',
	            'source' => '',
	            'visible' => false,
	            'required' => true,
	            'user_defined' => false,
	            'default' => '',
	            'searchable' => false,
	            'filterable' => false,
	            'comparable' => false,
	            'visible_on_front' => false,
	            'unique' => false,
                'type' => 'int',
                'sort_order' => -4,
                'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
                'group' => $group,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'apply_to' => 'amgiftcard',
            ]
        );

        $eavSetup->addAttribute(
            $entityType,
            'am_open_amount_min',
            [
                'type' => 'decimal',
                'label' => 'Open Amount Min Value',
                'backend' => '\Magento\Catalog\Model\Product\Attribute\Backend\Price',
                'input' => 'price',
                'source' => '',
                'required' => false,
                'sort_order' => -3,
                'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
                'group' => $group,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'apply_to' => 'amgiftcard',
                'class' => 'validate-number',
                'visible'  => true,
	            'used_in_product_listing' => true,
            ]
        );

        $eavSetup->addAttribute(
            $entityType,
            'am_open_amount_max',
            [
                'type' => 'decimal',
                'label' => 'Open Amount Max Value',
                'backend' => '\Magento\Catalog\Model\Product\Attribute\Backend\Price',
                'input' => 'price',
                'source' => '',
                'required' => false,
                'sort_order' => -2,
                'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
                'group' => $group,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'apply_to' => 'amgiftcard',
                'class' => 'validate-number',
                'visible'  => true,
                'used_in_product_listing' => true,
            ]
        );

	    $eavSetup->addAttribute(
		    $entityType,
		    'am_giftcard_fee_enable',
		    [
			    'type' => 'int',
			    'backend' => '',
			    'input' => '',
			    'frontend' => '',
			    'label' => '',
			    'class' => '',
			    'source' => '',
			    'required' => true,
			    'sort_order' => -1,
			    'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
                'group' => $group,
			    'is_used_in_grid' => true,
			    'is_visible_in_grid' => false,
			    'is_filterable_in_grid' => false,
			    'apply_to' => 'amgiftcard',
			    'visible'  => true
		    ]
	    );

        $eavSetup->addAttribute(
            $entityType,
            'am_giftcard_fee_type',
            [
                'type' => 'int',
                'label' => 'Add a fee for purchase',
                'backend' => '',
                'input' => 'select',
                'source' => '\Amasty\GiftCard\Model\Config\Source\Fee',
                'required' => false,
                'sort_order' => 0,
                'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
                'group' => $group,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'apply_to' => 'amgiftcard',
                'visible'  => true
            ]
        );

        $eavSetup->addAttribute(
            $entityType,
            'am_giftcard_fee_value',
            [
                'type' => 'decimal',
                'label' => 'Specify fee value',
                'backend' => '',
                'input' => 'text',
                'source' => '',
                'required' => false,
                'sort_order' => 1,
                'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
                'group' => $group,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'apply_to' => 'amgiftcard',
                'visible'  => true
            ]
        );

        // Attributes to gift card tab

        $eavSetup->addAttribute(
            $entityType,
            'am_giftcard_type',
            [
                'type' => 'int',
                'label' => 'Card Type',
                'backend' => '',
                'input' => 'select',
                'source' => '\Amasty\GiftCard\Model\Config\Source\GiftCardType',
                'required' => true,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'group' => $attributeGroupName,
                'visible_on_front' => false,
                'apply_to' => 'amgiftcard',
                'visible'  => true
            ]
        );

        $eavSetup->addAttribute(
            $entityType,
            'am_giftcard_lifetime',
            [
                'type' => 'int',
                'label' => 'Lifetime (days)',
                'backend' => '\Amasty\GiftCard\Model\Product\Attribute\Backend\UseConfig\Lifetime',
                'input' => 'text',
                'source' => '',
                'required' => false,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'group' => $attributeGroupName,
                'visible_on_front' => false,
                'apply_to' => 'amgiftcard',
                'visible'  => true
            ]
        );

        $eavSetup->addAttribute(
            $entityType,
            'am_allow_message',
            [
                'type' => 'int',
                'label' => 'Allow Message',
                'backend' => '\Magento\Catalog\Model\Product\Attribute\Backend\Boolean',
                'input' => 'select',
                'source' => '\Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'required' => false,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => $attributeGroupName,
                'visible_on_front' => false,
                'apply_to' => 'amgiftcard',
                'visible'  => true
            ]
        );

        $eavSetup->addAttribute(
            $entityType,
            'am_email_template',
            [
                'type' => 'varchar',
                'label' => 'Email Template',
                'backend' => '\Amasty\GiftCard\Model\Product\Attribute\Backend\UseConfig\EmailTemplate',
                'input' => 'select',
                'source' => '\Amasty\GiftCard\Model\Config\Source\EmailTemplate',
                'required' => false,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => $attributeGroupName,
                'visible_on_front' => false,
                'apply_to' => 'amgiftcard',
                'visible'  => true
            ]
        );

        $eavSetup->addAttribute(
            $entityType,
            'am_giftcard_code_set',
            [
                'type' => 'int',
                'label' => 'Choose gift card code pool',
                'backend' => '\Magento\Catalog\Model\Product\Attribute\Backend\Boolean',
                'input' => 'select',
                'source' => '\Amasty\GiftCard\Model\Config\Source\GiftCardCodeSet',
                'required' => true,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'group' => $attributeGroupName,
                'visible_on_front' => false,
                'apply_to' => 'amgiftcard',
                'visible'  => true
            ]
        );

        $eavSetup->addAttribute(
            $entityType,
            'am_giftcard_code_image',
            [
                'type' => 'varchar',
                'label' => 'Choose gift card images',
                'backend' => '\Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',//'\Amasty\GiftCard\Model\Attribute\Backend\GiftCard\Image',
                'input' => 'multiselect',
                'source' => '\Amasty\GiftCard\Model\Config\Source\Image',
                'required' => true,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'group' => $attributeGroupName,
                'visible_on_front' => false,
                'apply_to' => 'amgiftcard',
                'visible'  => true
            ]
        );
    }
}