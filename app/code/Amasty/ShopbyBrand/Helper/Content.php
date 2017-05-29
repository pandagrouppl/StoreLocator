<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\ShopbyBrand\Helper;

use Amasty\Shopby\Api\CategoryDataSetterInterface;
use Amasty\Shopby\Api\Data\OptionSettingInterface;
use Amasty\Shopby\Helper\OptionSetting;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Layer;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManager;
use Amasty\Shopby\Model\Category\Manager as CategoryManager;

class Content extends AbstractHelper implements CategoryDataSetterInterface
{
    /** @var  Layer */
    protected $_layer;

    /** @var  OptionSetting */
    protected $_optionHelper;

    /** @var  StoreManager */
    protected $_storeManager;

    /**
     * @param Context $context
     * @param Layer\Resolver $layerResolver
     * @param OptionSetting $optionHelper
     * @param StoreManager $storeManager
     */
    public function __construct(
        Context $context,
        Layer\Resolver $layerResolver,
        OptionSetting $optionHelper,
        StoreManager $storeManager
    ) {
        parent::__construct($context);
        $this->_layer = $layerResolver->get();
        $this->_optionHelper = $optionHelper;
        $this->_storeManager = $storeManager;
    }

    /**
     * Set Category data from current Brand.
     * @param Category $category
     * @return $this;
     */
    public function setCategoryData(Category $category)
    {
        $brand = $this->getCurrentBranding();
        if ($brand) {
            $this->_populateCategoryWithBrand($category, $brand);
        }
        return $this;
    }

    /**
     * Get current Brand.
     * @return null|OptionSettingInterface
     */
    public function getCurrentBranding()
    {
        $attributeCode = $this->scopeConfig->getValue('amshopby_brand/general/attribute_code', ScopeInterface::SCOPE_STORE);
        if ($attributeCode == '') {
            return null;
        }

        $value = $this->_getRequest()->getParam($attributeCode);
        if (!isset($value)) {
            return null;
        }

        $isRootCategory =
            $this->_layer->getCurrentCategory()->getId() == $this->_layer->getCurrentStore()->getRootCategoryId();
        if (!$isRootCategory) {
            return null;
        }

        $setting = $this->_optionHelper->getSettingByValue(
            $value,
            \Amasty\Shopby\Helper\FilterSetting::ATTR_PREFIX . $attributeCode,
            $this->_storeManager->getStore()->getId());

        return $setting;
    }

    /**
     * Populate category with Brand data.
     * @param Category $category
     * @param OptionSettingInterface $brand
     * @return $this
     */
    protected function _populateCategoryWithBrand(Category $category, OptionSettingInterface $brand)
    {
        $category->setName($brand->getTitle());
        $category->setData('description', $brand->getDescription());
        $category->setData('landing_page', $brand->getTopCmsBlockId());
        if ($brand->getTopCmsBlockId()) {
            $category->setData(CategoryManager::CATEGORY_FORCE_MIXED_MODE, 1);
        }
        $category->setData(CategoryManager::CATEGORY_SHOPBY_IMAGE_URL, $brand->getImageUrl());

        $category->setData('meta_title', $brand->getMetaTitle());
        $category->setData('meta_description', $brand->getMetaDescription());
        $category->setData('meta_keywords', $brand->getMetaKeywords());
        $category->setData(CategoryDataSetterInterface::APPLIED_BRAND_VALUE, $brand->getValue());
        return $this;
    }
}
