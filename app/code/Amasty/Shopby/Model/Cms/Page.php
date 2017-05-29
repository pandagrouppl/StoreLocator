<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Model\Cms;


class Page extends \Magento\Framework\Model\AbstractModel
{
    const VAR_SETTINGS = 'amshopby_settings';

    protected function _construct()
    {
        $this->_init('Amasty\Shopby\Model\ResourceModel\Cms\Page');
    }
}