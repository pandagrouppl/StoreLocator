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
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        echo "\nStarting instalation/updating pages and blocks...\n";

        $setup->startSetup();
        $page = $this->_pageFactory->create()->load('about-us');
        $content = file_get_contents('pages/about-us.phtml', FILE_USE_INCLUDE_PATH);
        $layoutContent = file_get_contents('pages/brownlow-skit.xml', FILE_USE_INCLUDE_PATH);
        $page->setTitle('About Us')
            ->setIdentifier('about-us')
            ->setIsActive(true)
            ->setPageLayout('1column-unconstrained-width')
            ->setLayoutUpdateXml($layoutContent)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Page about-us was installed/updated\n";
        $setup->endSetup();

        $setup->startSetup();
        $page = $this->_pageFactory->create()->load('brownlow-skit');
        $content = file_get_contents('pages/brownlow-skit.phtml', FILE_USE_INCLUDE_PATH);
        $layoutContent = file_get_contents('pages/brownlow-skit.xml', FILE_USE_INCLUDE_PATH);
        $page->setTitle('Brownlow Skit')
            ->setIdentifier('brownlow-skit')
            ->setIsActive(true)
            ->setPageLayout('1column-unconstrained-width')
            ->setLayoutUpdateXml($layoutContent)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Page brownlow-skit was installed/updated\n";
        $setup->endSetup();

        $setup->startSetup();
        $page = $this->_pageFactory->create()->load('look-book');
        $content = file_get_contents('pages/look-book.phtml', FILE_USE_INCLUDE_PATH);
        $layoutContent = file_get_contents('pages/look-book.xml', FILE_USE_INCLUDE_PATH);
            $page->setTitle('Look Book')
                ->setIdentifier('look-book')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setLayoutUpdateXml($layoutContent)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
        echo "Page look-book was installed/updated\n";
        $setup->endSetup();


        $setup->startSetup();
        $page = $this->_pageFactory->create()->load('made-to-measure');
        $content = file_get_contents('pages/made-to-measure.phtml', FILE_USE_INCLUDE_PATH);
        $layoutContent = file_get_contents('pages/made-to-measure.xml', FILE_USE_INCLUDE_PATH);
        $page->setTitle('Made To Measure')
            ->setIdentifier('made-to-measure')
            ->setIsActive(true)
            ->setPageLayout('1column-unconstrained-width')
            ->setLayoutUpdateXml($layoutContent)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Page m2m was installed/updated\n";
        $setup->endSetup();

        $setup->startSetup();

        $page = $this->_pageFactory->create()->load('our-mills');
        $content = file_get_contents('pages/our-mills.phtml', FILE_USE_INCLUDE_PATH);
        $layoutContent = file_get_contents('pages/our-mills.xml', FILE_USE_INCLUDE_PATH);
        $page->setTitle('Our Mills')
            ->setIdentifier('our-mills')
            ->setIsActive(true)
            ->setPageLayout('1column-unconstrained-width')
            ->setLayoutUpdateXml($layoutContent)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
            echo "Page our-mills was installed/updated\n";
        $setup->endSetup();

        $setup->startSetup();

        $page = $this->_pageFactory->create()->load('schedule-your-appointment');
        $content = file_get_contents('pages/schedule-your-appointment.phtml', FILE_USE_INCLUDE_PATH);
        $layoutContent = file_get_contents('pages/schedule-your-appointment.xml', FILE_USE_INCLUDE_PATH);
        $page->setTitle('Custom Tailored Made Suit Appointments')
            ->setIdentifier('schedule-your-appointment')
            ->setIsActive(true)
            ->setPageLayout('1column-unconstrained-width')
            ->setLayoutUpdateXml($layoutContent)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Page our-schedule-your-appointment was installed/updated\n";
        $setup->endSetup();


        $setup->startSetup();
        $page = $this->_pageFactory->create()->load('shipping-returns');
        $content = file_get_contents('pages/shipping-returns.phtml', FILE_USE_INCLUDE_PATH);
        $layoutContent = file_get_contents('pages/shipping-returns.xml', FILE_USE_INCLUDE_PATH);
        $page->setTitle('Shipping Returns')
            ->setIdentifier('shipping-returns')
            ->setIsActive(true)
            ->setPageLayout('1column')
            ->setLayoutUpdateXml($layoutContent)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Page shipping-returns was installed/updated\n";
        $setup->endSetup();


        $setup->startSetup();
        $page = $this->_pageFactory->create()->load('size-chart');
        $content = file_get_contents('pages/size-chart.phtml', FILE_USE_INCLUDE_PATH);
        $layoutContent = file_get_contents('pages/size-chart.xml', FILE_USE_INCLUDE_PATH);
        $page->setTitle('Size Guide')
            ->setIdentifier('size-chart')
            ->setIsActive(true)
            ->setPageLayout('1column')
            ->setLayoutUpdateXml($layoutContent)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Page size-chart was installed/updated\n";
        $setup->endSetup();


        $setup->startSetup();
        $page = $this->_pageFactory->create()->load('suit-fit-guide');
        $content = file_get_contents('pages/suit-fit-guide.phtml', FILE_USE_INCLUDE_PATH);
        $layoutContent = file_get_contents('pages/suit-fit-guide.xml', FILE_USE_INCLUDE_PATH);
        $page->setTitle('Suit fit Guide')
            ->setIdentifier('suit-fit-guide')
            ->setIsActive(true)
            ->setPageLayout('1column-unconstrained-width')
            ->setLayoutUpdateXml($layoutContent)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Page suit-fit-guide was installed/updated\n";
        $setup->endSetup();


        $setup->startSetup();
        $page = $this->_pageFactory->create()->load('sustainability');
        $content = file_get_contents('pages/sustainability.phtml', FILE_USE_INCLUDE_PATH);
        $layoutContent = file_get_contents('pages/sustainability.xml', FILE_USE_INCLUDE_PATH);
        $page->setTitle('Sustainability')
            ->setIdentifier('sustainability')
            ->setIsActive(true)
            ->setPageLayout('1column-unconstrained-width')
            ->setLayoutUpdateXml($layoutContent)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Page sustainability was installed/updated\n";
        $setup->endSetup();


        $setup->startSetup();
        $page = $this->_pageFactory->create()->load('terms');
        $content = file_get_contents('pages/terms.phtml', FILE_USE_INCLUDE_PATH);
        $layoutContent = file_get_contents('pages/terms.xml', FILE_USE_INCLUDE_PATH);
            $page->setTitle('Terms')
                ->setIdentifier('terms')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setLayoutUpdateXml($layoutContent)
                ->setStores(array(0))
                ->setContent($content)
                ->save();
            echo "Page terms was installed/updated\n";
        $setup->endSetup();

        $setup->startSetup();
        $block = $this->_blockFactory->create()->load('deal-chinos');
        $content = file_get_contents('blocks/deal-chinos.phtml', FILE_USE_INCLUDE_PATH);
        $block->setTitle('Deal - chinos')
            ->setIdentifier('deal-chinos')
            ->setIsActive(true)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Block deal-chinos was installed/updated\n";
        $setup->endSetup();

        $setup->startSetup();
        $block = $this->_blockFactory->create()->load('deal-shirts');
        $content = file_get_contents('blocks/deal-shirts.phtml', FILE_USE_INCLUDE_PATH);
        $block->setTitle('Deal - Shirts')
            ->setIdentifier('deal-shirts')
            ->setIsActive(true)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Block deal-shirts was installed/updated\n";
        $setup->endSetup();

        $setup->startSetup();
        $block = $this->_blockFactory->create()->load('deal-suits');
        $content = file_get_contents('blocks/deal-suits.phtml', FILE_USE_INCLUDE_PATH);
        $block->setTitle('Deal - suits')
            ->setIdentifier('deal-suits')
            ->setIsActive(true)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Block deal-suits was installed/updated\n";
        $setup->endSetup();

        $setup->startSetup();
        $block = $this->_blockFactory->create()->load('deal-ties');
        $content = file_get_contents('blocks/deal-ties.phtml', FILE_USE_INCLUDE_PATH);
        $block->setTitle('Deal - ties')
            ->setIdentifier('deal-ties')
            ->setIsActive(true)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Block deal-ties was installed/updated\n";
        $setup->endSetup();



        $setup->startSetup();
        $block = $this->_blockFactory->create()->load('menublock');
        $content = file_get_contents('blocks/menublock.phtml', FILE_USE_INCLUDE_PATH);
        $block->setTitle('Menu Block')
            ->setIdentifier('menublock')
            ->setIsActive(true)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Block menublock was installed/updated\n";
        $setup->endSetup();


        $setup->startSetup();
        $block = $this->_blockFactory->create()->load('about-us-menu');
        $content = file_get_contents('blocks/about-us-menu.phtml', FILE_USE_INCLUDE_PATH);
        $block->setTitle('About Us Menu')
            ->setIdentifier('about-us-menu')
            ->setIsActive(true)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Block about-us-menu was installed/updated\n";
        $setup->endSetup();


        $setup->startSetup();
        $block = $this->_blockFactory->create()->load('blog-header');
        $content = file_get_contents('blocks/blog-header.phtml', FILE_USE_INCLUDE_PATH);
        $block->setTitle('Blog Header')
            ->setIdentifier('blog-header')
            ->setIsActive(true)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Block blog-header was installed/updated\n";
        $setup->endSetup();



        $setup->startSetup();
        $block = $this->_blockFactory->create()->load('faq');
        $content = file_get_contents('blocks/faq.phtml', FILE_USE_INCLUDE_PATH);
        $block->setTitle('Faq')
            ->setIdentifier('faq')
            ->setIsActive(true)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Block faq was installed/updated\n";
        $setup->endSetup();


        $setup->startSetup();
        $block = $this->_blockFactory->create()->load('footer-links');
        $content = file_get_contents('blocks/footer-links.phtml', FILE_USE_INCLUDE_PATH);
        $block->setTitle('Footer Links')
            ->setIdentifier('footer-links')
            ->setIsActive(true)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Block footer-links was installed/updated\n";
        $setup->endSetup();


        $setup->startSetup();
        $block = $this->_blockFactory->create()->load('menublock-accessories');
        $content = file_get_contents('blocks/menublock-accessories.phtml', FILE_USE_INCLUDE_PATH);
        $block->setTitle('Menu Block Accessories')
            ->setIdentifier('menublock-accessories')
            ->setIsActive(true)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Block menublock-accessories was installed/updated\n";
        $setup->endSetup();


        $setup->startSetup();
        $block = $this->_blockFactory->create()->load('menublock-clothing');
        $content = file_get_contents('blocks/menublock-clothing.phtml', FILE_USE_INCLUDE_PATH);
        $block->setTitle('Menu Block Clothing')
            ->setIdentifier('menublock-clothing')
            ->setIsActive(true)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Block menublock-clothing was installed/updated\n";
        $setup->endSetup();

        $setup->startSetup();
        $block = $this->_blockFactory->create()->load('menublock-made-to-measure');
        $content = file_get_contents('blocks/menublock-made-to-measure.phtml', FILE_USE_INCLUDE_PATH);
        $block->setTitle('Menu Block Made to Measure')
            ->setIdentifier('menublock-made-to-measure')
            ->setIsActive(true)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Block menublock-made-to-measure was installed/updated\n";
        $setup->endSetup();

        $setup->startSetup();
        $block = $this->_blockFactory->create()->load('menublock-stores');
        $content = file_get_contents('blocks/menublock-stores.phtml', FILE_USE_INCLUDE_PATH);
        $block->setTitle('Menu Block Stores')
            ->setIdentifier('menublock-stores')
            ->setIsActive(true)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Block menublock-stores was installed/updated\n";
        $setup->endSetup();

        $setup->startSetup();
        $block = $this->_blockFactory->create()->load('menublock-brand');
        $content = file_get_contents('blocks/menublock-brand.phtml', FILE_USE_INCLUDE_PATH);
        $block->setTitle('Menu Block Brand')
            ->setIdentifier('menublock-brand')
            ->setIsActive(true)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Block menublock-brand was installed/updated\n";
        $setup->endSetup();

        $setup->startSetup();
        $block = $this->_blockFactory->create()->load('menublock-suits');
        $content = file_get_contents('blocks/menublock-suits.phtml', FILE_USE_INCLUDE_PATH);
        $block->setTitle('Menu Block Suits')
            ->setIdentifier('menublock-suits')
            ->setIsActive(true)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Block menublock-suits was installed/updated\n";
        $setup->endSetup();


        $setup->startSetup();
        $page = $this->_pageFactory->create()->load('msfw-runway');
        $content = file_get_contents('pages/msfw-runway.phtml', FILE_USE_INCLUDE_PATH);
        $layoutContent = file_get_contents('pages/msfw-runway.xml', FILE_USE_INCLUDE_PATH);
        $page->setTitle('Msfw Runway')
            ->setIdentifier('msfw-runway')
            ->setIsActive(true)
            ->setPageLayout('1column-unconstrained-width')
            ->setLayoutUpdateXml($layoutContent)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Page msfw-runway was installed/updated\n";
        $setup->endSetup();


        $setup->startSetup();
        $block = $this->_blockFactory->create()->load('menublock-editorial');
        $content = file_get_contents('blocks/menublock-editorial.phtml', FILE_USE_INCLUDE_PATH);
        $block->setTitle('Menu Block Editorial')
            ->setIdentifier('menublock-editorial')
            ->setIsActive(true)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Block menublock-editorial was installed/updated\n";
        $setup->endSetup();

        $setup->startSetup();
        $block = $this->_blockFactory->create()->load('shipping-returns-block');
        $content = file_get_contents('blocks/shipping-returns-block.phtml', FILE_USE_INCLUDE_PATH);
        $block->setTitle('Shipping Returns Block')
            ->setIdentifier('shipping-returns-block')
            ->setIsActive(true)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Block shipping-returns-block was installed/updated\n";
        $setup->endSetup();


        $setup->startSetup();
        $block = $this->_blockFactory->create()->load('size-chart');
        $content = file_get_contents('blocks/size-chart.phtml', FILE_USE_INCLUDE_PATH);
        $block->setTitle('Size Chart Block')
            ->setIdentifier('size-chart')
            ->setIsActive(true)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Block size-chart was installed/updated\n";
        $setup->endSetup();

        $setup->startSetup();
        $block = $this->_blockFactory->create()->load('suits-description');
        $content = file_get_contents('blocks/suits-description.phtml', FILE_USE_INCLUDE_PATH);
        $block->setTitle('Suits description')
            ->setIdentifier('suits-description')
            ->setIsActive(true)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Block suits-description was installed/updated\n";
        $setup->endSetup();


        $setup->startSetup();
        $page = $this->_pageFactory->create()->load('404-error');
        $content = file_get_contents('pages/404-error.phtml', FILE_USE_INCLUDE_PATH);
        $page->setTitle('404 Error')
            ->setIdentifier('404-error')
            ->setIsActive(true)
            ->setPageLayout('1column')
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Page 404-error was installed/updated\n";
        $setup->endSetup();


        $setup->startSetup();
        $block = $this->_blockFactory->create()->load('popup-success');
        $content = file_get_contents('blocks/popup-success.phtml', FILE_USE_INCLUDE_PATH);
        $block->setTitle('Popup Success')
            ->setIdentifier('popup-success')
            ->setIsActive(true)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Block popup-success was installed/updated\n";
        $setup->endSetup();

        $setup->startSetup();
        $block = $this->_blockFactory->create()->load('shipping-info-bar');
        $content = file_get_contents('blocks/shipping-info-bar.phtml', FILE_USE_INCLUDE_PATH);
        $block->setTitle('Shipping Info')
            ->setIdentifier('shipping-info-bar')
            ->setIsActive(true)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Block shipping-info-bar was installed/updated\n";
        $setup->endSetup();

        $setup->startSetup();
        $page = $this->_pageFactory->create()->load('home');
        $content = file_get_contents('pages/home.phtml', FILE_USE_INCLUDE_PATH);
        $layoutContent = file_get_contents('pages/home.xml', FILE_USE_INCLUDE_PATH);
        $page->setTitle('Home')
            ->setIdentifier('home')
            ->setIsActive(true)
            ->setPageLayout('1column')
            ->setLayoutUpdateXml($layoutContent)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Page home was installed/updated\n";
        $setup->endSetup();

        $setup->startSetup();
        $page = $this->_pageFactory->create()->load('sartorial-ninja');
        $content = file_get_contents('pages/sartorial-ninja.phtml', FILE_USE_INCLUDE_PATH);
        $layoutContent = file_get_contents('pages/sartorial-ninja.xml', FILE_USE_INCLUDE_PATH);
        $page->setTitle('Sartorial Ninja')
            ->setIdentifier('sartorial-ninja')
            ->setIsActive(true)
            ->setPageLayout('1column')
            ->setLayoutUpdateXml($layoutContent)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Page sartorial ninja was installed/updated\n";
        $setup->endSetup();

        $setup->startSetup();
        $page = $this->_pageFactory->create()->load('the-new-wave');
        $content = file_get_contents('pages/the-new-wave.phtml', FILE_USE_INCLUDE_PATH);
        $layoutContent = file_get_contents('pages/the-new-wave.xml', FILE_USE_INCLUDE_PATH);
        $page->setTitle('The New Wave')
            ->setIdentifier('the-new-wave')
            ->setIsActive(true)
            ->setPageLayout('1column')
            ->setLayoutUpdateXml($layoutContent)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Page the new wave was installed/updated\n";
        $setup->endSetup();

        $setup->startSetup();
        $page = $this->_pageFactory->create()->load('returns-and-exchanges');
        $content = file_get_contents('pages/returns-and-exchanges.phtml', FILE_USE_INCLUDE_PATH);
        $layoutContent = file_get_contents('pages/returns-and-exchanges.xml', FILE_USE_INCLUDE_PATH);
        $page->setTitle('Returns and Exchanges')
            ->setIdentifier('returns-and-exchanges')
            ->setIsActive(true)
            ->setPageLayout('1column')
            ->setLayoutUpdateXml($layoutContent)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Page return and exchanges was installed/updated\n";
        $setup->endSetup();

//        $setup->startSetup();
//        $page = $this->_pageFactory->create()->load('corporate');
//        $content = file_get_contents('pages/corporate.phtml', FILE_USE_INCLUDE_PATH);
//        $layoutContent = file_get_contents('pages/corporate.xml', FILE_USE_INCLUDE_PATH);
//        $page->setTitle('Corporate')
//            ->setIdentifier('corporate')
//            ->setIsActive(true)
//            ->setPageLayout('1column-unconstrained-width')
//            ->setLayoutUpdateXml($layoutContent)
//            ->setStores(array(0))
//            ->setContent($content)
//            ->save();
//        echo "Page corporate was installed/updated\n";
//        $setup->endSetup();

        $setup->startSetup();
        $page = $this->_pageFactory->create()->load('discover-more-page');
        $content = file_get_contents('pages/discover-more-page.phtml', FILE_USE_INCLUDE_PATH);
        $layoutContent = file_get_contents('pages/discover-more-page.xml', FILE_USE_INCLUDE_PATH);
        $page->setTitle('Discover More Page')
            ->setIdentifier('discover-more-page')
            ->setIsActive(true)
            ->setPageLayout('1column')
            ->setLayoutUpdateXml($layoutContent)
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        echo "Page discover more page was installed/updated\n";
        $setup->endSetup();

        echo "Finish instalation/updating pages and blocks";
    }
}
