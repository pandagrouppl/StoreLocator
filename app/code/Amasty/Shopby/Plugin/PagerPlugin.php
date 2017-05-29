<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\Shopby\Plugin;


class PagerPlugin
{
    protected $helper;

    public function __construct(\Amasty\Shopby\Helper\Data $helper)
    {
        $this->helper = $helper;
    }

    public function aroundGetPagerUrl(\Magento\Theme\Block\Html\Pager $subject, \Closure $closure, $params = [])
    {
        if($this->helper->isAjaxEnabled()) {
            $params['isAjax'] = null;
            $params['_'] = null;
        }

        return $closure($params);
    }
}
