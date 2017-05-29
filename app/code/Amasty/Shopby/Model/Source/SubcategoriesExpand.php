<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\Shopby\Model\Source;


class SubcategoriesExpand implements \Magento\Framework\Option\ArrayInterface
{
    const ALWAYS = 1;
    const BY_CLICK = 2;
    /**
     * Return array of options as value-label pairs
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::ALWAYS,
                'label' => __('Always')
            ],
            [
                'value' => self::BY_CLICK,
                'label' => __('By Click')
            ],
        ];
    }
}
