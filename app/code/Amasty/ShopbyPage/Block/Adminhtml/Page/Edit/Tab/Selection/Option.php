<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\ShopbyPage\Block\Adminhtml\Page\Edit\Tab\Selection;

use Magento\Backend\Block\Widget;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;

class Option extends Widget implements RendererInterface
{
    /**
     * Path to template file in theme.
     *
     * @var string
     */
    protected $_template = 'attribute/option.phtml';

    /** @var AbstractAttribute */
    protected $_eavAttribute;

    /** @var  int */
    protected $_attributeIdx;

    /** @var  mixed */
    protected $_attributeValue;

    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getValueHtml()
    {
        $block = $this->getLayout()
            ->createBlock('Amasty\ShopbyPage\Block\Adminhtml\Page\Edit\Tab\Selection\Value')
            ->setEavAttributeValue($this->_attributeValue)
            ->setEavAttributeIdx($this->_attributeIdx);

        if ($this->_eavAttribute){
            $block->setEavAttribute($this->_eavAttribute);
        }

        return $block->toHtml();
    }

    /**
     * @param AbstractAttribute $attribute
     * @return $this
     */
    public function setEavAttribute(AbstractAttribute $attribute)
    {
        $this->_eavAttribute = $attribute;
        return $this;
    }

    /**
     * @param $idx
     * @return $this
     */
    public function setEavAttributeIdx($idx)
    {
        $this->_attributeIdx = $idx;
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setEavAttributeValue($value)
    {
        $this->_attributeValue = $value;
        return $this;
    }
}