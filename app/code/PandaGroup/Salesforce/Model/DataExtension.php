<?php

namespace PandaGroup\Salesforce\Model;

class DataExtension extends \Magento\Framework\Model\AbstractModel
{
    /** @var \PandaGroup\Salesforce\Logger\Logger */
    protected $logger;

    /** @var \PandaGroup\Salesforce\Model\Config */
    protected $config;

    /** @var \PandaGroup\Salesforce\Model\Api\DataExtension  */
    protected $dataExtension;

    /** @var \PandaGroup\Salesforce\Model\Api\DataExtension\Row  */
    protected $dataExtensionRow;

    /** @var \PandaGroup\Salesforce\Model\FieldMapper  */
    protected $fieldMapper;


    /**
     * DataExtension constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \PandaGroup\Salesforce\Logger\Logger $logger
     * @param \PandaGroup\Salesforce\Model\Config $config
     * @param \PandaGroup\Salesforce\Model\Api\DataExtension $dataExtension
     * @param \PandaGroup\Salesforce\Model\Api\DataExtension\Row $dataExtensionRow
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \PandaGroup\Salesforce\Logger\Logger $logger,
        \PandaGroup\Salesforce\Model\Config $config,
        \PandaGroup\Salesforce\Model\Api\DataExtension $dataExtension,
        \PandaGroup\Salesforce\Model\Api\DataExtension\Row $dataExtensionRow,
        \PandaGroup\Salesforce\Model\FieldMapper $fieldMapper
    ) {
        parent::__construct($context, $registry);
        $this->logger = $logger;
        $this->config = $config;
        $this->dataExtension = $dataExtension;
        $this->dataExtensionRow = $dataExtensionRow;
        $this->fieldMapper = $fieldMapper;
    }

    /**
     * Create all of the schema - Salesforce Marketing Cloud structure
     *
     * @return bool
     */
    public function createDataExtensions()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        /** @var \PandaGroup\Salesforce\Model\DataExtension\Carts $cartsDataExtension */
        $cartsDataExtension = $objectManager->create('PandaGroup\Salesforce\Model\DataExtension\Carts');
        $status = $cartsDataExtension->createCartsDataExtension();

        return $status;
    }

    /**
     * Synchronize data on Salesforce
     */
    public function syncDataExtensions()
    {
        if (true === $this->config->getEnableStatus()) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            /** @var \PandaGroup\Salesforce\Model\DataExtension\Carts $cartsDataExtension */
            $cartsDataExtension = $objectManager->create('PandaGroup\Salesforce\Model\DataExtension\Carts');
            $cartsDataExtension->syncCartsDataExtension();
            $this->config->setLastUploadDate(date('d-m-Y H:i:s'));
        }
    }
}
