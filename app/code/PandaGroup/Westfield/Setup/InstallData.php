<?php

namespace PandaGroup\Westfield\Setup;

class InstallData implements \Magento\Framework\Setup\InstallDataInterface
{
    private $eavSetupFactory;

    public function __construct(\Magento\Eav\Setup\EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function install(
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $setup->startSetup();
        
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'westfield_category',
            [
                'group'                     => 'General Information',
                'input'                     => 'multiselect',
                'type'                      => 'text',
                'label'                     => 'Westfield Categories',
                'visible'                   => true,
                'required'                  => false,
                'wysiwyg_enabled'           => false,
                'visible_on_front'          => true,
                'is_html_allowed_on_front'  => true,
                'global'                    => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'backend'                   => 'PandaGroup\Westfield\Model\Config\Backend\Category',
                'source'                    => 'PandaGroup\Westfield\Model\Config\Source\Category',
                'sort_order'                => 4,

                /*'class' => '',
                'user_defined' => false,
                'default' => 0,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => ''*/
            ]
        );
        
        $setup->endSetup();
    }
}
