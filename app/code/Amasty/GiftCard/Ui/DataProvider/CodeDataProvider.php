<?php

namespace Amasty\GiftCard\Ui\DataProvider;

use Amasty\GiftCard\Model\ResourceModel\CodeSet\CollectionFactory;

class CodeDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collectionInitialized = false;

    /**
     * Construct
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }

    public function getCollection()
    {
        $collection = parent::getCollection();

        if (!$this->collectionInitialized){
            $collection->joinCodeQtyAndUnused();

            $this->collectionInitialized = true;
        }

        return $collection;
    }
}