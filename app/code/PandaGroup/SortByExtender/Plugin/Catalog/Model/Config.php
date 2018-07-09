<?php

namespace PandaGroup\SortByExtender\Plugin\Catalog\Model;

class Config
{
    public function afterGetAttributeUsedForSortByArray(
        \Magento\Catalog\Model\Config $catalogConfig,
        $options
    ) {

        $options['price_low_to_high'] = __('Price - Low To High');
        $options['price_high_to_low'] = __('Price - High To Low');
        return $options;

    }
}
