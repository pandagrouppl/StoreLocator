<?php

namespace PandaGroup\Westfield\Model\Catalog\Product;

class Attribute extends \Magento\Framework\Model\AbstractModel
{
    const XML_PATH_COLOR_ATTRIBUTE_CODE = 'light4website_westfield/settings/color_attribute_code';

    protected $sizeLabels = [];
    
    protected $objectManager;

    public function __construct()
    {
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }
    
    public function getAllColorOptionLabels() {
        return $this->getOptionsArrayByCode($this->getColorAttributeCode());
    }

    public function getAllSizeOptionLabels() {
        if (empty($this->sizeLabels)) {
            foreach ($this->getProductSizeAttributeCodes() as $attributeCode) {
                $optionArray = $this->getOptionsArrayByCode($attributeCode);
                $this->sizeLabels = $this->sizeLabels + $optionArray;
            }
        }

        return $this->sizeLabels;
    }

    public function getOptionsArrayByCode($attributeCode, $key = 'value', $value = 'label') {
        $options = $this->getAllAttributeOptionsByCode($attributeCode);

        $labels = array();
        foreach ($options as $option) {
            if (isset($option[$value]) && isset($option[$key]) && !empty($option[$key])  && !empty($option[$value])) {
                $labels[$option[$key]] = $option[$value];
            }
        }

        return $labels;
    }

    protected function getAllAttributeOptionsByCode($attributeCode) {
        $attributeAllOptions = [];
        $attributeId = $this->getAttributeIdByCode($attributeCode);
        $attributeModel = $this->objectManager->create('Magento\Catalog\Model\ResourceModel\Eav\Attribute');
        $attribute = $attributeModel->load($attributeId);

        if ($attribute->usesSource()) {
            $attributeAllOptions = $attribute->getSource()->getAllOptions();
        }

        return $attributeAllOptions;
    }

    protected function getAttributeIdByCode($attributeCode) {
        $entityAttributeModel = $this->objectManager->create('Magento\Eav\Model\ResourceModel\Entity\Attribute');
        return $entityAttributeModel->getIdByCode('catalog_product', $attributeCode);
    }

    protected function getColorAttributeCode($storeId = null) {
        $configModel = $this->objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface');
        return $configModel->getValue(self::XML_PATH_COLOR_ATTRIBUTE_CODE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getProductSize($product) {
        $sizeValue = $this->getProductSizeValue($product);

        if (empty($sizeValue)) {
            return '';
        } else {
            return $this->getSizeLabelByValue($sizeValue);
        }
    }

    protected function getProductSizeValue($product) {
        $productData = $product->getData();

        foreach ($this->getProductSizeAttributeCodes() as $sizeCode) {
            if (isset($productData[$sizeCode]) && !empty($productData[$sizeCode])) {
                return $productData[$sizeCode];
            }
        }

        return '';
    }

    public function getSizeLabelByValue($value) {
        $options = $this->getAllSizeOptionLabels();
        $label = '';

        if (isset($options[$value])) {
            $label = $options[$value];
        }

        return $label;
    }

    public function getProductSizeAttributeCodes() {
        return [
            'business_size',
            'business_orange',
            'casual_sizes',
            'casual_sizes',
            'suit_size',
            'suit_size_2',
            'sports_jackets_size',
            'pants_size'
        ];
    }
}
