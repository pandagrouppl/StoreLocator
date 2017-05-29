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


class SubcategoriesView implements \Magento\Framework\Option\ArrayInterface
{
    const FOLDING = 1;
    const FLY_OUT = 2;
    /**
     * Return array of options as value-label pairs
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::FOLDING,
                'label' => __('Folding')
            ],
            [
                'value' => self::FLY_OUT,
                'label' => __('Fly-out')
            ],
        ];
    }
}
