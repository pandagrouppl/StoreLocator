<?php

namespace Light4website\CMSUpdate\Setup\Upgrade\Version_1_4;

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
     * Construct
     *
     * @param \Magento\Cms\Model\PageFactory $pageFactory
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     */
    public function __construct(
        \Magento\Cms\Model\PageFactory $pageFactory,
        \Magento\Cms\Model\BlockFactory $blockFactory
    ) {
        $this->_pageFactory = $pageFactory;
        $this->_blockFactory = $blockFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $page = $this->_pageFactory->create();
        $content = file_get_contents('pages/msfw-runway.phtml', FILE_USE_INCLUDE_PATH);

        $pageExists = $page->getCollection()->addFilter('identifier', 'msfw-runway')->getFirstItem();
        if (false == $pageExists) {
            $page->setTitle('Msfw Runway')
                ->setIdentifier('msfw-runway')
                ->setIsActive(true)
                ->setPageLayout('1column-unconstrained-width')
                ->setLayoutUpdateXml(
                    <<<EOT
<referenceContainer name="page.top">
<referenceBlock name="breadcrumbs" remove="true" />
</referenceContainer>
EOT
                )
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        } else {
            $pageExists->setContent($content)->save();
        }

        $setup->endSetup();



        $setup->startSetup();

        $block = $this->_blockFactory->create();
        $content = file_get_contents('blocks/menublock-editorial.phtml', FILE_USE_INCLUDE_PATH);

        $blockExists = $block->getCollection()->addFilter('identifier', 'menublock-editorial')->getFirstItem();
        if (false == $blockExists) {

            $block->setTitle('menublock-editorial')
                ->setIdentifier('menublock-editorial')
                ->setIsActive(true)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        } else {
            $blockExists->setContent($content)->save();
        }

        $setup->endSetup();



        $setup->startSetup();

        $block = $this->_blockFactory->create();

        $content = file_get_contents('blocks/size-chart.phtml', FILE_USE_INCLUDE_PATH);

        $blockExists = $block->getCollection()->addFilter('identifier', 'size-chart')->getFirstItem();
        if (false == $blockExists) {

            $block->setTitle('size-chart')
                ->setIdentifier('size-chart')
                ->setIsActive(true)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        } else {
            $blockExists->setContent($content)->save();
        }

        $setup->endSetup();



        $setup->startSetup();

        $page = $this->_pageFactory->create();
        $content = file_get_contents('pages/404-error.phtml', FILE_USE_INCLUDE_PATH);

        $pageExists = $page->getCollection()->addFilter('identifier', '404-error')->getFirstItem();
        if (false == $pageExists) {
            $page->setTitle('404')
                ->setIdentifier('404-error')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        } else {
            $pageExists->setContent($content)->save();
        }

        $setup->endSetup();



        $setup->startSetup();

        $block = $this->_blockFactory->create();
        $content = file_get_contents('blocks/popup-success.phtml', FILE_USE_INCLUDE_PATH);

        $blockExists = $block->getCollection()->addFilter('identifier', 'popup-success')->getFirstItem();
        if (false == $blockExists) {

            $block->setTitle('Popup Success')
                ->setIdentifier('popup-success')
                ->setIsActive(true)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        } else {
            $blockExists->setContent($content)->save();
        }

        $setup->endSetup();
    }
}
