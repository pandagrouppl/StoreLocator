<?php

namespace Light4website\Attributes\Setup;

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

        if (version_compare($context->getVersion(), "1.0.1", "<")) {
            $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'large_images_template', [
                'type'     => 'int',
                'label'    => 'Use Large Images Template',
                'input'    => 'boolean',
                'source'   => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'visible'  => true,
                'default'  => '0',
                'required' => false,
                'global'   => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group'    => 'Design',
            ]);
        }

        $setup->endSetup();
    }
}
