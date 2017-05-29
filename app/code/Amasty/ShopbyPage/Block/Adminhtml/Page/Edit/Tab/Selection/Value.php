<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\ShopbyPage\Block\Adminhtml\Page\Edit\Tab\Selection;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget;
use Magento\Framework\Registry;
use Amasty\ShopbyPage\Controller\RegistryConstants;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;

class Value extends Widget
{
    /**
     * Path to template file in theme.
     *
     * @var string
     */
    protected $_template = 'attribute/value.phtml';

    /** @var Registry*/
    protected $_coreRegistry;

    /** @var AbstractAttribute */
    protected $_eavAttribute;

    /** @var  int */
    protected $_attributeIdx;

    /** @var  mixed */
    protected $_attributeValue;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ){
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Get attribute
     * @return  AbstractAttribute
     */
    public function getEavAttribute()
    {
        if ($this->_eavAttribute === null){
            $this->_eavAttribute = $this->_coreRegistry->registry(RegistryConstants::ATTRIBUTE);
        }
        return $this->_eavAttribute;
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
     * @return array
     */
    public function getAttributeOptions()
    {
        return $this->getEavAttribute()->getFrontend()->getSelectOptions();
    }

    /**
     * @return string
     */
    public function getInputName()
    {
        return 'conditions[' . $this->getEavAttributeIdx() . '][value]';
    }

    /**
     * @return int|mixed
     */
    public function getEavAttributeIdx()
    {
        if ($this->_attributeIdx === null){
            $this->_attributeIdx = $this->_coreRegistry->registry(RegistryConstants::ATTRIBUTE_IDX);
        }
        return $this->_attributeIdx;
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

    /**
     * @return mixed
     */
    public function getEavAttributeValue()
    {
        return $this->_attributeValue;
    }

    /**
     * @return mixed|null|string
     */
    public function getFrontendInput()
    {
        return $this->getEavAttribute() ? $this->getEavAttribute()->getFrontendInput() : null;
    }
}