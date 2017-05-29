<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\ShopbyBrand\Ui\Component\Listing\Columns;

/**
 * Class SliderImage
 * @package Amasty\ShopbyBrand\Ui\Component\Listing\Columns
 * @author Evgeni Obukhovsky
 */
class SliderImage extends Image
{
    /**
     * @param \Amasty\Shopby\Model\OptionSetting $brand
     * @return null|string
     */
    protected function getImage(\Amasty\Shopby\Model\OptionSetting $brand)
    {
        return $brand->getSliderImageUrl()
            ? $brand->getSliderImageUrl()
            : $this->_imageHelper->getDefaultPlaceholderUrl();
    }
}