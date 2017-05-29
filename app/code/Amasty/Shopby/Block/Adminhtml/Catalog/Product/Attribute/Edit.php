<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\Shopby\Block\Adminhtml\Catalog\Product\Attribute;

class Edit extends \Magento\Backend\Block\Template
{
    protected $coreRegistry;

    protected $displayModeSource;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Amasty\Shopby\Model\Source\DisplayMode\Proxy $displayModeSource,
        array $data = []
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->displayModeSource = $displayModeSource;
        parent::__construct($context, $data);
    }

    public function getFilterCode()
    {
        /** @var $attribute \Magento\Catalog\Model\ResourceModel\Eav\Attribute */
        $attribute = $this->coreRegistry->registry('entity_attribute');

        return \Amasty\Shopby\Helper\FilterSetting::ATTR_PREFIX . $attribute->getAttributeCode();
    }
}
