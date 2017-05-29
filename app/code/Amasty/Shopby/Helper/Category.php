<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\Shopby\Helper;

use Amasty\Shopby\Model\Source\RenderCategoriesLevel;

class Category extends \Magento\Framework\App\Helper\AbstractHelper
{
    const ATTRIBUTE_CODE = 'category_ids';
    protected $setting;
    protected $categoryManager;
    protected $layer;
    protected $categoryRepository;

    protected $startCategory;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Amasty\Shopby\Helper\FilterSetting $settingHelper,
        \Amasty\Shopby\Model\Category\Manager\Proxy $categoryManager,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
    ) {
        parent::__construct($context);
        $this->setting = $settingHelper->getSettingByAttributeCode(self::ATTRIBUTE_CODE);
        $this->categoryManager = $categoryManager;
        $this->layer = $layerResolver->get();
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return mixed
     */
    public function getStartCategory()
    {
        if(is_null($this->startCategory)) {
            $this->init();
        }

        return $this->startCategory;
    }

    /**
     * @return bool
     */
    public function isCategoryFilterExtended()
    {
        return $this->setting->getCategoryTreeDepth() > 1;
    }

    protected function init()
    {
        if($this->setting->getRenderCategoriesLevel() == RenderCategoriesLevel::ROOT_CATEGORY) {
            $category = $this->categoryRepository->get($this->categoryManager->getRootCategoryId(), $this->categoryManager->getCurrentStoreId());
        } elseif($this->setting->getRenderCategoriesLevel() == RenderCategoriesLevel::CURRENT_CATEGORY_LEVEL) {
            if($this->layer->getCurrentCategory()->getId() == $this->categoryManager->getRootCategoryId()) {
                $category = $this->layer->getCurrentCategory();
            } else {
                $categoryId = $this->layer->getCurrentCategory()->getParentId();
                $category = $this->categoryRepository->get($categoryId, $this->categoryManager->getCurrentStoreId());
            }
        } else { //  RenderCategoriesLevel::CURRENT_CATEGORY_CHILDREN
            $category = $this->layer->getCurrentCategory();
        }

        $this->startCategory = $category;
    }
}
