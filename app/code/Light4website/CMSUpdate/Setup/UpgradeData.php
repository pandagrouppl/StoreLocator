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
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $page = $this->_pageFactory->create();
        $content = file_get_contents('pages/about_us.phtml', FILE_USE_INCLUDE_PATH);

        $pageExists = $page->getCollection()->addFilter('identifier', 'about-us')->getFirstItem();

        if (false == $pageExists) {
            $page->setTitle('About Us')
                ->setIdentifier('about-us')
                ->setIsActive(true)
                ->setPageLayout('1column-unconstrained-width')
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        } else {
            $pageExists->setContent($content)->save();
        }

        $setup->endSetup();


        $setup->startSetup();

        $page = $this->_pageFactory->create();
        $content = file_get_contents('pages/contact_us.phtml', FILE_USE_INCLUDE_PATH);
        $pageExists = $page->getCollection()->addFilter('identifier', 'contact_us')->getFirstItem();
        if (false == $pageExists) {
            $page->setTitle('Contact Us')
                ->setIdentifier('contact_us')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setLayoutUpdateXml(
                    <<<EOT
<referenceContainer name="content">
<referenceBlock name="page.main.title">
    <action method="setPageTitle">
        <argument translate="true" name="title" xsi:type="string">Contact Us</argument>
    </action>
</referenceBlock>
</referenceContainer>
<move element="page.main.title" destination="page.top" before="breadcrumbs"/>
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

        $page = $this->_pageFactory->create();
        $content = file_get_contents('pages/look-book.phtml', FILE_USE_INCLUDE_PATH);
        $pageExists = $page->getCollection()->addFilter('identifier', 'look-book')->getFirstItem();
        if (false == $pageExists) {
            $page->setTitle('Look Book')
                ->setIdentifier('look-book')
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

        $page = $this->_pageFactory->create();
        $content = file_get_contents('pages/made-to-measure.phtml', FILE_USE_INCLUDE_PATH);
        $pageExists = $page->getCollection()->addFilter('identifier', 'made-to-measure')->getFirstItem();
        if (false == $pageExists) {
            $page->setTitle('Made To Measure')
                ->setIdentifier('made-to-measure')
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

        $page = $this->_pageFactory->create();
        $content = file_get_contents('pages/our-design.phtml', FILE_USE_INCLUDE_PATH);
        $pageExists = $page->getCollection()->addFilter('identifier', 'our-design')->getFirstItem();
        if (false == $pageExists) {
            $page->setTitle('Our Design')
                ->setIdentifier('our-design')
                ->setIsActive(true)
                ->setPageLayout('1column-unconstrained-width')
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        } else {
            $pageExists->setContent($content)->save();
        }

        $setup->endSetup();


        $setup->startSetup();

        $page = $this->_pageFactory->create();
        $content = file_get_contents('pages/our-labels.phtml', FILE_USE_INCLUDE_PATH);
        $pageExists = $page->getCollection()->addFilter('identifier', 'our-labels')->getFirstItem();
        if (false == $pageExists) {
            $page->setTitle('Our Labels')
                ->setIdentifier('our-labels')
                ->setIsActive(true)
                ->setPageLayout('1column-unconstrained-width')
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        } else {
            $pageExists->setContent($content)->save();
        }

        $setup->endSetup();


        $setup->startSetup();

        $page = $this->_pageFactory->create();
        $content = file_get_contents('pages/our-mills.phtml', FILE_USE_INCLUDE_PATH);
        $pageExists = $page->getCollection()->addFilter('identifier', 'our-mills')->getFirstItem();
        if (false == $pageExists) {
            $page->setTitle('Our Mills')
                ->setIdentifier('our-mills')
                ->setIsActive(true)
                ->setPageLayout('1column-unconstrained-width')
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        } else {
            $pageExists->setContent($content)->save();
        }

        $setup->endSetup();


        $setup->startSetup();

        $page = $this->_pageFactory->create();
        $content = file_get_contents('pages/shipping-returns.phtml', FILE_USE_INCLUDE_PATH);
        $pageExists = $page->getCollection()->addFilter('identifier', 'shipping-returns')->getFirstItem();
        if (false == $pageExists) {
            $page->setTitle('Shipping returns')
                ->setIdentifier('shipping-returns')
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

        $page = $this->_pageFactory->create();
        $content = file_get_contents('pages/size-chart.phtml', FILE_USE_INCLUDE_PATH);
        $pageExists = $page->getCollection()->addFilter('identifier', 'size-chart')->getFirstItem();
        if (false == $pageExists) {
            $page->setTitle('Size chart')
                ->setIdentifier('size-chart')
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

        $page = $this->_pageFactory->create();
        $content = file_get_contents('pages/sustainability.phtml', FILE_USE_INCLUDE_PATH);
        $pageExists = $page->getCollection()->addFilter('identifier', 'sustainability')->getFirstItem();
        if (false == $pageExists) {
            $page->setTitle('Sustainability')
                ->setIdentifier('sustainability')
                ->setIsActive(true)
                ->setPageLayout('1column-unconstrained-width')
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        } else {
            $pageExists->setContent($content)->save();
        }

        $setup->endSetup();



        $setup->startSetup();

        $page = $this->_pageFactory->create();
        $content = file_get_contents('pages/terms.phtml', FILE_USE_INCLUDE_PATH);
        $layoutContent = file_get_contents('pages/terms.xml', FILE_USE_INCLUDE_PATH);
        $pageExists = $page->getCollection()->addFilter('identifier', 'terms')->getFirstItem();
        if (false == $pageExists) {
            $page->setTitle('Terms')
                ->setIdentifier('terms')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setLayoutUpdateXml($layoutContent)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        } else {
            $pageExists->setContent($content)->save();
        }

        $setup->endSetup();


        $setup->startSetup();

        $block = $this->_blockFactory->create();
        $content = file_get_contents('blocks/menublock.phtml', FILE_USE_INCLUDE_PATH);

        $blockExists = $block->getCollection()->addFilter('identifier', 'menublock')->getFirstItem();
        if (false == $blockExists) {

            $block->setTitle('menublock')
                ->setIdentifier('menublock')
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
        $content = file_get_contents('blocks/about-us-menu.phtml', FILE_USE_INCLUDE_PATH);

        $blockExists = $block->getCollection()->addFilter('identifier', 'about-us-menu')->getFirstItem();
        if (false == $blockExists) {

            $block->setTitle('about-us-menu')
                ->setIdentifier('about-us-menu')
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
        $content = file_get_contents('blocks/blog-header.phtml', FILE_USE_INCLUDE_PATH);

        $blockExists = $block->getCollection()->addFilter('identifier', 'blog-header')->getFirstItem();
        if (false == $blockExists) {

            $block->setTitle('blog-header')
                ->setIdentifier('blog-header')
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
        $content = file_get_contents('blocks/contact-us.phtml', FILE_USE_INCLUDE_PATH);

        $blockExists = $block->getCollection()->addFilter('identifier', 'contact-us')->getFirstItem();
        if (false == $blockExists) {

            $block->setTitle('contact-us')
                ->setIdentifier('contact-us')
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
        $content = file_get_contents('blocks/faq.phtml', FILE_USE_INCLUDE_PATH);

        $blockExists = $block->getCollection()->addFilter('identifier', 'faq')->getFirstItem();
        if (false == $blockExists) {

            $block->setTitle('faq')
                ->setIdentifier('faq')
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
        $content = file_get_contents('blocks/footer-links.phtml', FILE_USE_INCLUDE_PATH);

        $blockExists = $block->getCollection()->addFilter('identifier', 'footer-links')->getFirstItem();
        if (false == $blockExists) {

            $block->setTitle('footer-links')
                ->setIdentifier('footer-links')
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
        $content = file_get_contents('blocks/menublock-accessories.phtml', FILE_USE_INCLUDE_PATH);

        $blockExists = $block->getCollection()->addFilter('identifier', 'menublock-accessories')->getFirstItem();
        if (false == $blockExists) {

            $block->setTitle('menublock-accessories')
                ->setIdentifier('menublock-accessories')
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
        $content = file_get_contents('blocks/menublock-clothing.phtml', FILE_USE_INCLUDE_PATH);

        $blockExists = $block->getCollection()->addFilter('identifier', 'menublock-clothing')->getFirstItem();
        if (false == $blockExists) {

            $block->setTitle('menublock-clothing')
                ->setIdentifier('menublock-clothing')
                ->setIsActive(true)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        } else {
            $blockExists->setContent($content)->save();
        }

        $setup->endSetup();

//
        $setup->startSetup();

        $block = $this->_blockFactory->create();
        $content = file_get_contents('blocks/menublock-suits.phtml', FILE_USE_INCLUDE_PATH);

        $blockExists = $block->getCollection()->addFilter('identifier', 'menublock-suit')->getFirstItem();

        if (false == $blockExists) {

            $block->setTitle('menublock-suit')
                ->setIdentifier('menublock-suit')
                ->setIsActive(true)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        } else {
            $blockExists->setContent($content)->save();
        }

        $setup->endSetup();

//
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
