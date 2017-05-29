<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\ShopbyBrand\Setup;


use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use \Magento\Cms\Model\PageFactory;
use Magento\Framework\App\Config\Storage\WriterInterface;

class UpgradeData implements UpgradeDataInterface
{
    /** @var PageFactory */
    protected $_pageFactory;

    /** @var WriterInterface  */
    protected $_configWriter;

    /**
     * UpgradeData constructor.
     * @param PageFactory $pageFactory
     * @param WriterInterface $configWriter
     */
    public function __construct(
        \Magento\Cms\Model\PageFactory $pageFactory,
        WriterInterface $configWriter
    ) {
        $this->_pageFactory = $pageFactory;
        $this->_configWriter = $configWriter;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $this->createAllBrandsPage();
        }

        $setup->endSetup();
    }

    protected function createAllBrandsPage()
    {
        $identifier = $this->getIdentifier();
        $content = '<p style="text-align: left;"><span style="font-size: small;"><strong>Searching for a favorite brand? Browse the list below to find just the label you\'re looking for!</strong></span></p>
<p style="text-align: left;"><span style="font-size: medium;"><strong><br /></strong></span></p>
<p><img src="{{media url="wysiwyg/collection/collection-performance.jpg"}}" alt="" /></p>
<p>{{widget type="Amasty\ShopbyBrand\Block\Widget\BrandSlider" template="widget/brand_list/slider.phtml"}}</p>
<p>{{widget type="Amasty\ShopbyBrand\Block\Widget\BrandList" columns="3" template="widget/brand_list/index.phtml"}}</p>';
        $page = $this->_pageFactory->create();
        $page->setTitle('All Brands Page')
            ->setIdentifier($identifier)
            ->setData('mageworx_hreflang_identifier', 'en-us')
            ->setIsActive(false)
            ->setPageLayout('1column')
            ->setStores(array(0))
            ->setContent($content)
            ->save();
        $this->_configWriter->save('amshopby_brand/general/brands_page', $identifier);
    }

    protected function getIdentifier($index = 0)
    {
        $identifier = 'brands';
        if ($index) {
            $identifier .= '_' . $index;
        }
        $page = $this->_pageFactory->create()->load($identifier);
        if ($page->getId()) {
            return $this->getIdentifier(++$index);
        }
        return $identifier;
    }
}
