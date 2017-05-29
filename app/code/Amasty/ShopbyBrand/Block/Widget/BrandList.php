<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\ShopbyBrand\Block\Widget;

use Amasty\Shopby\Helper\OptionSetting as OptionSettingHelper;
use Magento\Catalog\Model\Product\Attribute\Repository;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Catalog\Model\ResourceModel\Layer\Filter\Attribute as FilterAttributeResource;

class BrandList extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    /** @var  Repository */
    private $repository;

    /** @var  ScopeConfigInterface */
    private $scopeConfig;

    /** @var OptionSettingHelper  */
    private $optionSettingHelper;

    /** @var array|null  */
    private $productCount = null;

    /** @var FilterAttributeResource  */
    private $filterAttributeResource;

    /** @var \Magento\CatalogInventory\Helper\Stock  */
    private $stockHelper;

    /** @var \Magento\Catalog\Model\Product\Visibility */
    protected $catalogProductVisibility;

    /** @var \Magento\Catalog\Api\CategoryRepositoryInterface  */
    private $categoryRepository;

    /** @var \Magento\Catalog\Model\Layer\Category\ItemCollectionProvider  */
    private $collectionProvider;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        Repository $repository,
        OptionSettingHelper $optionSetting,
        FilterAttributeResource $filterAttributeResource,
        \Magento\CatalogInventory\Helper\Stock $stockHelper,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Magento\Catalog\Model\Layer\Category\ItemCollectionProvider $collectionProvider,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->scopeConfig = $context->getScopeConfig();
        $this->repository = $repository;
        $this->optionSettingHelper = $optionSetting;
        $this->filterAttributeResource = $filterAttributeResource;
        $this->stockHelper = $stockHelper;
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->categoryRepository = $categoryRepository;
        $this->collectionProvider = $collectionProvider;
    }

    public function getIndex()
    {
        $items = $this->getItems();
        if (is_null($items)) {
            return [];
        }

        $this->sortItems($items);

        $letters = $this->items2letters($items);

        $columnCount = abs(intval($this->getData('columns')));
        if (!$columnCount) {
            $columnCount = 1;
        }
        $itemsPerColumn = ceil((sizeof($items) + sizeof($letters)) / max(1, $columnCount));

        $col = 0; // current column
        $num = 0; // current number of items in column
        $index = [];
        foreach ($letters as $letter => $items){
            $index[$col][$letter] = $items['items'];
            $num += $items['count'];
            $num++;
            if ($num >= $itemsPerColumn){
                $num = 0;
                $col++;
            }
        }

        return $index;
    }

    public function getItems()
    {
        $attribute_code = $this->scopeConfig->getValue('amshopby_brand/general/attribute_code', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($attribute_code == '') {
            return null;
        }

        $options = $this->repository->get($attribute_code)->getOptions();
        array_shift($options);

        $items = [];
        foreach ($options as $option) {
            $filter_code = \Amasty\Shopby\Helper\FilterSetting::ATTR_PREFIX . $attribute_code;
            $setting = $this->optionSettingHelper->getSettingByValue($option->getValue(), $filter_code, $this->_storeManager->getStore()->getId());

            $items[] = [
                'label' => $setting->getLabel(),
                'url'   => $setting->getUrlPath(),
                'img'   => $setting->getImageUrl(),
                'cnt'   => $this->_getOptionProductCount($setting->getValue())
            ];
        }

        if (!$this->_scopeConfig->getValue('amshopby_brand/brands_landing/display_zero',  \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) {
           $items = array_filter($items, [$this, "_removeEmptyBrands"]);
        }

        return $items;
    }

    protected function _removeEmptyBrands($var)
    {
        return $var['cnt'] > 0;
    }

    protected function sortItems(array &$items)
    {
        usort($items, function($a, $b) {
            $a['label'] = trim($a['label']);
            $b['label'] = trim($b['label']);

            $x = substr($a['label'], 0, 1);
            $y = substr($b['label'], 0, 1);
            if (is_numeric($x) && !is_numeric($y)) return 1;
            if (!is_numeric($x) && is_numeric($y)) return -1;

            return strcmp(strtoupper($a['label']), strtoupper($b['label']));
        });
    }

    protected function items2letters($items)
    {
        $letters = [];
        foreach ($items as $item) {
            if (function_exists('mb_strtoupper')) {
                $i = mb_strtoupper(mb_substr($item['label'], 0, 1, 'UTF-8'));
            } else {
                $i = strtoupper(substr($item['label'], 0, 1));
            }

            if (is_numeric($i)) { $i = '#'; }

            if (!isset($letters[$i]['items'])){
                $letters[$i]['items'] = array();
            }

            $letters[$i]['items'][] = $item;

            if (!isset($letters[$i]['count'])){
                $letters[$i]['count'] = 0;
            }

            $letters[$i]['count']++;
        }

        return $letters;
    }

    /**
     * @return array
     */
    public function getAllLetters()
    {
        $brandLetters = [];
        foreach ($this->getIndex() as $letters) {
            $brandLetters = array_merge($brandLetters, array_keys($letters));
        }
        return $brandLetters;
    }

    public function getSearchHtml()
    {
        if (!$this->getData('show_search') || !$this->getItems()) {
            return '';
        }
        $searchCollection = [];
        foreach ($this->getItems() as $item) {
            $searchCollection[$item['url']] = $item['label'];
        }
        $searchCollection = json_encode($searchCollection);
        /** @var \Magento\Framework\View\Element\Template $block */
        $block = $this->getLayout()->createBlock('\Magento\Framework\View\Element\Template', 'ambrands.search')
            ->setTemplate('Amasty_ShopbyBrand::brand_search.phtml')
            ->setBrands($searchCollection);
        return $block->toHtml();
    }

    /**
     * get brand product count
     *
     * @param $optionId
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getOptionProductCount($optionId)
    {
        if (is_null($this->productCount)) {
            $select = $this->getRootProductSelect();

            $connection = $this->filterAttributeResource->getConnection();
            $attrCode = $this->scopeConfig->getValue('amshopby_brand/general/attribute_code', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $attribute = $this->repository->get($attrCode);
            $tableAlias = sprintf('%s_idx', $attribute->getAttributeCode());
            $storeId = $this->_storeManager->getStore()->getId();
            $conditions = [
                "{$tableAlias}.entity_id = e.entity_id",
                $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
                $connection->quoteInto("{$tableAlias}.store_id = ?", $storeId),
            ];

            $select->join(
                [$tableAlias => $this->filterAttributeResource->getMainTable()],
                join(' AND ', $conditions),
                ['value', 'count' => new \Zend_Db_Expr("COUNT({$tableAlias}.entity_id)")]
            )->group(
                "{$tableAlias}.value"
            );

            $this->productCount =  $connection->fetchPairs($select);
        }

        return isset($this->productCount[$optionId]) ? $this->productCount[$optionId] : '0';
    }

    /**
     * @return \Magento\Framework\DB\Select
     */
    private function getRootProductSelect()
    {
        $rootCategoryId = $this->_storeManager->getStore()->getRootCategoryId();
        $category = $this->categoryRepository->get($rootCategoryId);
        $collection = $this->collectionProvider->getCollection($category);

        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

        $showOutOfStock = $this->scopeConfig->isSetFlag(
            \Magento\CatalogInventory\Model\Configuration::XML_PATH_SHOW_OUT_OF_STOCK,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if (!$showOutOfStock) {
            $this->stockHelper->addIsInStockFilterToCollection($collection);
        }

        $select = $collection->getSelect();
        $select->reset(\Magento\Framework\DB\Select::COLUMNS);

        return $select;
    }

    /**
     * Apply options from config
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $configValues = $this->_scopeConfig->getValue('amshopby_brand/brands_landing',  \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        foreach ($configValues as $option => $value) {
            if (is_null($this->getData($option))) {
                $this->setData($option, $value);
            }
        }
        return parent::_beforeToHtml();
    }
}
