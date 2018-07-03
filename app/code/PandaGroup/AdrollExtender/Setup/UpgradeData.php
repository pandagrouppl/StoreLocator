<?php

namespace PandaGroup\AdrollExtender\Setup;

class UpgradeData implements \Magento\Framework\Setup\UpgradeDataInterface
{
    /** @var \Magento\Eav\Setup\EavSetupFactory  */
    private $eavSetupFactory;

    /**
     * Constructor
     *
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     */
    public function __construct(\Magento\Eav\Setup\EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     */
    public function upgrade(
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        /** @var \Magento\Eav\Setup\EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        // Add 'adroll_img' product attribute
        if (version_compare($context->getVersion(), "1.0.0", "<")) {

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'adroll_img',
                [
                    'type' => 'varchar',
                    'backend' => '',
                    'frontend' => 'Magento\Catalog\Model\Product\Attribute\Frontend\Image',
                    'label' => 'AdRoll Image',
                    'input' => 'media_image',
                    'class' => '',
                    'source' => '',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'default' => null,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'unique' => false,
                    'apply_to' => ''
                ]
            );
        }

        $setup->endSetup();
    }
}
