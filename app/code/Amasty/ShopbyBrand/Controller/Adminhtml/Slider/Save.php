<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\ShopbyBrand\Controller\Adminhtml\Slider;

/**
 * Class Save
 * @package Amasty\ShopbyBrand\Controller\Adminhtml\Slider
 * @author Evgeni Obukhovsky
 */
class Save extends \Amasty\Shopby\Controller\Adminhtml\Option\Save
{
    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Amasty_ShopbyBrand::slider');
    }

    protected function _redirectRefer()
    {
        $this->_forward('index');
    }
}