<?php

namespace PandaGroup\Salesforce\Model\DataExtension;

class Carts extends \PandaGroup\Salesforce\Model\DataExtension
{
    //const DATA_EXTENSION_CUSTOMER_KEY = 'magento2_pandagroup_test1';
    const DATA_EXTENSION_CUSTOMER_KEY = 'magento2_pandagroup_quote';

    const DATA_EXTENSION_NAME = 'MAGENTO_2_CARTS';

    /** @var \Magento\Quote\Model\Quote  */
    protected $quote;


    /**
     * Carts constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \PandaGroup\Salesforce\Logger\Logger $logger
     * @param \PandaGroup\Salesforce\Model\Config $config
     * @param \PandaGroup\Salesforce\Model\Api\DataExtension $dataExtension
     * @param \PandaGroup\Salesforce\Model\Api\DataExtension\Row $dataExtensionRow
     * @param \PandaGroup\Salesforce\Model\FieldMapper $fieldMapper
     * @param \Magento\Quote\Model\Quote $quote
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \PandaGroup\Salesforce\Logger\Logger $logger,
        \PandaGroup\Salesforce\Model\Config $config,
        \PandaGroup\Salesforce\Model\Api\DataExtension $dataExtension,
        \PandaGroup\Salesforce\Model\Api\DataExtension\Row $dataExtensionRow,
        \PandaGroup\Salesforce\Model\FieldMapper $fieldMapper,
        \Magento\Quote\Model\Quote $quote
    ) {
        $this->quote = $quote;
        parent::__construct($context, $registry, $logger, $config, $dataExtension, $dataExtensionRow, $fieldMapper);
    }

    public function createCartsDataExtension()
    {
        $dataExtensionPrefix = $this->config->getDataExtensionPrefix();
        $dataExtensionName = strtoupper($dataExtensionPrefix) . self::DATA_EXTENSION_NAME;

        $customerKey = $dataExtensionPrefix . self::DATA_EXTENSION_CUSTOMER_KEY;

        $columnsData = [
            ['Name' => 'Entity Id', 'FieldType' => 'Number', 'IsPrimaryKey' => 'true', 'IsRequired' => 'true'],
            ['Name' => 'Store Id', 'FieldType' => 'Number', 'IsRequired' => 'true', 'DefaultValue' => '0'],
            ['Name' => 'Created At', 'FieldType' => 'Date', 'IsRequired' => 'true', 'DefaultValue' => 'Now()'],
            ['Name' => 'Updated At', 'FieldType' => 'Date'],
            ['Name' => 'Converted At', 'FieldType' => 'Date'],
            ['Name' => 'Is Active', 'FieldType' => 'Number', 'DefaultValue' => '1'],
            ['Name' => 'Is Virtual', 'FieldType' => 'Number', 'DefaultValue' => '0'],
            ['Name' => 'Is Multi Shipping', 'FieldType' => 'Number', 'DefaultValue' => '0'],
            ['Name' => 'Items Qty', 'FieldType' => 'Decimal', 'Precision' => '12', 'Scale' => '4', 'DefaultValue' => '0.0000'],
            ['Name' => 'Orig Order Id', 'FieldType' => 'Number', 'DefaultValue' => '0'],
            ['Name' => 'Store To Base Rate', 'FieldType' => 'Decimal', 'Precision' => '12', 'Scale' => '4', 'DefaultValue' => '0.0000'],
            ['Name' => 'Store To Quote Rate', 'FieldType' => 'Decimal', 'Precision' => '12', 'Scale' => '4', 'DefaultValue' => '0.0000'],
            ['Name' => 'Base Currency Code', 'FieldType' => 'Text', 'MaxLength' => '255'],
            ['Name' => 'Store Currency Code', 'FieldType' => 'Text', 'MaxLength' => '255'],
            ['Name' => 'Quote Currency Code', 'FieldType' => 'Text', 'MaxLength' => '255'],
            ['Name' => 'Grand Total', 'FieldType' => 'Decimal', 'Precision' => '12', 'Scale' => '4', 'DefaultValue' => '0.0000'],
            ['Name' => 'Base Grand Total', 'FieldType' => 'Decimal', 'Precision' => '12', 'Scale' => '4', 'DefaultValue' => '0.0000'],
            ['Name' => 'Checkout Method', 'FieldType' => 'Text', 'MaxLength' => '255'],
            ['Name' => 'Customer Id', 'FieldType' => 'Number'],
            ['Name' => 'Customer Tax Class Id', 'FieldType' => 'Number'],
            ['Name' => 'Customer Group Id', 'FieldType' => 'Number', 'DefaultValue' => '0'],
            ['Name' => 'Customer Email', 'FieldType' => 'EmailAddress', 'MaxLength' => '254'],
            ['Name' => 'Customer Prefix', 'FieldType' => 'Text', 'MaxLength' => '40'],
            ['Name' => 'Customer Firstname', 'FieldType' => 'Text', 'MaxLength' => '255'],
            ['Name' => 'Customer Middlename', 'FieldType' => 'Text', 'MaxLength' => '40'],
            ['Name' => 'Customer Lastname', 'FieldType' => 'Text', 'MaxLength' => '255'],
            ['Name' => 'Customer Suffix', 'FieldType' => 'Text', 'MaxLength' => '40'],
            ['Name' => 'Customer Dob', 'FieldType' => 'Date'],
            ['Name' => 'Customer Note', 'FieldType' => 'Text', 'MaxLength' => '255'],
            ['Name' => 'Customer Note Notify', 'FieldType' => 'Number', 'DefaultValue' => '1'],
            ['Name' => 'Customer Is Guest', 'FieldType' => 'Number', 'DefaultValue' => '0'],
            ['Name' => 'Remote Ip', 'FieldType' => 'Text', 'MaxLength' => '32'],
            ['Name' => 'Applied Rule Ids', 'FieldType' => 'Text', 'MaxLength' => '255'],
            ['Name' => 'Reserved Order Id', 'FieldType' => 'Text', 'MaxLength' => '64'],
            ['Name' => 'Coupon Code', 'FieldType' => 'Text', 'MaxLength' => '255'],
            ['Name' => 'Global Currency Code', 'FieldType' => 'Text', 'MaxLength' => '255'],
            ['Name' => 'Base To Global Rate', 'FieldType' => 'Decimal', 'Precision' => '12', 'Scale' => '4'],
            ['Name' => 'Base To Quote Rate', 'FieldType' => 'Decimal', 'Precision' => '12', 'Scale' => '4'],
            ['Name' => 'Customer Taxvat', 'FieldType' => 'Text', 'MaxLength' => '255'],
            ['Name' => 'Customer Gender', 'FieldType' => 'Text', 'MaxLength' => '255'],
            ['Name' => 'Subtotal', 'FieldType' => 'Decimal', 'Precision' => '12', 'Scale' => '4'],
            ['Name' => 'Base Subtotal', 'FieldType' => 'Decimal', 'Precision' => '12', 'Scale' => '4'],
            ['Name' => 'Subtotal With Discount', 'FieldType' => 'Decimal', 'Precision' => '12', 'Scale' => '4'],
            ['Name' => 'Base Subtotal With Discount', 'FieldType' => 'Decimal', 'Precision' => '12', 'Scale' => '4'],
            ['Name' => 'Is Changed', 'FieldType' => 'Number'],
            ['Name' => 'Trigger Recollect', 'FieldType' => 'Number', 'IsRequired' => 'true', 'DefaultValue' => '0'],
            ['Name' => 'Ext Shipping Info', 'FieldType' => 'Text', 'MaxLength' => '255'],
            ['Name' => 'Is Persistent', 'FieldType' => 'Number', 'DefaultValue' => '0'],
            ['Name' => 'Gift Message Id', 'FieldType' => 'Number']
        ];

        $newDataExtensionCustomerKey = $this->dataExtension->createDataExtension($dataExtensionName, $customerKey, $columnsData);

        if (null === $newDataExtensionCustomerKey) {
            $this->logger->addError('Exception occurred during create cart data extension.');
            return false;
        } else {
            $this->logger->addInfo('Data Extension \''. $dataExtensionName . '\' created.');
            return true;
        }
    }

    public function syncCartsDataExtension()
    {
        $this->syncNewCreatedCarts();

        $this->syncNewUpdatedCarts();
    }

    protected function syncNewCreatedCarts()
    {
        $quoteCollectionToAdd = $this->getCartCollectionToAdd();

        $quoteCollectionCount = $quoteCollectionToAdd->count();

        if ($quoteCollectionCount === 0) {
            $msg = 'Skip synchronisation because of ' . $quoteCollectionCount . ' newly created carts.';
            $this->logger->addInfo($msg);
            return;
        }

        $msg = 'Start synchronisation of ' . $quoteCollectionCount . ' newly created carts.';
        $this->logger->addInfo($msg);

        $dataExtensionPrefix = $this->config->getDataExtensionPrefix();
        $customerKey = $dataExtensionPrefix . self::DATA_EXTENSION_CUSTOMER_KEY;
        $maxQtyPerSync = $this->config->getMaxRowQtyPerSync();
        $quoteQty = 0;

        foreach ($quoteCollectionToAdd as $quote) {
            $mappedData = $this->fieldMapper->mapArrayFields($quote->getData());

            $status = $this->dataExtensionRow->addRowByCustomerKey($customerKey, $mappedData);

            if (true === $status) {
                // Update last_upload_entity_id_from_created
                $this->config->setLastUploadEntityIdFromCreated($quote->getData('entity_id'));
            } else {
                $this->config->setLastUploadStatus('Failed');
                $msg = 'Synchronisation was stopped because of unsuccessful upload of entity';
                $this->logger->addError($msg);
                return;
            }

            $quoteQty++;
            if ($quoteQty === $maxQtyPerSync) break;
            // 35 - 45 rekordów na minute
        }

        $this->config->setLastUploadStatus('Completed');
        $msg = 'Synchronisation completed';
        $this->logger->addInfo($msg);
    }

    protected function syncNewUpdatedCarts()
    {
        $quoteCollectionToAdd = $this->getCartCollectionToUpdate();

        $quoteCollectionCount = $quoteCollectionToAdd->count();

        if ($quoteCollectionCount === 0) {
            $msg = 'Skip synchronisation because of ' . $quoteCollectionCount . ' newly updated carts.';
            $this->logger->addInfo($msg);
            return;
        }

        $msg = 'Start synchronisation of ' . $quoteCollectionCount . ' newly updated carts.';
        $this->logger->addInfo($msg);

        $dataExtensionPrefix = $this->config->getDataExtensionPrefix();
        $customerKey = $dataExtensionPrefix . self::DATA_EXTENSION_CUSTOMER_KEY;
        $maxQtyPerSync = $this->config->getMaxRowQtyPerSync();
        $quoteQty = 0;

        foreach ($quoteCollectionToAdd as $quote) {
            $quoteQty++;
            if ($quoteQty === $maxQtyPerSync) break;

            $row = $this->dataExtensionRow->getRowByFilter($customerKey, ['Entity Id'], 'Entity Id', $quote['entity_id']);

            if (true === empty($row)) {
                $msg = 'Row don\'t exist yet';
                $this->logger->addInfo($msg);
                continue;
            }

            $mappedData = $this->fieldMapper->mapArrayFields($quote->getData());

            $status = $this->dataExtensionRow->updateRow($customerKey, $mappedData);

            if (true === $status) {
                // Update last_upload_entity_id_from_created
                $this->config->setLastUploadEntityIdFromUpdated($quote->getData('entity_id'));
            } else {
                $this->config->setLastUploadStatus('Failed');
                $msg = 'Synchronisation was stopped because of unsuccessful upload of entity';
                $this->logger->addError($msg);
                return;
            }
        }

        $this->config->setLastUploadStatus('Completed');
        $msg = 'Synchronisation completed';
        $this->logger->addInfo($msg);
    }


    protected function getCartCollectionToAdd()
    {
        $fromDate = $this->config->getStartSyncFromDate();

        $lastUploadEntityIdFromCreated = $this->config->getLastUploadEntityIdFromCreated();

        $quoteCollection = $this->quote->getCollection()
            ->setOrder('entity_id', \Magento\Framework\Data\Collection::SORT_ORDER_ASC);

        if (null !== $fromDate and false === empty($fromDate)) {
            $quoteCollection->addFieldToFilter('created_at', ['from' => $fromDate]);
        }

        // Select newly created
        $quoteCollection->addFieldToFilter('entity_id', ['from' => $lastUploadEntityIdFromCreated + 1]);

        return $quoteCollection;
    }

    protected function getCartCollectionToUpdate()
    {
        $fromDate = $this->config->getStartSyncFromDate();
        $lastUploadEntityIdFromUpdated = $this->config->getLastUploadEntityIdFromUpdated();
        $lastUploadEntityIdFromCreated = $this->config->getLastUploadEntityIdFromCreated();

        $quoteCollection = $this->quote->getCollection()
            ->setOrder('entity_id', \Magento\Framework\Data\Collection::SORT_ORDER_ASC);

        if (null !== $fromDate and false === empty($fromDate)) {
            $quoteCollection->addFieldToFilter('created_at', ['from' => $fromDate]);
            $quoteCollection->addFieldToFilter('updated_at', ['from' => $fromDate]);    // bug in database
        }

        // Select newly updated
        //if (null !== $lastUploadDate and false === empty($lastUploadDate)) {
            $quoteCollection->addFieldToFilter('entity_id', ['from' => $lastUploadEntityIdFromUpdated + 1]);
            $quoteCollection->addFieldToFilter('entity_id', ['to' => $lastUploadEntityIdFromCreated]);  // don't updating rows when don't exist yet
            $quoteCollection->addFieldToFilter('updated_at', ['neq' => '0000-00-00 00:00:00']);     // not equal new carts
        //}

        return $quoteCollection;
    }
}
