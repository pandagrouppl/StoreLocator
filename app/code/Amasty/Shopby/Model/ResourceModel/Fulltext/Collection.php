<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */
namespace Amasty\Shopby\Model\ResourceModel\Fulltext;

use Amasty\Shopby\Helper\Category;
use Magento\CatalogInventory\Helper\Stock;
use Magento\Framework\DB\Select;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Phrase;
use Magento\Framework\Search\Response\QueryResponse;
use Magento\Framework\Search\Adapter\Mysql\TemporaryStorage;

/**
 * Fulltext Collection
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Collection extends \Magento\Catalog\Model\ResourceModel\Product\Collection
{
    /** @var  QueryResponse */
    protected $queryResponse;

    /**
     * Catalog search data
     *
     * @var \Magento\Search\Model\QueryFactory
     */
    protected $queryFactory = null;

    /**
     * Catalog search fulltext
     *
     * @var \Magento\CatalogSearch\Model\Fulltext
     */
    protected $_catalogSearchFulltext;

    /**
     * @var \Amasty\Shopby\Model\Request\Builder
     */
    protected $requestBuilder;

    /**
     * @var \Magento\Search\Model\SearchEngine
     */
    protected $searchEngine;

    /** @var string */
    protected $queryText;

    /** @var string|null */
    protected $order = null;

    /**
     * @var \Amasty\Shopby\Model\Request\Builder
     */
    public $_memRequestBuilder;

    protected $category_ids = [];

    /**
     * @var string
     */
    protected $searchRequestName;

    /**
     * @var \Magento\Framework\Search\Adapter\Mysql\TemporaryStorageFactory
     */
    protected $temporaryStorageFactory;

    /** @var \Amasty\Shopby\Model\Layer\Cms\Manager  */
    protected $cmsManager;

    /** @var Stock */
    protected $stockHelper;

    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Eav\Model\EntityFactory $eavEntityFactory,
        \Magento\Catalog\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Validator\UniversalFactory $universalFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Catalog\Model\Indexer\Product\Flat\State $catalogProductFlatState,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\Product\OptionFactory $productOptionFactory,
        \Magento\Catalog\Model\ResourceModel\Url $catalogUrl,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Customer\Api\GroupManagementInterface $groupManagement,
        \Magento\Search\Model\QueryFactory $catalogSearchData,
        \Magento\CatalogSearch\Model\Fulltext $catalogSearchFulltext,
        \Amasty\Shopby\Model\Request\Builder $requestBuilder,
        \Magento\Search\Model\SearchEngine $searchEngine,
        \Magento\Framework\Search\Adapter\Mysql\TemporaryStorageFactory $temporaryStorageFactory,
        \Amasty\Shopby\Model\Layer\Cms\Manager $cmsManager,
        Stock $stockHelper,
        $connection = null,
        $searchRequestName = 'catalog_view_container'
    ) {
        $this->_catalogSearchFulltext = $catalogSearchFulltext;
        $this->queryFactory = $catalogSearchData;
        $this->searchRequestName = $searchRequestName;
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $eavConfig,
            $resource,
            $eavEntityFactory,
            $resourceHelper,
            $universalFactory,
            $storeManager,
            $moduleManager,
            $catalogProductFlatState,
            $scopeConfig,
            $productOptionFactory,
            $catalogUrl,
            $localeDate,
            $customerSession,
            $dateTime,
            $groupManagement,
            $connection
        );
        $this->requestBuilder = $requestBuilder;
        $this->searchEngine = $searchEngine;
        $this->temporaryStorageFactory = $temporaryStorageFactory;
        $this->cmsManager = $cmsManager;
        $this->stockHelper = $stockHelper;
    }

    /**
     * Apply attribute filter to facet collection
     *
     * @param string $field
     * @param null $condition
     * @return $this
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if ($this->queryResponse !== null) {
            throw new \RuntimeException('Illegal state');
        }
        if (!is_array($condition) || (!in_array(key($condition), ['from', 'to'], true) && $field != 'visibility')) {
            $this->requestBuilder->bind($field, $condition);
        } else {
            if (!empty($condition['from'])) {
                $this->requestBuilder->bind("{$field}.from", $condition['from']);
            }
            if (!empty($condition['to'])) {
                $this->requestBuilder->bind("{$field}.to", $condition['to']);
            }
        }
        return $this;
    }

    /**
     * Add search query filter
     *
     * @param string $query
     * @return $this
     */
    public function addSearchFilter($query)
    {
        $this->queryText = trim($this->queryText . ' ' . $query);
        return $this;
    }

    /**
     * @inheritdoc
     */

    public function setRequestData($builder){
        $this->_select->reset();
        $this->requestBuilder = $builder;
        $this->queryResponse = null;
        $this->_isFiltersRendered = false;

    }

    public function getMemRequestBuilder()
    {
        if(is_null($this->_memRequestBuilder)) {
            $this->_memRequestBuilder = clone $this->requestBuilder;
            $this->_memRequestBuilder->bindDimension('scope', $this->getStoreId());
            if ($this->queryText) {
                $this->_memRequestBuilder->bind('search_term', $this->queryText);
            }

            $priceRangeCalculation = $this->_scopeConfig->getValue(
                \Magento\Catalog\Model\Layer\Filter\Dynamic\AlgorithmFactory::XML_PATH_RANGE_CALCULATION,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            if ($priceRangeCalculation) {
                $this->_memRequestBuilder->bind('price_dynamic_algorithm', $priceRangeCalculation);
            }

            $this->_memRequestBuilder->setRequestName($this->searchRequestName);
        }
        return $this->_memRequestBuilder;
    }

    protected function _renderFiltersBefore()
    {

        $this->requestBuilder->bindDimension('scope', $this->getStoreId());
        if ($this->queryText) {
            $this->requestBuilder->bind('search_term', $this->queryText);
        }

        $priceRangeCalculation = $this->_scopeConfig->getValue(
            \Magento\Catalog\Model\Layer\Filter\Dynamic\AlgorithmFactory::XML_PATH_RANGE_CALCULATION,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if ($priceRangeCalculation) {
            $this->requestBuilder->bind('price_dynamic_algorithm', $priceRangeCalculation);
        }

        $this->requestBuilder->setRequestName($this->searchRequestName);

        $this->_memRequestBuilder = clone $this->requestBuilder;
        $queryRequest = $this->requestBuilder->create();

        $this->queryResponse = $this->searchEngine->search($queryRequest);

        $temporaryStorage = $this->temporaryStorageFactory->create();
        $table = $temporaryStorage->storeDocuments($this->queryResponse->getIterator());

        $this->getSelect()->joinInner(
            [
                'search_result' => $table->getName(),
            ],
            'e.entity_id = search_result.' . TemporaryStorage::FIELD_ENTITY_ID,
            []
        );

        $this->cmsManager->setIndexStorageTable($table);

        $this->_totalRecords = $this->queryResponse->count();

        if ($this->order && 'relevance' === $this->order['field']) {
            $this->getSelect()->order('search_result.'. TemporaryStorage::FIELD_SCORE . ' ' . $this->order['dir']);
        }
        return parent::_renderFiltersBefore();
    }

    /**
     * @return $this
     */
    protected function _renderFilters()
    {
        $this->_filters = [];
        return parent::_renderFilters();
    }

    /**
     * Set Order field
     *
     * @param string $attribute
     * @param string $dir
     * @return $this
     */
    public function setOrder($attribute, $dir = Select::SQL_DESC)
    {
        $this->order = ['field' => $attribute, 'dir' => $dir];
        if ($attribute != 'relevance') {
            parent::setOrder($attribute, $dir);
        }
        return $this;
    }

    /**
     * Stub method for compatibility with other search engines
     *
     * @return $this
     */
    public function setGeneralDefaultQuery()
    {
        return $this;
    }

    /**
     * Return field faceted data from faceted search result
     *
     * @param string $field
     * @return array
     * @throws StateException
     */
    public function getFacetedData($field)
    {
        $this->_renderFilters();
        $aggregations = $this->queryResponse->getAggregations();
        $bucket = $aggregations->getBucket($field . '_bucket');
        if (!$bucket) {
            throw new StateException(new Phrase('Bucket does not exist'));
        }

        $result = [];
        foreach ($bucket->getValues() as $value) {
            $metrics = $value->getMetrics();
            $result[$metrics['value']] = $metrics;
        }
        return $result;
    }

    /**
     * Specify category filter for product collection
     *
     * @param \Magento\Catalog\Model\Category $category
     * @return $this
     */
    public function addCategoryFilter(\Magento\Catalog\Model\Category $category)
    {
        // code for multiselect category filter
        $this->category_ids[] = $category->getId();
        $this->addFieldToFilter(Category::ATTRIBUTE_CODE, $category->getId());
        return parent::addCategoryFilter($category);
    }

    /**
     * Set product visibility filter for enabled products
     *
     * @param array $visibility
     * @return $this
     */
    public function setVisibility($visibility)
    {
        $this->addFieldToFilter('visibility', $visibility);
        return parent::setVisibility($visibility);
    }

    protected function _beforeLoad()
    {
        $this->stockHelper->addIsInStockFilterToCollection($this);

        return parent::_beforeLoad();
    }

    /**
     * Add order by entity_id
     *
     * @return $this
     */
    protected function _renderOrders()
    {
        if (!$this->_isOrdersRendered) {
            parent::_renderOrders();
            $filters = $this->_productLimitationFilters;
            if (isset($filters['category_id']) || isset($filters['visibility'])) {
                $this->getSelect()->order("e.entity_id ASC");
            }
        }
        return $this;
    }
}
