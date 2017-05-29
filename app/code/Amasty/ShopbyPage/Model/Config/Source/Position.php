<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\ShopbyPage\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use Amasty\ShopbyPage\Model\Page;

class Position  implements ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $optionArray = [];
        $arr = $this->toArray();
        foreach($arr as $value => $label){
            $optionArray[] = [
                'value' => $value,
                'label' => $label
            ];
        }
        return $optionArray;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            Page::POSITION_REPLACE => __('Replace Category\'s Data'),
            Page::POSITION_AFTER => __('After Category\'s Data'),
            Page::POSITION_BEFORE => __('Before Category\'s Data'),
        ];
    }
}
