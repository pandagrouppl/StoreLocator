<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */


namespace Amasty\GiftCard\Ui\DataProvider;

use Amasty\GiftCard\Model\ResourceModel\Account\CollectionFactory;

class AccountDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
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
            $collection->joinOrder();
            $collection->joinCode();

            $this->collectionInitialized = true;
        }

        return $collection;
    }
}