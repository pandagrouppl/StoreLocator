<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Model\Layer\Cms;

use Magento\Framework\Search\Adapter\Mysql\TemporaryStorage;

class Manager
{
    /** @var \Magento\Framework\View\Layout  */
    protected $layout;

    /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection  */
    protected $cmsCollection;

    /** @var  \Magento\Framework\DB\Ddl\Table */
    protected $table;

    protected $isIndexStorageApplied = false;

    public function __construct(
        \Magento\Framework\View\LayoutInterface $layout,
        array $data = []
    ){
        $this->layout = $layout;
    }

    public function init()
    {
        foreach ($this->layout->getAllBlocks() as $block) {
            if ($block->getProductCollection() instanceof \Magento\Catalog\Model\ResourceModel\Product\Collection) {
                /** @var  \Magento\CatalogWidget\Block\Product\ProductsList $block  */

                $collection = $block->getProductCollection();
                $this->cmsCollection = $collection;
                break;
            }
        }

    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     */
    public function setCmsCollection(\Magento\Catalog\Model\ResourceModel\Product\Collection $collection)
    {
        $this->cmsCollection = $collection;
    }


    /**
     * @return bool
     */
    public function isCmsPageNavigation()
    {
        return $this->cmsCollection !== null;
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getCmsCollection()
    {
        return $this->cmsCollection;
    }

    /**
     * @param $select
     */
    public function addCmsPageDataToSelect($select)
    {
        $cmsSelect = clone $this->cmsCollection->getSelect();

        $cmsSelect->limit(null);

        $select->joinInner(
            ['blockEntities' => $cmsSelect],
            'search_index.entity_id  = blockEntities.entity_id',
            []
        );
    }

    public function setIndexStorageTable(\Magento\Framework\DB\Ddl\Table $table)
    {
        $this->table = $table;
        return $this;
    }

    public function applyIndexStorage(\Magento\Catalog\Model\ResourceModel\Product\Collection $collection)
    {
        if ($this->table && !$this->isIndexStorageApplied) {

            $collection->clear();
            $collection->getSelect()->joinInner(
                [
                    'search_result' => $this->table->getName(),
                ],
                'e.entity_id = search_result.' . TemporaryStorage::FIELD_ENTITY_ID,
                []
            );

            $this->isIndexStorageApplied = true;
        }
    }
}