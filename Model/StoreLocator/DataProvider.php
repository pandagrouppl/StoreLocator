<?php

namespace PandaGroup\StoreLocator\Model\StoreLocator;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var array
     */
    protected $loadedData;

    /**
     * DataProvider constructor.
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param \PandaGroup\StoreLocator\Model\ResourceModel\StoreLocator\CollectionFactory $faqCollectionFactory
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \PandaGroup\StoreLocator\Model\ResourceModel\StoreLocator\CollectionFactory $faqCollectionFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection    = $faqCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        /** @var \PandaGroup\StoreLocator\Model\StoreLocator $store */
        foreach ($items as $store) {
            $this->loadedData[$store->getId()] = $store->getData();
        }

        $data = $this->dataPersistor->get('storelocator');
        if (!empty($data)) {
            $store = $this->collection->getNewEmptyItem();
            $store->setData($data);
            $this->loadedData[$store->getId()] = $store->getData();
            $this->dataPersistor->clear('storelocator');
        }

        return $this->loadedData;
    }
}