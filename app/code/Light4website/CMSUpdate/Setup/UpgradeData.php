<?php

namespace Light4website\CMSUpdate\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;


/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var \Magento\Cms\Model\PageFactory
     */
    protected $_pageFactory;

    /**
     * @var \Magento\Cms\Model\BlockFactory
     */
    protected $_blockFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    protected $upgradeData_1_1;
    protected $upgradeData_1_2;
    protected $upgradeData_1_3;
    protected $upgradeData_1_4;

    /**
     * Construct
     *
     * @param \Magento\Cms\Model\PageFactory $pageFactory
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     */
    public function __construct(
        \Magento\Cms\Model\PageFactory $pageFactory,
        \Magento\Cms\Model\BlockFactory $blockFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Light4website\CMSUpdate\Setup\Upgrade\Version_1_1\UpgradeData $upgradeData_1_1,
        \Light4website\CMSUpdate\Setup\Upgrade\Version_1_2\UpgradeData $upgradeData_1_2,
        \Light4website\CMSUpdate\Setup\Upgrade\Version_1_3\UpgradeData $upgradeData_1_3,
        \Light4website\CMSUpdate\Setup\Upgrade\Version_1_4\UpgradeData $upgradeData_1_4
    ) {
        $this->_pageFactory = $pageFactory;
        $this->_blockFactory = $blockFactory;
        $this->_storeManager = $storeManager;
        $this->upgradeData_1_1 = $upgradeData_1_1;
        $this->upgradeData_1_2 = $upgradeData_1_2;
        $this->upgradeData_1_3 = $upgradeData_1_3;
        $this->upgradeData_1_4 = $upgradeData_1_4;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
//        $setup->startSetup();


        if (version_compare($context->getVersion(), '1.1') < 0)
        {
            $this->upgradeData_1_1->upgrade($setup, $context);
        }

        if (version_compare($context->getVersion(), '1.2') < 0)
        {
            $this->upgradeData_1_2->upgrade($setup, $context);
        }

        if (version_compare($context->getVersion(), '1.3') < 0)
        {
            $this->upgradeData_1_3->upgrade($setup, $context);
        }

        if (version_compare($context->getVersion(), '1.4') < 0)
        {
            $this->upgradeData_1_4->upgrade($setup, $context);
        }

//        $setup->endSetup();

    }
}