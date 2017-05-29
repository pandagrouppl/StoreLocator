<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */
namespace Amasty\Shopby\Model\Layer\Filter;

use Amasty\Shopby\Model\FilterSetting;
use Amasty\Shopby\Model\Layer\Filter\Traits\FromToDecimal;
use Amasty\Shopby\Model\Source\DisplayMode;
use Magento\Catalog\Model\Layer\Filter\Dynamic\AlgorithmFactory;

class Price extends \Magento\CatalogSearch\Model\Layer\Filter\Price
    implements \Amasty\Shopby\Api\Data\FromToFilterInterface
{
    use FromToDecimal;

    /**
     * @var \Amasty\Shopby\Helper\FilterSetting
     */
    protected $settingHelper;

    /**
     * @var string
     */
    protected $currencySymbol;

    /**
     * @var \Magento\Catalog\Model\Layer\Filter\DataProvider\Price
     */
    protected $dataProvider;

    /**
     * @var \Amasty\Shopby\Model\Search\Adapter\Mysql\AggregationAdapter
     */
    protected $aggregationAdapter;


    /**
     * @var
     */
    protected $facetedData;

    /**
     * @var \Amasty\Shopby\Model\Request
     */
    protected $shopbyRequest;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\Registry|null
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * Price constructor.
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Layer $layer
     * @param \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder
     * @param \Magento\Catalog\Model\ResourceModel\Layer\Filter\Price $resource
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Search\Dynamic\Algorithm $priceAlgorithm
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param AlgorithmFactory $algorithmFactory
     * @param \Magento\Catalog\Model\Layer\Filter\DataProvider\PriceFactory $dataProviderFactory
     * @param \Amasty\Shopby\Helper\FilterSetting $settingHelper
     * @param \Amasty\Shopby\Model\Search\Adapter\Mysql\AggregationAdapter $aggregationAdapter
     * @param \Amasty\Shopby\Model\Request $shopbyRequest
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer $layer,
        \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
        \Magento\Catalog\Model\ResourceModel\Layer\Filter\Price $resource,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Search\Dynamic\Algorithm $priceAlgorithm,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Catalog\Model\Layer\Filter\Dynamic\AlgorithmFactory $algorithmFactory,
        \Magento\Catalog\Model\Layer\Filter\DataProvider\PriceFactory $dataProviderFactory,
        \Amasty\Shopby\Helper\FilterSetting $settingHelper,
        \Amasty\Shopby\Model\Search\Adapter\Mysql\AggregationAdapter $aggregationAdapter,
        \Amasty\Shopby\Model\Request $shopbyRequest,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->settingHelper = $settingHelper;
        $this->currencySymbol = $priceCurrency->getCurrencySymbol();
        $this->dataProvider = $dataProviderFactory->create(['layer' => $layer]);
        $this->aggregationAdapter = $aggregationAdapter;
        $this->shopbyRequest = $shopbyRequest;
        $this->scopeConfig = $scopeConfig;
        $this->priceCurrency = $priceCurrency;
        parent::__construct(
            $filterItemFactory, $storeManager, $layer, $itemDataBuilder,
            $resource, $customerSession, $priceAlgorithm, $priceCurrency,
            $algorithmFactory, $dataProviderFactory, $data
        );
    }

    /**
     * @return array
     */
    public function getFromToConfig()
    {
        $config = [
            'from'          => null,
            'to'            => null,
            'min'           => null,
            'max'           => null,
            'requestVar'    => null,
            'step'          => null,
            'template'      => null];

        $filterSetting = $this->settingHelper->getSettingByLayerFilter($this);

        if ((string)$filterSetting->getDisplayMode() === (string)DisplayMode::MODE_SLIDER ||
            (string)$filterSetting->getDisplayMode() === (string)DisplayMode::MODE_FROM_TO_ONLY ||
            $filterSetting->getAddFromToWidget() === '1') {

            $facets = $this->getFacetedData();

            if (!isset($facets['data'])) {
                return $config;
            }

            $min = $this->getMin(
                    floatval($facets['data']['min']),
                    $filterSetting->getSliderMin()
                ) * $this->getCurrencyRate();
            $max = $this->getMax(
                    floatval($facets['data']['max']),
                    $filterSetting->getSliderMax()
                ) * $this->getCurrencyRate();

            if($min == $max) {
                return $config;
            }
            $from = !empty($this->getCurrentFrom()) ? floatval($this->getCurrentFrom()) : null;
            $to = !empty($this->getCurrentTo()) ? floatval($this->getCurrentTo()) : null;
            if ($filterSetting->getUnitsLabelUseCurrencySymbol()) {
                $template = $this->currencySymbol . '{from} - ' . $this->currencySymbol . '{to}';
            } else {
                $template = '{from}' . $filterSetting->getUnitsLabel() . ' - {to}' . $filterSetting->getUnitsLabel();
            }

            $config =
                [
                    'from' => $from,
                    'to' => $to,
                    'min' => $min,
                    'max' => $max,
                    'requestVar'    => $this->getRequestVar(),
                    'step'          => round($filterSetting->getSliderStep(), 4),
                    'template'      => $template,
                ];
        }
        return $config;
    }

    /**
     * @return int
     */
    public function getItemsCount()
    {
        $filterSetting = $this->settingHelper->getSettingByLayerFilter($this);
        $ignoreRanges = $filterSetting->getDisplayMode() == DisplayMode::MODE_FROM_TO_ONLY
            || $filterSetting->getDisplayMode() == DisplayMode::MODE_SLIDER;
        $itemsCount = $ignoreRanges ? 0 : parent::getItemsCount();
        if ($itemsCount == 0) {
            /**
             * show up filter event don't have any option
             */
            $fromToConfig = $this->getFromToConfig();
            if ($fromToConfig && $fromToConfig['min'] != $fromToConfig['max']) {
                return 1;
            }

        }

        return $itemsCount;
    }

    /**
     * @return array
     */
    protected function _getItemsData()
    {

        $attribute = $this->getAttributeModel();
        $this->_requestVar = $attribute->getAttributeCode();

        $facets = $this->getFacetedData();

        $data = [];
        if (count($facets) > 1) { // two range minimum
            foreach ($facets as $key => $aggregation) {
                $count = $aggregation['count'];
                if (strpos($key, '_') === false) {
                    continue;
                }
                $data[] = $this->prepareData($key, $count, $data);
            }
        }

        if (count($this->getFromToConfig()) && count($data) == 1) {
            $data = [];
        }
        return $data;
    }

    /**
     * @param string $key
     * @param int $count
     * @return array
     */
    protected function prepareData($key, $count)
    {
        list($from, $to) = explode('_', $key);
        if ($from == '*') {
            $from = $this->getFrom($to);
        }
        if ($to == '*') {
            $to = '';
        }
        $label = $this->_renderRangeLabel(
            empty($from) ? 0 : $from * $this->getCurrencyRate(),
            empty($to) ? $to : $to * $this->getCurrencyRate()
        );
        $value = $from . '-' . $to . $this->dataProvider->getAdditionalRequestData();

        $data = [
            'label' => $label,
            'value' => $value,
            'count' => $count,
            'from' => $from,
            'to' => $to,
        ];

        return $data;
    }


    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @return $this
     */
    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        $filter = $this->shopbyRequest->getFilterParam($this);
        $noValidate = 0;
        if(!empty($filter) && !is_array($filter)) {
            $filterParams = explode(',', $filter);
            $validateFilter = $this->dataProvider->validateFilter($filterParams[0]);
            if (!$validateFilter) {
                $noValidate =1;
            } else {
                $this->setFromTo($validateFilter[0], $validateFilter[1]);
            }
        }

        if($this->isApplied()) {
            return $this;
        }

        $request->setParam($this->getRequestVar(), $filter);
        $apply = parent::apply($request);

        if ($noValidate) {
            return $this;
        }

        if(!empty($filter) && !is_array($filter)) {
            $filterSetting = $this->settingHelper->getSettingByLayerFilter($this);
            if ($filterSetting->getDisplayMode() == DisplayMode::MODE_SLIDER) {
                $arrayRange = $this->getExtremeValues(
                    $filterSetting,
                    $this->getFacetedData(),
                    $this->getCurrencyRate()
                );

                $this->getLayer()->getProductCollection()->addFieldToFilter(
                    'price',
                    $arrayRange
                );
            }
        }

        return $apply;
    }

    /**
     * @return mixed
     */
    protected function getFacetedData()
    {
        if(is_null($this->facetedData)) {
            /** @var \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection $productCollection */
            $productCollection = $this->getLayer()->getProductCollection();
            $attribute = $this->getAttributeModel();
            if ($this->hasCurrentValue()) {
                $requestBuilder = clone $productCollection->getMemRequestBuilder();
                $filterSetting = $this->settingHelper->getSettingByLayerFilter($this);
                if ($this->scopeConfig
                    ->getValue(AlgorithmFactory::XML_PATH_RANGE_CALCULATION) != AlgorithmFactory::RANGE_CALCULATION_IMPROVED || $this->isUnical($filterSetting)) {
                    $requestBuilder->removePlaceholder($attribute->getAttributeCode() . '.from');
                    $requestBuilder->removePlaceholder($attribute->getAttributeCode() . '.to');
                }
                $queryRequest = $requestBuilder->create();
                $facets = $this->aggregationAdapter->getBucketByRequest($queryRequest, $attribute->getAttributeCode());
            } else {
                $facets = $productCollection->getFacetedData($attribute->getAttributeCode());
            }
            if (!$this->_coreRegistry->registry('originalFacets')) {
                $this->_coreRegistry->register('originalFacets', $facets);
            }
            $this->facetedData = $this->_coreRegistry->registry('originalFacets');
        }

        return $this->facetedData;
    }

    /**
     * @param $filterSetting
     * @return bool
     */
    public function isUnical($filterSetting)
    {
        return ($filterSetting->getDisplayMode() == \Amasty\Shopby\Model\Source\DisplayMode::MODE_SLIDER ||
        $filterSetting->getDisplayMode() == \Amasty\Shopby\Model\Source\DisplayMode::MODE_FROM_TO_ONLY ||
        $filterSetting->getAddFromToWidget() === '1');
    }
}
