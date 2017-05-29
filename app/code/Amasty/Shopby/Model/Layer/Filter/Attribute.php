<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */
namespace Amasty\Shopby\Model\Layer\Filter;

use Amasty\Shopby\Helper\FilterSetting;
use Amasty\Shopby\Model\Layer\Filter\Traits\FilterTrait;
use Magento\Catalog\Model\Layer\Filter\AbstractFilter;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Layer attribute filter
 */
class Attribute extends AbstractFilter
{
    use FilterTrait;
    /**
     * @var \Magento\Framework\Filter\StripTags
     */
    protected $tagFilter;

    /** @var  FilterSetting */
    protected $settingHelper;
    /**
     * @var \Amasty\Shopby\Api\Data\FilterSettingInterface
     */
    protected $filterSetting;

    /**
     * @var \Amasty\Shopby\Model\Search\Adapter\Mysql\AggregationAdapter
     */
    protected $aggregationAdapter;

    /**
     * @var \Amasty\Shopby\Model\Request
     */
    protected $shopbyRequest;

    /** @var  ScopeConfigInterface */
    protected $scopeConfig;

    public function __construct(
        \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer $layer,
        \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
        \Magento\Framework\Filter\StripTags $tagFilter,
        \Amasty\Shopby\Model\Search\Adapter\Mysql\AggregationAdapter $aggregationAdapter,
        array $data = [],
        FilterSetting $settingHelper,
        \Amasty\Shopby\Model\Request $shopbyRequest,
        ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct(
            $filterItemFactory,
            $storeManager,
            $layer,
            $itemDataBuilder,
            $data
        );
        $this->tagFilter = $tagFilter;
        $this->settingHelper = $settingHelper;
        $this->aggregationAdapter = $aggregationAdapter;
        $this->shopbyRequest = $shopbyRequest;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Apply attribute option filter to product collection
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        if($this->isApplied()) {
            return $this;
        }

        $attributeValue = $this->shopbyRequest->getFilterParam($this);

        if (empty($attributeValue)) {
            return $this;
        }
        
        $values = explode(',', $attributeValue);

        $this->setCurrentValue($values);

        if (!$this->isMultiselectAllowed() && count($values) > 1) {
            $values = array_slice($values, 0, 1);
        }
        $attribute = $this->getAttributeModel();
        /** @var \Amasty\Shopby\Model\ResourceModel\Fulltext\Collection $productCollection */
        $productCollection = $this->getLayer()
            ->getProductCollection();

        if($this->getFilterSetting()->isUseAndLogic()) {
            foreach($values as $key=>$value) {
                $productCollection->addFieldToFilter($this->getFakeAttributeCodeForApply($attribute->getAttributeCode(), $key), $value);
            }
        } else {
            $collectionValue = count($values) > 1 ? $values : $values[0];
            $productCollection->addFieldToFilter($attribute->getAttributeCode(), $collectionValue);
        }

        if ($this->shouldAddState()) {
            $this->addState($values);
        }
        return $this;
    }

    protected function isMultiselectAllowed()
    {
        return $this->getFilterSetting()->isMultiselect();
    }

    public function shouldAddState()
    {
        // Could be overwritten in plugins
        return true;
    }



    protected function addState(array $values)
    {
        $labels = [];
        foreach($values as $value){
            $labels[] = $this->getOptionText($value);
        }

        $this->getLayer()
            ->getState()
            ->addFilter($this->_createItem(implode(', ', $labels), $values));
    }

    /**
     * Get data array for building attribute filter items
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getItemsData()
    {
        $selected = !!$this->shopbyRequest->getFilterParam($this);
        if ($selected && !$this->isVisibleWhenSelected()) {
            return [];
        }

        $attribute = $this->getAttributeModel();
        /** @var \Amasty\Shopby\Model\ResourceModel\Fulltext\Collection $productCollectionOrigin */
        $productCollectionOrigin = $this->getLayer()
            ->getProductCollection();

        if ($this->hasCurrentValue() && !$this->getFilterSetting()->isUseAndLogic()){
            $requestBuilder = clone $productCollectionOrigin->getMemRequestBuilder();
            $requestBuilder->removePlaceholder($attribute->getAttributeCode());
            $queryRequest = $requestBuilder->create();
            $optionsFacetedData = $this->aggregationAdapter->getBucketByRequest($queryRequest, $attribute->getAttributeCode());
        } else {
            $optionsFacetedData = $productCollectionOrigin->getFacetedData($attribute->getAttributeCode());
        }
        if (count($optionsFacetedData)) {
            $attributeValue = $this->shopbyRequest->getFilterParam($this);
            $values = explode(",", $attributeValue);
            foreach ($values as $value) {
                if (!array_key_exists($value, $optionsFacetedData)) {
                    $optionsFacetedData[$value] = ['value' => $value, 'count' => 0];
                }
            }
        }


        $options = $attribute->getFrontend()
            ->getSelectOptions();

        if($this->getFilterSetting()->getSortOptionsBy() == \Amasty\Shopby\Model\Source\SortOptionsBy::NAME) {
            usort($options, [$this, 'sortOption']);
        }

        foreach ($options as $option) {
            if (empty($option['value'])) {
                continue;
            }

            if(isset($optionsFacetedData[$option['value']])  || $this->getAttributeIsFilterable($attribute) != static::ATTRIBUTE_OPTIONS_ONLY_WITH_RESULTS){
                $this->itemDataBuilder->addItemData(
                    $this->tagFilter->filter($option['label']),
                    $option['value'],
                    isset($optionsFacetedData[$option['value']]['count']) ? $optionsFacetedData[$option['value']]['count'] : 0
                );
            }
        }

        return $this->getLimitedItemsData();
    }

    /**
     * Could be overwritten in plugins
     * @return bool
     */
    public function isVisibleWhenSelected()
    {
        $hideByDefaultMagentoBehavior = !$this->scopeConfig->isSetFlag('amshopby/general/keep_single_choice_visible', \Magento\Store\Model\ScopeInterface::SCOPE_STORE) && !$this->isMultiselectAllowed();
        return !$hideByDefaultMagentoBehavior;
    }

    /**
     * get items data according to attrbiute settings
     * @return array
     */
    protected function getLimitedItemsData()
    {
        $itemsData = $this->itemDataBuilder->build();

        $setting = $this->settingHelper->getSettingByLayerFilter($this);

        if ($setting->getHideOneOption()) {
            if (count($itemsData) == 1) {
                $itemsData = [];
            }
        }

        return $itemsData;
    }

    /**
     * @return \Amasty\Shopby\Api\Data\FilterSettingInterface
     */
    protected function getFilterSetting()
    {
        if(is_null($this->filterSetting)) {
            $this->filterSetting = $this->settingHelper->getSettingByLayerFilter($this);
        }
        return $this->filterSetting;
    }

    public function sortOption($a, $b)
    {
        $pattern = '@^(\d+)@';
        if (preg_match($pattern, $a['label'], $ma) && preg_match($pattern, $b['label'], $mb)) {
            $r = $ma[1] - $mb[1];
            if ($r != 0) {
                return $r;
            }
        }
        return strcmp($a['label'], $b['label']);
    }

    protected function getFakeAttributeCodeForApply($attributeCode, $key)
    {
        if($key > 0) {
            $attributeCode .= \Amasty\Shopby\Model\Search\RequestGenerator::FAKE_SUFFIX . $key;
        }

        return $attributeCode;
    }

}
