<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\Shopby\Block\Product\ProductList;


use Magento\Framework\View\Element\Template;

class Ajax extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Amasty\Shopby\Helper\Data
     */
    protected $helper;

    public function __construct(Template\Context $context, \Amasty\Shopby\Helper\Data $helper, array $data = [])
    {
        $this->helper = $helper;
        parent::__construct($context, $data);
    }


    public function canShowBlock()
    {
        return $this->helper->isAjaxEnabled();
    }

    public function submitByClick()
    {
        return $this->helper->getSubmitFiltersMode() === \Amasty\Shopby\Model\Source\SubmitMode::BY_BUTTON_CLICK;
    }

    public function scrollUp()
    {
        return $this->_scopeConfig->isSetFlag('amshopby/general/ajax_scroll_up');
    }
}
