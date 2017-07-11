<?php

namespace PandaGroup\StoreLocator\Model\States;

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
     * @param \PandaGroup\StoreLocator\Model\ResourceModel\States\CollectionFactory $statesCollectionFactory
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \PandaGroup\StoreLocator\Model\ResourceModel\States\CollectionFactory $statesCollectionFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection    = $statesCollectionFactory->create();
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
        /** @var \PandaGroup\StoreLocator\Model\States $state */
        foreach ($items as $state) {
            $this->loadedData[$state->getId()] = $state->getData();
        }

        $data = $this->dataPersistor->get('states_data');
        if (!empty($data)) {
            $state = $this->collection->getNewEmptyItem();
            $state->setData($data);
            $this->loadedData[$state->getId()] = $state->getData();
            $this->dataPersistor->clear('states_data');
        }

        return $this->loadedData;
    }
}