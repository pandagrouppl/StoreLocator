<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\ShopbySeo\Model\Source\Canonical;

class Root implements \Magento\Framework\Option\ArrayInterface
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
            \Amasty\ShopbySeo\Model\Customizer\Category\Seo::ROOT_CURRENT => __('Keep current URL'),
            \Amasty\ShopbySeo\Model\Customizer\Category\Seo::ROOT_PURE => __('URL Key Only'),
            \Amasty\ShopbySeo\Model\Customizer\Category\Seo::ROOT_FIRST_ATTRIBUTE => __('First Attribute Value'),
            \Amasty\ShopbySeo\Model\Customizer\Category\Seo::ROOT_CUT_OFF_GET => __('Current URL without Get parameters')
        ];
    }
}