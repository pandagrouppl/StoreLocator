<?php

namespace Light4website\CMSUpdate\Setup\Upgrade\Version_1_3;

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

    /**
     * Construct
     *
     * @param \Magento\Cms\Model\PageFactory $pageFactory
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
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
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $block = $this->_blockFactory->create();
        $content = <<<EOT
<section class="header-middle__block header-middle__block--2-column">
<figure><a href="/accessories/bags.html"> <img src="{{media url="wysiwyg/menublock-images/bags.jpg"}}" alt="" /> </a></figure>
<figure><a href="/accessories/ties-1.html"> <img src="{{media url="wysiwyg/menublock-images/Ties_6.jpg"}}" alt="" /> </a></figure>
</section>
EOT;
        $existingBlock = $block->load('menublock-accessories', 'identifier');
        if (!$existingBlock->getId()) {
            $block->setTitle('menublock-accessories')
                ->setIdentifier('menublock-accessories')
                ->setIsActive(true)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        $block = $this->_blockFactory->create();
        $content = <<<EOT
<section class="header-middle__block header-middle__block--3-column">
<figure><a href="/clothing/shirts-1.html"> <img src="{{media url="wysiwyg/menublock-images/Shirts_4.jpg"}}" alt="" /> </a></figure>
<figure><a href="/clothing/sports-jackets-1.html"> <img src="{{media url="wysiwyg/menublock-images/sports_jacket_1.jpg"}}" alt="" /> </a></figure>
<figure><a href="/clothing/coats.html"> <img src="{{media url="wysiwyg//menublock-images/coats.jpg"}}" alt="" /> </a></figure>
</section>
EOT;
        $existingBlock = $block->load('menublock-clothing', 'identifier');
        if (!$existingBlock->getId()) {
            $block->setTitle('menublock-clothing')
                ->setIdentifier('menublock-clothing')
                ->setIsActive(true)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();

        $setup->startSetup();

        $block = $this->_blockFactory->create();
        $content = <<<EOT
<section class="header-middle__block header-middle__block--3-column">
<figure><a href="/mens-suits-1.html"> <img src="{{media url="wysiwyg/menublock-images/two_suits_895_2.jpg"}}" alt="" /> </a></figure>
<figure><a href="/mens-suits-1.html"> <img src="{{media url="wysiwyg/menublock-images/Formal.jpg"}}" alt="" /> </a></figure>
<figure><a href="/mens-suits-1/vests.html"> <img src="{{media url="wysiwyg//menublock-images/two_suits_895_copy_1.jpg"}}" alt="" /> </a></figure>
</section>
EOT;
        $existingBlock = $block->load('menublock-accessories', 'identifier');
        if (!$existingBlock->getId()) {
            $block->setTitle('menublock-accessories')
                ->setIdentifier('menublock-suits')
                ->setIsActive(true)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        }

        $setup->endSetup();
    }
}
