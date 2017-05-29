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


class StatePlugin
{
    /**
     * @var \Amasty\Shopby\Helper\Data
     */
    protected $helper;

    public function __construct(\Amasty\Shopby\Helper\Data $helper)
    {
        $this->helper = $helper;
    }

    public function aroundGetClearUrl(\Magento\LayeredNavigation\Block\Navigation\State $subject, \Closure $closure)
    {
        if(!$this->helper->isAjaxEnabled()) {
            return $closure();
        }

        $filterState = [];
        $filterState['isAjax'] = null;
        $filterState['_'] = null;

        foreach ($subject->getActiveFilters() as $item) {
            $filterState[$item->getFilter()->getRequestVar()] = $item->getFilter()->getCleanValue();
        }
        $params['_current'] = true;
        $params['_use_rewrite'] = true;
        $params['_query'] = $filterState;
        $params['_escape'] = true;
        return $subject->getUrl('*/*/*', $params);
    }
}
