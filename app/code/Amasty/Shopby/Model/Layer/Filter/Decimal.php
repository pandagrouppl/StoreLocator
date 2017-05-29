<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */
namespace Amasty\Shopby\Model\Layer\Filter;

use Amasty\Shopby\Model\Layer\Filter\Traits\FromToDecimal;
use Amasty\Shopby\Model\Source\DisplayMode;

class Decimal extends \Magento\CatalogSearch\Model\Layer\Filter\Decimal
    implements \Amasty\Shopby\Api\Data\FromToFilterInterface
{
    use FromToDecimal;

    protected $settingHelper;

    protected $currencySymbol;

    /**
     * @var \Amasty\Shopby\Model\Search\Adapter\Mysql\AggregationAdapter
     */
    protected $aggregationAdapter;

    protected $dataProvider;

    protected $shopbyRequest;

    protected $extraToValue;

    private $facetedData;

    public function __construct(
        \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer $layer,
        \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
        \Magento\Catalog\Model\ResourceModel\Layer\Filter\DecimalFactory $filterDecimalFactory,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Amasty\Shopby\Helper\FilterSetting $settingHelper,
        \Amasty\Shopby\Model\Search\Adapter\Mysql\AggregationAdapter $aggregationAdapter,
        \Magento\Catalog\Model\Layer\Filter\DataProvider\PriceFactory $dataProviderFactory,
        \Amasty\Shopby\Model\Request $shopbyRequest,
        array $data = []
    ) {
        $this->settingHelper = $settingHelper;
        $this->currencySymbol = $priceCurrency->getCurrencySymbol();
        $this->aggregationAdapter = $aggregationAdapter;
        $this->dataProvider = $dataProviderFactory->create(['layer' => $layer]);
        $this->shopbyRequest = $shopbyRequest;

        parent::__construct($filterItemFactory, $storeManager, $layer, $itemDataBuilder, $filterDecimalFactory, $priceCurrency, $data);
    }


    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        $filter = $this->shopbyRequest->getFilterParam($this);
        $noValidate = 0;
        if(!empty($filter) && !is_array($filter)) {
            list($from, $to) = explode('-', $filter);
            $to -= $this->getExtraToValue();
            $filter = implode("-", [$from, $to]);
        }
        if (!empty($filter) && !is_array($filter)) {
            $filterParams = explode(',', $filter);
            $validateFilter = $this->dataProvider->validateFilter($filterParams[0]);
            if (!$validateFilter) {
                $noValidate = 1;
            } else {
                $this->setFromTo($validateFilter[0], $validateFilter[1]);
            }
        }
        if ($this->isApplied()) {
            return $this;
        }

        if ($noValidate) {
            return $this;
        }

        $request->setParam($this->getRequestVar(), $filter);
        $apply = parent::apply($request);
        if (!empty($filter) && !is_array($filter)) {
            $filterSetting = $this->settingHelper->getSettingByLayerFilter($this);
            if ($filterSetting->getDisplayMode() == DisplayMode::MODE_SLIDER) {
                $facets = $this->getFacetedData();
                $arrayRange = $this->getExtremeValues($filterSetting, $facets);
                $this->setFromTo($arrayRange['from'], $arrayRange['to']);
            }
        }

        return $apply;
    }

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
            );
            $max = $this->getMax(
                floatval($facets['data']['max']),
                $filterSetting->getSliderMax()
            );

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

            $config = [
                    'from' => $from,
                    'to' => $to,
                    'min' => $min,
                    'max' => $max,
                    'requestVar' => $this->getRequestVar(),
                    'step' => round($filterSetting->getSliderStep(), 4),
                    'template' => $template
                ];
        }
        return $config;
    }

    protected function _getItemsData()
    {
        $attribute = $this->getAttributeModel();

        /** @var \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection $productCollection */
        $productCollection = $this->getLayer()->getProductCollection();
        $productSize = $productCollection->getSize();
        $facets = $productCollection->getFacetedData($attribute->getAttributeCode());

        $data = [];
        foreach ($facets as $key => $aggregation) {
            if ($key === 'data'){
                continue;
            }
            $count = $aggregation['count'];
            if (!$this->isOptionReducesResults($count, $productSize)) {
                continue;
            }
            list($from, $to) = explode('_', $key);
            if ($from == '*') {
                $from = '';
            }
            if ($to == '*') {
                $to = '';
            }

            $label = $this->renderRangeLabel(
                empty($from) ? 0 : $from,
                empty($to) ? $to : $to
            );

            $value = $from . '-' . $to;

            $data[] = [
                'label' => $label,
                'value' => $value,
                'count' => $count,
                'from' => $from,
                'to' => $to
            ];
        }

        return $data;
    }


    protected function renderRangeLabel($fromPrice, $toPrice)
    {
        $filterSetting = $this->settingHelper->getSettingByLayerFilter($this);
        if($filterSetting->getUnitsLabelUseCurrencySymbol()) {
            return parent::renderRangeLabel($fromPrice, $toPrice);
        }
        $formattedFromPrice = round($fromPrice, 4).' '.$filterSetting->getUnitsLabel();
        if ($toPrice === '') {
            return __('%1 and above', $formattedFromPrice);
        } else {
            return __('%1 - %2', $formattedFromPrice, round($toPrice, 4).' '.$filterSetting->getUnitsLabel());
        }
    }

    public function getExtraToValue()
    {
        if ($this->extraToValue == null) {
            $filterSetting = $this->settingHelper->getSettingByLayerFilter($this);
            $extraToValue = $this->getFloatNumber(
                $this->getSignsCount(
                    $filterSetting->getSliderStep(),
                    $filterSetting->getDisplayMode() == DisplayMode::MODE_SLIDER
                )
            );
            $this->extraToValue = $extraToValue;
            if ($filterSetting->getDisplayMode() != DisplayMode::MODE_SLIDER) {
                $this->extraToValue = 0;
            }
        }

        return $this->extraToValue;
    }

    private function getFacetedData()
    {
        if(is_null($this->facetedData)) {
            $productCollectionOrigin = $this->getLayer()->getProductCollection();
            $attribute = $this->getAttributeModel();

            if ($this->hasCurrentValue()) {
                $requestBuilder = clone $productCollectionOrigin->getMemRequestBuilder();
                $requestBuilder->removePlaceholder($attribute->getAttributeCode() . '.from');
                $requestBuilder->removePlaceholder($attribute->getAttributeCode() . '.to');
                $queryRequest = $requestBuilder->create();
                $facets = $this->aggregationAdapter->getBucketByRequest($queryRequest, $attribute->getAttributeCode());
            } else {
                $facets = $productCollectionOrigin->getFacetedData($attribute->getAttributeCode());
            }
            $this->facetedData = $facets;
        }

        return $this->facetedData;
    }
}
