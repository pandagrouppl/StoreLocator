<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\ShopbySeo\Model\Source\Canonical;

class Category implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $options = [];
        foreach ($this->toArray() as $optionValue => $optionLabel) {
            $options[] = [
                'value' => $optionValue,
                'label' => $optionLabel
            ];
        }
        return $options;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            \Amasty\ShopbySeo\Model\Customizer\Category\Seo::CATEGORY_CURRENT => __('Keep current URL'),
            \Amasty\ShopbySeo\Model\Customizer\Category\Seo::CATEGORY_PURE => __('URL Without Filters'),
            \Amasty\ShopbySeo\Model\Customizer\Category\Seo::CATEGORY_BRAND_FILTER => __('Brand Filter Only'),
            \Amasty\ShopbySeo\Model\Customizer\Category\Seo::CATEGORY_FIRST_ATTRIBUTE => __('First Attribute Value'),
            \Amasty\ShopbySeo\Model\Customizer\Category\Seo::CATEGORY_CUT_OFF_GET => __('Current URL without Get parameters')
        ];
    }
}