<?php

namespace PandaGroup\Salesforce\Setup;

class UpgradeData implements \Magento\Framework\Setup\UpgradeDataInterface
{
    /** @var \Magento\Cms\Model\PageFactory  */
    protected $_pageFactory;

    /** @var \Magento\Cms\Model\BlockFactory  */
    protected $_blockFactory;

    /** @var \Magento\Store\Model\StoreManagerInterface  */
    protected $_storeManager;


    /**
     * Construct
     *
     * @param \Magento\Cms\Model\PageFactory $pageFactory
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Cms\Model\PageFactory $pageFactory,
        \Magento\Cms\Model\BlockFactory $blockFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_pageFactory = $pageFactory;
        $this->_blockFactory = $blockFactory;
        $this->_storeManager = $storeManager;
    }

    /**
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     */
    public function upgrade(
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $setup->startSetup();

        $storeId = (int) $this->_storeManager->getStore()->getId();

        $setup->endSetup();
    }
}
