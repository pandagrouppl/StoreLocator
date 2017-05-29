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


class ShowProductQuantities implements \Magento\Framework\Option\ArrayInterface
{
    const SHOW_DEFAULT = 0;
    const SHOW_YES = 1;
    const SHOW_NO = 2;

    public function toOptionArray()
    {
        return [
            [
                'value' => self::SHOW_DEFAULT,
                'label' => __('Default')
            ],
            [
                'value' => self::SHOW_YES,
                'label' => __('Yes')
            ],
            [
                'value' => self::SHOW_NO,
                'label' => __('No')
            ],
        ];
    }
}
