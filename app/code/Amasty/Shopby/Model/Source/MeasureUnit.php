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


class MeasureUnit implements \Magento\Framework\Option\ArrayInterface
{
    const CUSTOM            = 0;
    const CURRENCY_SYMBOL   = 1;

    public function toOptionArray()
    {
        return [
            [
                'value' => self::CURRENCY_SYMBOL,
                'label' => __('Store Currency')
            ],
            [
                'value' => self::CUSTOM,
                'label' => __('Custom label')
            ]
        ];
    }


}
