<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\ShopbyBrand\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Catalog\Model\Product\Attribute\Repository;
use Amasty\Shopby\Model\ResourceModel\OptionSetting\CollectionFactory as OptionCollectionFactory;
use Amasty\Shopby\Helper\FilterSetting;
use Amasty\Shopby\Api\Data\OptionSettingInterface;

class Data extends AbstractHelper
{
    /** @var \Magento\Framework\UrlInterface  */
    private $url;

    /** @var \Amasty\Shopby\Helper\OptionSetting  */
    private $optionSettingHelper;

    /** @var Repository  */
    private $repository;

    /** @var OptionCollectionFactory  */
    private $optionCollectionFactory;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Amasty\Shopby\Helper\OptionSetting $optionSettingHelper,
        Repository $repository,
        OptionCollectionFactory $optionCollectionFactory
    ) {
        parent::__construct($context);
        $this->url = $context->getUrlBuilder();
        $this->optionSettingHelper = $optionSettingHelper;
        $this->repository = $repository;
        $this->optionCollectionFactory = $optionCollectionFactory;
    }
    
    public function getAllBrandsUrl($scopeCode = null)
    {
        $pageIdentifier = $this->scopeConfig->getValue('amshopby_brand/general/brands_page', ScopeInterface::SCOPE_STORE, $scopeCode);
        return $this->url->getUrl($pageIdentifier);
    }

    /**
     * Update branded option setting collection.
     */
    public function updateBrandOptions()
    {
        $attrCode   = $this->scopeConfig->getValue('amshopby_brand/general/attribute_code', ScopeInterface::SCOPE_STORE);
        if (!$attrCode) {
            return;
        }
        $filterCode = FilterSetting::ATTR_PREFIX . $attrCode;
        $currentAttributeValues = $this->getCurrentBrandAttributeValues($attrCode);
        // Temporary disabled: planning feature "Relink"
        //$this->deleteExtraBrandOptions($currentAttributeValues, $filterCode);
        $this->addMissingBrandOptions($currentAttributeValues, $filterCode);
    }

    /**
     * @param string $attrCode
     * @return string[]
     */
    private function getCurrentBrandAttributeValues($attrCode)
    {
        /** @var \Magento\Eav\Model\Entity\Attribute\Option[]  $attributeOptions */
        $attributeOptions = $this->repository->get($attrCode)->getOptions();
        $attributeValues = [];
        foreach ($attributeOptions as $option) {
            if ($option->getValue()) {
                $attributeValues[] = $option->getValue();
            }
        }
        return $attributeValues;
    }

    /**
     * @param string[] $currentAttributeValues
     * @param string $filterCode
     */
    private function deleteExtraBrandOptions($currentAttributeValues, $filterCode)
    {
        $settingOptionCollection = $this->optionCollectionFactory->create();
        $optionsToDelete = $settingOptionCollection
            ->addFieldToFilter(OptionSettingInterface::FILTER_CODE, $filterCode)
            ->addFieldToFilter(OptionSettingInterface::VALUE, ['nin' => $currentAttributeValues]);
        foreach ($optionsToDelete as $optionSetting ) {
            /** @var \Amasty\Shopby\Model\OptionSetting $optionSetting */
            $optionSetting->getResource()->delete($optionSetting);
        }
    }

    /**
     * @param string[] $currentAttributeValues
     * @param string $filterCode
     */
    private function addMissingBrandOptions($currentAttributeValues, $filterCode)
    {
        foreach ($currentAttributeValues as $value) {
            /** @var \Amasty\Shopby\Model\OptionSetting $optionSetting */
            $optionSetting = $this->optionSettingHelper->getSettingByValue($value, $filterCode, 0);
            if (!$optionSetting->getId()) {
                $optionSetting->getResource()->save($optionSetting);
            }
        }
    }
}
