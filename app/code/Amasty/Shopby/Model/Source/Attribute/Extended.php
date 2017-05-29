<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Model\Source\Attribute;

class Extended extends \Amasty\Shopby\Model\Source\Attribute
{
    const ALL = 'amshopby_all_attributes';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $allOption = [[
            'value' => self::ALL,
            'label' => strval(__('All Attributes'))
        ]];
        return array_merge($allOption, parent::toOptionArray());
    }
}