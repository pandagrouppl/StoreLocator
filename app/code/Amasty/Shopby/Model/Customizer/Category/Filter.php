<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\Shopby\Model\Customizer\Category;

use Amasty\Shopby\Api\CategoryDataSetterInterface;
use Magento\Catalog\Model\Category;

class Filter implements CustomizerInterface
{
    /** @var CustomizerInterface */
    protected $_contentHelper;

    /**
     * @param CategoryDataSetterInterface $contentHelper
     */
    public function __construct(CategoryDataSetterInterface $contentHelper)
    {
        $this->_contentHelper = $contentHelper;
    }

    /**
     * @param Category $category
     * @return $this
     */
    public function prepareData(Category $category)
    {
        $this->_contentHelper->setCategoryData($category);
        return $this;
    }
}