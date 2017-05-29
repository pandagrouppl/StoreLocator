<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Block\Adminhtml\Config\Form\Field;
use Amasty\Shopby\Helper\Category;

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */
class CategoryFilter extends \Magento\Backend\Block\Template implements \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $eavConfig;

    /**
     * CategoryFilter constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Eav\Model\Config               $eavConfig
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Eav\Model\Config $eavConfig,
        array $data = []
    ) {
        $this->eavConfig = $eavConfig;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve HTML markup for given form element
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     *
     * @return string
     */
    public function render(
        \Magento\Framework\Data\Form\Element\AbstractElement $element
    ) {
        $title = __('Category Filter Settings');

        $attributeId = $this->eavConfig->getAttribute(\Magento\Catalog\Model\Product::ENTITY, Category::ATTRIBUTE_CODE)->getId();
        $url = $this->getUrl('catalog/product_attribute/edit',['attribute_id'=>$attributeId]);

        return '<button id="categoryFilterSettingsBtn" class="action-add" title="'.$title.'" type="button"
                onclick="document.location.href=\''.$url.'\'">
                        <span>'.$title.'</span>
                    </button>';
    }


}
