<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\ShopbyPage\Plugin;

use Magento\Catalog\Helper\Category;
use Amasty\ShopbyPage\Model\Page;

class HelperCategoryPlugin
{
    /** @var \Magento\Catalog\Model\Layer\Resolver  */
    protected $_layerResolver;

    /**
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     */
    public function __construct(
        \Magento\Catalog\Model\Layer\Resolver $layerResolver
    ){
        $this->_layerResolver = $layerResolver;
    }

    /**
     * @return \Magento\Catalog\Model\Category|null
     */
    protected function _getCurrentCategory()
    {
        $catalogLayer = $this->_layerResolver->get();

        if (!$catalogLayer) {
            return null;
        }

        return $catalogLayer->getCurrentCategory();
    }

    /**
     * @param Category $category
     * @param $canUse
     * @return bool
     */
    public function afterCanUseCanonicalTag(Category $category, $canUse)
    {
        $currentCategory = $this->_getCurrentCategory();

        if (!$canUse && $currentCategory !== null) {
            if ($currentCategory->getData(Page::CATEGORY_FORCE_USE_CANONICAL)) {
                $canUse = true;
            }
        }
        return $canUse;
    }
}