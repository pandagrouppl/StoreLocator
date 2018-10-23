<?php
namespace WeltPixel\Backend\Model\ResourceModel\Debugger\Grid;

use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Search\AggregationInterface;

/**
 * Class Collection
 * @package WeltPixel\Bakcend\Model\ResourceModel\Debugger\Grid
 */
class Collection extends \Magento\Framework\Data\Collection implements SearchResultInterface
{
    /**
     * @var AggregationInterface
     */
    protected $aggregations;

    /**
     * @var \WeltPixel\Backend\Model\Scanner
     */
    protected $scanner;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $backendSession;


    /**
     * @param \Magento\Backend\Model\Session $backendSession
     * @param \WeltPixel\Backend\Model\Scanner $scanner
     * @param \Magento\Framework\DataObjectFactory $dataObjectFactory
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param string $mainTable
     * @param string $eventPrefix
     * @param string $eventObject
     * @param string $resourceModel
     * @param string $model
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Model\Session $backendSession,
        \WeltPixel\Backend\Model\Scanner $scanner,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        $mainTable,
        $eventPrefix,
        $eventObject,
        $resourceModel,
        $model = 'Magento\Framework\View\Element\UiComponent\DataProvider\Document'
    )
    {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            null,
            null
        );

        $this->_eventPrefix = $eventPrefix;
        $this->_eventObject = $eventObject;
        $this->_dataObjectFactory = $dataObjectFactory;
        $this->scanner = $scanner;
        $this->backendSession = $backendSession;
    }

    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    protected $_dataObjectFactory;

    /**
     * @return AggregationInterface
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * @param AggregationInterface $aggregations
     * @return $this
     */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
    }

    /**
     * Retrieve all ids for collection
     * Backward compatibility with EAV collection
     *
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getAllIds($limit = null, $offset = null)
    {
        return $this->getConnection()->fetchCol($this->_getAllIdsSelect($limit, $offset), $this->_bindParams);
    }

    /**
     * Get search criteria.
     *
     * @return \Magento\Framework\Api\SearchCriteriaInterface|null
     */
    public function getSearchCriteria()
    {
        return null;
    }

    /**
     * Set search criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null)
    {
        return $this;
    }

    /**
     * Get total count.
     *
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }

    /**
     * Set total count.
     *
     * @param int $totalCount
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setTotalCount($totalCount)
    {
        return $this;
    }

    /**
     * Set items list.
     *
     * @param \Magento\Framework\Api\ExtensibleDataInterface[] $items
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setItems(array $items = null)
    {
        return $this;
    }

    /**
     * @return \Magento\Framework\DataObject[]
     */
    public function getItems()
    {
        $this->_populateCollection();
        return parent::getItems();
    }

    public function _populateCollection()
    {
        $rewritesOption = $this->backendSession->getDebuggerRewite(false);
        $rewrites = $this->scanner->getRewrites($rewritesOption);

        $searchKey = str_replace("\\\\\\\\", "\\", $this->scanner->getData('filterCondition'));

        if ($searchKey) {
            foreach ($rewrites as $key => $value) {
                if (strpos(strtolower($key), strtolower($searchKey)) === false) {
                    unset($rewrites[$key]);
                }
            }
        }

        foreach ($rewrites as $originalClass => $rewriteOptions) {
            foreach ($rewriteOptions as $areaCode => $rewriteClasses) {
                if (count($rewriteClasses) > 1) {
                    $status = false;
                } else {
                    $status = true;
                }

                /**
                 * Exceptions
                 * Magento\Catalog\Block\Product\ImageBuilder class we rewrite in LazyLoading, OwlCarousel modules as well
                 * these modules are sold as standalone ones, so we rewrite the class also in CategoryPage module
                 * where we merge the functionalities from those 2 modules, in this case this rewrite is marked as Ok,
                 */
                if (($originalClass == 'Magento\Catalog\Block\Product\ImageBuilder') &&
                    ($areaCode == 'frontend') &&
                    (count($rewriteClasses) == 3)
                ) {
                    $status = true;
                }

                foreach ($rewriteClasses as $key => $rewriteClass) {
                    $item = $this->_dataObjectFactory->create();
                    if ($key) {
                        $item->setArea('');
                        $item->setOriginalClass('');
                    } else {
                        $item->setArea($areaCode);
                        $item->setOriginalClass($originalClass);
                    }
                    $item->setRewriteClass($rewriteClass);
                    $item->setStatus($status);
                    $this->addItem($item);
                }
            }
        }
    }

    /**
     * Filter applied only on the original_class column
     *
     * @param array|string $field
     * @param array|int|string $condition
     * @return $this
     */
    public function addFieldToFilter($field, $condition)
    {
        $conditionValue = substr($condition['like'], 2, -2);

        $this->scanner->setData('filterCondition', $conditionValue);

        return $this;
    }
}
