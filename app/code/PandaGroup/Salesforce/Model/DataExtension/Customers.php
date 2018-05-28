<?php

namespace PandaGroup\Salesforce\Model\DataExtension;

class Customers extends \PandaGroup\Salesforce\Model\DataExtension
{
    /**
     * Customers constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \PandaGroup\Salesforce\Logger\Logger $logger
     * @param \PandaGroup\Salesforce\Model\Config $config
     * @param \PandaGroup\Salesforce\Model\Api\DataExtension $dataExtension
     * @param \PandaGroup\Salesforce\Model\Api\DataExtension\Row $dataExtensionRow
     * @param \PandaGroup\Salesforce\Model\FieldMapper $fieldMapper
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
        parent::__construct($context, $registry, $logger, $config, $dataExtension, $dataExtensionRow, $fieldMapper);
    }

    public function createCustomersDataExtension()
    {
        $dataExtensionPrefix = $this->config->getDataExtensionPrefix();
        $dataExtensionName = 'Customers - Magento2 Shop';
        $customerKey = $dataExtensionPrefix . 'magento2_pandagroup_customers';

        $columnsData = [
            ['Name' => 'Customer Id', 'FieldType' => 'Text', 'IsPrimaryKey' => 'true', 'MaxLength' => '100', 'IsRequired' => 'true'],
            ['Name' => 'Email', 'FieldType' => 'Text']
        ];
        $this->dataExtension->createDataExtension($dataExtensionName, $customerKey, $columnsData);
    }


}
