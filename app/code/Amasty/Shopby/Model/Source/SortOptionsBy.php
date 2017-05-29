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


class SortOptionsBy implements \Magento\Framework\Option\ArrayInterface
{
    const POSITION = 0;
    const NAME = 1;

    public function toOptionArray()
    {
        return [
            [
                'value' => self::POSITION,
                'label' => __('Position')
            ],
            [
                'value' => self::NAME,
                'label' => __('Name')
            ],
        ];
    }
}
