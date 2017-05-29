<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\ShopbyBrand\Plugin\Block\Html;

use \Amasty\Shopby\Helper\FilterSetting;
use Magento\Store\Model\ScopeInterface;

class Title
{
    /** @var \Magento\Framework\Registry  */
    protected $_registry;

    /** @var \Magento\Store\Model\StoreManagerInterface  */
    protected $_storeManager;

    /** @var \Amasty\Shopby\Helper\OptionSetting  */
    protected $_optionSettingHelper;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface  */
    protected $_scopeConfig;

    /** @var \Magento\Framework\View\Element\BlockFactory  */
    protected $_blockFactory;

    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Element\BlockFactory $blockFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Amasty\Shopby\Helper\OptionSetting $optionSetting,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_registry = $registry;
        $this->_storeManager = $storeManager;
        $this->_optionSettingHelper = $optionSetting;
        $this->_scopeConfig = $scopeConfig;
        $this->_blockFactory = $blockFactory;
    }

    /**
     * Add Brand Label to Product Page
     *
     * @param \Magento\Theme\Block\Html\Title $original
     * @param $html
     * @return mixed
     */
    public function afterToHtml(
        \Magento\Theme\Block\Html\Title $original,
        $html
    ) {
        if (!$this->_scopeConfig->getValue('amshopby_brand/general/product_icon', ScopeInterface::SCOPE_STORE)) {
            return $html;
        }
        /** @var \Magento\Catalog\Model\Product */
        $product = $this->_registry->registry('current_product');
        if ($product instanceof \Magento\Catalog\Model\Product) {
            if ($product->getId()) {
                $brandCode = $this->_scopeConfig->getValue('amshopby_brand/general/attribute_code', ScopeInterface::SCOPE_STORE);
                $attribute = $product->getResource()->getAttribute($brandCode);
                $storeId = $this->_storeManager->getStore()->getId();
                $optionId = (int) $product->getResource()->getAttributeRawValue($product->getId(), $attribute, $storeId);
                $filterCode = FilterSetting::ATTR_PREFIX . $brandCode;
                $setting = $this->_optionSettingHelper->getSettingByValue($optionId, $filterCode, $storeId);
                if ($setting && $setting->getId() && $setting->getImageUrl()) {
                    $block = $this->_blockFactory->createBlock('\Magento\Framework\View\Element\Template')
                        ->setData('setting', $setting)
                        ->setTemplate('Amasty_ShopbyBrand::brand_icon.phtml');

                    $count = 1;
                    $html = str_replace('/h1>', '/h1>' . $block->toHtml(), $html, $count);
                }
            }
        }
        return $html;
    }
}
