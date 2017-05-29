<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */
namespace Amasty\Shopby\Model\Layer\Filter;
use Amasty\Shopby\Model\Layer\Filter\Traits\FilterTrait;
use Amasty\Shopby\Model\Source\RenderCategoriesLevel;
use Amasty\Shopby\Helper\Category as CategoryHelper;


/**
 * Layer category filter
 */
class Category extends \Magento\Catalog\Model\Layer\Filter\AbstractFilter
{
    use FilterTrait;
    /**
     * @var \Amasty\Shopby\Helper\FilterSetting
     */
    protected $settingHelper;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * @var \Magento\Catalog\Model\Layer\Filter\DataProvider\Category
     */
    protected $dataProvider;

    /**
     * @var \Amasty\Shopby\Model\Search\Adapter\Mysql\AggregationAdapter
     */
    protected $aggregationAdapter;

    /** @var \Amasty\Shopby\Model\Category\Manager\Proxy  */
    protected $categoryManager;

    /** @var \Magento\Catalog\Api\CategoryRepositoryInterface  */
    protected $categoryRepository;

    /** @var Item\CategoryExtendedDataBuilder  */
    protected $categoryExtendedDataBuilder;

    /** @var CategoryItemsFactory  */
    protected $categoryItemsFactory;

    /** @var \Amasty\Shopby\Model\Request  */
    protected $shopbyRequest;

    /** @var \Amasty\Shopby\Helper\Category  */
    protected $categoryHelper;

    public function __construct(
        \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer $layer,
        \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
        \Magento\Framework\Escaper $escaper,
        \Magento\Catalog\Model\Layer\Filter\DataProvider\CategoryFactory $categoryDataProviderFactory,
        \Amasty\Shopby\Helper\FilterSetting $settingHelper,
        \Amasty\Shopby\Model\Search\Adapter\Mysql\AggregationAdapter $aggregationAdapter,
        \Amasty\Shopby\Model\Category\Manager\Proxy $categoryManager,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Amasty\Shopby\Model\Layer\Filter\Item\CategoryExtendedDataBuilder $categoryExtendedDataBuilder,
        \Amasty\Shopby\Model\Layer\Filter\CategoryItemsFactory $categoryItemsFactory,
        \Amasty\Shopby\Model\Request $shopbyRequest,
        \Amasty\Shopby\Helper\Category $categoryHelper,
        array $data = []
    ) {
        parent::__construct(
            $filterItemFactory,
            $storeManager,
            $layer,
            $itemDataBuilder,
            $data
        );
        $this->escaper = $escaper;
        $this->_requestVar = 'cat';
        $this->dataProvider = $categoryDataProviderFactory->create(['layer' => $this->getLayer()]);
        $this->settingHelper = $settingHelper;
        $this->aggregationAdapter = $aggregationAdapter;
        $this->categoryManager = $categoryManager;
        $this->categoryRepository = $categoryRepository;
        $this->categoryExtendedDataBuilder = $categoryExtendedDataBuilder;
        $this->categoryItemsFactory = $categoryItemsFactory;
        $this->shopbyRequest = $shopbyRequest;
        $this->categoryHelper = $categoryHelper;
    }

    /**
     * Apply category filter to product collection
     *
     * @param   \Magento\Framework\App\RequestInterface $request
     * @return  $this
     */
    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        if($this->isApplied()) {
            return $this;
        }
        $categoryId = $this->shopbyRequest->getFilterParam($this) ?: $request->getParam('id');
        if (empty($categoryId)) {
            return $this;
        }
        $categoryIds = explode(',', $categoryId);
        $categoryIds = array_unique($categoryIds);
        /** @var \Amasty\Shopby\Model\ResourceModel\Fulltext\Collection $productCollection */
        $productCollection = $this->getLayer()->getProductCollection();
        $setting = $this->settingHelper->getSettingByLayerFilter($this);

        if($setting->isMultiselect() && $request->getParam('id') != $categoryId){
            $this->setCurrentValue($categoryIds);
            $productCollection->addFieldToFilter(CategoryHelper::ATTRIBUTE_CODE, $categoryIds);
            $category = $this->getLayer()->getCurrentCategory();
            $child = $category->getCollection()->addFieldToFilter($category->getIdFieldName(), $categoryIds)->addAttributeToSelect('name');
            $categoriesInState = [];
            foreach ($categoryIds as $categoryId) {
                if($currentCategory = $child->getItemById($categoryId)) {
                    $categoriesInState[$currentCategory->getId()] = $currentCategory->getName();
                    //$this->getLayer()->getState()->addFilter($this->_createItem($currentCategory->getName(), $categoryId));
                } else { // category not found
                    // $this->getLayer()->getState()->addFilter($this->_createItem('Error!', $categoryId));
                }
            }
            $this->getLayer()->getState()->addFilter($this->_createItem(implode(", ", $categoriesInState), array_keys($categoriesInState)));
        } else {
            $this->dataProvider->setCategoryId($categoryId);
            $category = $this->dataProvider->getCategory();
            $productCollection->addCategoryFilter($category);
            if ($request->getParam('id') != $category->getId() && $this->dataProvider->isValid()) {
                $this->getLayer()->getState()->addFilter($this->_createItem($category->getName(), $categoryId));
            }
        }

        return $this;
    }

    /**
     * Get filter value for reset current filter state
     *
     * @return mixed|null
     */
    public function getResetValue()
    {
        return $this->dataProvider->getResetValue();
    }

    /**
     * Get filter name
     *
     * @return \Magento\Framework\Phrase
     */
    public function getName()
    {
        return __('Category');
    }

    /**
     * Get fiter items count
     *
     * @return int
     */
    public function getItemsCount()
    {
        if(!$this->categoryHelper->isCategoryFilterExtended()) {
            return parent::getItemsCount();
        }
        return  $this->getItems()->getCount();
    }

    protected function _initItems()
    {
        if(!$this->categoryHelper->isCategoryFilterExtended()) {
            return parent::_initItems();
        }
        $data = $this->getExtendedCategoryData();
        /** @var CategoryItems $itemsCollection */
        $itemsCollection = $this->categoryItemsFactory->create();
        $itemsCollection->setStartPath($data['startPath']);
        $itemsCollection->setCount($data['count']);
        foreach ($data['items'] as $path=>$items) {
            foreach ($items as $itemData) {
                $itemsCollection->addItem(
                    $path,
                    $this->_createItem($itemData['label'], $itemData['value'], $itemData['count'])
                );
            }
        }

        $this->_items = $itemsCollection;
        return $this;
    }


    /**
     * Get data array for building category filter items
     *
     * @return array
     */
    protected function _getItemsData()
    {
        $optionsFacetedData = $this->getFacetedData();
        $category = $this->dataProvider->getCategory();
        $categories = $category->getChildrenCategories();

        if ($category->getIsActive()) {
            foreach ($categories as $category) {
                if ($category->getIsActive()
                    && isset($optionsFacetedData[$category->getId()])
                ) {
                    $this->itemDataBuilder->addItemData(
                        $this->escaper->escapeHtml($category->getName()),
                        $category->getId(),
                        $optionsFacetedData[$category->getId()]['count']
                    );
                }
            }
        }

        $itemsData =  $this->itemDataBuilder->build();
        $setting = $this->settingHelper->getSettingByLayerFilter($this);
        if ($setting->getHideOneOption()) {
            if (count($itemsData) == 1) {
                $itemsData = [];
            }
        }

        if($setting->getSortOptionsBy() == \Amasty\Shopby\Model\Source\SortOptionsBy::NAME) {
            usort($itemsData, [$this, 'sortOption']);
        }

        return $itemsData;
    }

    public function sortOption($a, $b)
    {
        return strcmp($a['label'], $b['label']);
    }

    /**
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getExtendedCategoryData()
    {
        $setting = $this->settingHelper->getSettingByLayerFilter($this);
        $optionsFacetedData = $this->getFacetedData();
        $startCategory = $this->categoryHelper->getStartCategory();
        $startPath = $startCategory->getPath();
        $minLevel = $startCategory->getLevel();
        $isRenderAllCategoriesTree = (bool)$setting->getRenderAllCategoriesTree();
        $isRenderAllCategoriesTree = $isRenderAllCategoriesTree || $this->getLayer()->getCurrentCategory()->getId() == $this->categoryManager->getRootCategoryId();
        $isRenderAllCategoriesTree = $isRenderAllCategoriesTree || $this->getRenderCategoriesLevel() == RenderCategoriesLevel::CURRENT_CATEGORY_CHILDREN;
        $currentCategoryPath = $this->getLayer()->getCurrentCategory()->getPath();
        $currentCategoryParents = explode('/', $currentCategoryPath);

        $maxLevel = $minLevel + $this->getCategoriesTreeDept();
        /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $collection */
        $collection = $startCategory->getCollection();
        $isFlat = $collection instanceof \Magento\Catalog\Model\ResourceModel\Category\Flat\Collection;
        $mainTablePrefix = $isFlat ? 'main_table.' : '';
        $collection->addAttributeToSelect(
            'name'
        )->addAttributeToFilter(
            $mainTablePrefix.'is_active',
            1
        )->addFieldToFilter($mainTablePrefix.'path', ['like'=>$startPath.'%'])
            ->addFieldToFilter($mainTablePrefix.'level', ['gt'=>$minLevel])
            ->addFieldToFilter($mainTablePrefix.'level', ['lteq'=>$maxLevel])
            ->setOrder(
                $mainTablePrefix.'position',
                \Magento\Framework\DB\Select::SQL_ASC
            );
        $mainTablePrefix = $isFlat ? 'main_table.' : 'e.';
        $collection->getSelect()->joinLeft(['parent'=>$collection->getMainTable()], $mainTablePrefix.'parent_id = parent.entity_id', ['parent_path'=>'parent.path']);

        foreach($collection as $category) {
            if(!isset($optionsFacetedData[$category->getId()])) {
                continue;
            }
            if(!$isRenderAllCategoriesTree && !in_array($category->getId(), $currentCategoryParents) && strpos($category->getPath(), $currentCategoryPath) !== 0) {
                continue;
            }
            $this->categoryExtendedDataBuilder->addItemData(
                $category->getParentPath(),
                $this->escaper->escapeHtml($category->getName()),
                $category->getId(),
                $optionsFacetedData[$category->getId()]['count']
            );
        }
        $itemsData = [];
        $itemsData['count'] = $this->categoryExtendedDataBuilder->getItemsCount();
        $itemsData['startPath'] = $startPath;
        $itemsData['items'] = $this->categoryExtendedDataBuilder->build();


        if ($setting->getHideOneOption()) {
            if ($itemsData['count'] == 1) {
                $itemsData['items'] = [];
                $itemsData['count'] = 0;
            }
        }

        if($setting->getSortOptionsBy() == \Amasty\Shopby\Model\Source\SortOptionsBy::NAME) {
            foreach($itemsData['items'] as $path=>&$items) {
                usort($items, [$this, 'sortOption']);
            }
        }

        return $itemsData;
    }

    protected function getFacetedData()
    {
        /** @var \Amasty\Shopby\Model\ResourceModel\Fulltext\Collection $productCollection */
        $productCollection = $this->getLayer()->getProductCollection();

        if(
            $this->hasCurrentValue() ||
            $this->getRenderCategoriesLevel() == RenderCategoriesLevel::CURRENT_CATEGORY_LEVEL ||
            $this->getRenderCategoriesLevel() == RenderCategoriesLevel::ROOT_CATEGORY
        ) {
            $startCategoryForCountBucket = $this->categoryHelper->getStartCategory();
            $requestBuilder = clone $productCollection->getMemRequestBuilder();
            $requestBuilder->removePlaceholder(CategoryHelper::ATTRIBUTE_CODE);
            /* add current category to filter, because it's must be */
            $requestBuilder->bind(CategoryHelper::ATTRIBUTE_CODE, $startCategoryForCountBucket->getId());
            $queryRequest = $requestBuilder->create();
            $optionsFacetedData = $this->aggregationAdapter->getBucketByRequest($queryRequest, 'category');
        } else {
            $optionsFacetedData = $productCollection->getFacetedData('category');
        }

        return $optionsFacetedData;
    }

    /**
     *
     * @return int
     */
    protected function getRenderCategoriesLevel()
    {
        $setting = $this->settingHelper->getSettingByLayerFilter($this);
        return $setting->getRenderCategoriesLevel();
    }

    /**
     *
     * @return int
     */
    protected function getCategoriesTreeDept()
    {
        $setting = $this->settingHelper->getSettingByLayerFilter($this);
        return $setting->getCategoryTreeDepth();
    }


}
