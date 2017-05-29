<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Model\Layer\Filter\OnSale;

use Magento\CatalogRule\Pricing\Price\CatalogRulePrice;

class Helper
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resource;

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $dateTime;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $localeDate;

    /**
     * @param \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable\Product\CollectionFactory $configurableCollectionFactory
     */
    function __construct(
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable\Product\CollectionFactory $configurableCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
    )
    {
        $this->configurableCollectionFactory = $configurableCollectionFactory;
        $this->storeManager = $storeManager;
        $this->resource = $resourceConnection;
        $this->customerSession = $customerSession;
        $this->dateTime = $dateTime;
        $this->localeDate = $localeDate;
    }

    public function addOnSaleFilter($collection)
    {
        $configurableCollection = $this->configurableCollectionFactory->create();
        $configurableCollection->getSelect()->group('e.entity_id');
        $configurableCollection->load();

        if (!$configurableCollection->hasFlag('catalog_rule_loaded')){
            $this->_loadCatalogRule($configurableCollection);
        }

        $collection->addPriceData();
        $collection->getSelect()->joinLeft(
            ['configurable' => $configurableCollection->getSelect()],
            'e.entity_id in (configurable.entity_id, configurable.parent_id)',
            ['catalog_rule_price']
        );

        $collection->getSelect()->joinLeft(
            ['relation' => $collection->getTable('catalog_product_relation')],
            'relation.child_id = e.entity_id',
            ['parent_id' => 'relation.parent_id']
        );


        $collection->getSelect()->where('ifnull(configurable.catalog_rule_price, price_index.final_price) < price_index.price');
        $collection->getSelect()->group('e.entity_id');
    }

    protected function _loadCatalogRule($productCollection)
    {
        if (!$productCollection->hasFlag('catalog_rule_loaded')) {
            $connection = $this->resource->getConnection();
            $store = $this->storeManager->getStore();
            $productCollection->getSelect()
                ->joinLeft(
                    ['catalog_rule' => $this->resource->getTableName('catalogrule_product_price')],
                    implode(' AND ', [
                        'catalog_rule.product_id = e.entity_id',
                        $connection->quoteInto('catalog_rule.website_id = ?', $store->getWebsiteId()),
                        $connection->quoteInto(
                            'catalog_rule.customer_group_id = ?',
                            $this->customerSession->getCustomerGroupId()
                        ),
                        $connection->quoteInto(
                            'catalog_rule.rule_date = ?',
                            $this->dateTime->formatDate($this->localeDate->scopeDate($store->getId()), false)
                        ),
                    ]),
                    [CatalogRulePrice::PRICE_CODE => 'rule_price']
                );
            $productCollection->setFlag('catalog_rule_loaded', true);
        }
    }
}