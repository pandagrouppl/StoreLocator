<?php

namespace PandaGroup\Salesforce\Model\DataExtension;

class Products extends \PandaGroup\Salesforce\Model\DataExtension
{
    const DATA_EXTENSION_CUSTOMER_KEY = 'magento2_pandagroup_product';

    const DATA_EXTENSION_NAME = 'MAGENTO_2_PRODUCTS';

    /** @var \Magento\Catalog\Model\ProductRepository  */
    protected $productRepository;

    /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection  */
    protected $productCollection;

    /** @var \Magento\Catalog\Model\ProductFactory  */
    protected $productFactory;

    /** @var \Magento\Catalog\Model\Product  */
    protected $product;


    /**
     * Products constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \PandaGroup\Salesforce\Logger\Logger $logger
     * @param \PandaGroup\Salesforce\Model\Config $config
     * @param \PandaGroup\Salesforce\Model\Api\DataExtension $dataExtension
     * @param \PandaGroup\Salesforce\Model\Api\DataExtension\Row $dataExtensionRow
     * @param \PandaGroup\Salesforce\Model\FieldMapper $fieldMapper
     * @param \Magento\Catalog\Model\ProductRepository $productRepository
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Model\Product $product
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \PandaGroup\Salesforce\Logger\Logger $logger,
        \PandaGroup\Salesforce\Model\Config $config,
        \PandaGroup\Salesforce\Model\Api\DataExtension $dataExtension,
        \PandaGroup\Salesforce\Model\Api\DataExtension\Row $dataExtensionRow,
        \PandaGroup\Salesforce\Model\FieldMapper $fieldMapper,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\Product $product
    ) {
        $this->productRepository = $productRepository;
        $this->productCollection = $productCollection;
        $this->productFactory = $productFactory;
        $this->product = $product;
        parent::__construct($context, $registry, $logger, $config, $dataExtension, $dataExtensionRow, $fieldMapper);
    }

    public function createProductsDataExtension()
    {
        $dataExtensionPrefix = $this->config->getDataExtensionPrefix();
        $dataExtensionName = strtoupper($dataExtensionPrefix) . self::DATA_EXTENSION_NAME;

        $customerKey = $dataExtensionPrefix . self::DATA_EXTENSION_CUSTOMER_KEY;

        $columnsData = [
            ['Name' => 'Entity Id', 'FieldType' => 'Number', 'IsPrimaryKey' => 'true', 'IsRequired' => 'true'],
            ['Name' => 'Attribute Set Id', 'FieldType' => 'Number', 'IsRequired' => 'true', 'DefaultValue' => '0'],
            ['Name' => 'Type Id', 'FieldType' => 'Text', 'MaxLength' => '32', 'IsRequired' => 'true'],
            ['Name' => 'Sku', 'FieldType' => 'Text', 'MaxLength' => '64', 'DefaultValue' => ''],
            ['Name' => 'Has Options', 'FieldType' => 'Number', 'IsRequired' => 'true', 'DefaultValue' => '0'],
            ['Name' => 'Required Options', 'FieldType' => 'Number', 'IsRequired' => 'true', 'DefaultValue' => '0'],
            ['Name' => 'Is Salable', 'FieldType' => 'Number'],
            ['Name' => 'Created At', 'FieldType' => 'Date', 'IsRequired' => 'true', 'DefaultValue' => 'Now()'],
            ['Name' => 'Updated At', 'FieldType' => 'Date', 'IsRequired' => 'true', 'DefaultValue' => 'Now()'],
            ['Name' => 'Reg Price', 'FieldType' => 'Decimal', 'Precision' => '12', 'Scale' => '4'],
            ['Name' => 'Special Price', 'FieldType' => 'Decimal', 'Precision' => '12', 'Scale' => '4'],
            ['Name' => 'Product Url', 'FieldType' => 'Text', 'MaxLength' => '255'],

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

    public function getProductDetails($product)
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = [
            'entity_id' => $product->getId(),
            'attribute_set_id' => $product->getData('attribute_set_id'),
            'type_id' => $product->getTypeId(),
            'sku' => $product->getSku(),
            'has_options' => $product->getData('has_options'),
            'required_options' => $product->getData('required_options'),
            'is_salable' => $product->getData('is_salable'),
            'created_at' => $product->getCreatedAt(),
            'updated_at' => $product->getUpdatedAt(),
            'reg_price' => $product->getFinalPrice(1),
            'special_price' => $product->getSpecialPrice(),
            'product_url' => $product->getProductUrl()
        ];

        return $product;
    }

    public function syncProductsDataExtension()
    {
        $this->syncNewCreatedProducts();

        $this->syncNewUpdatedProducts();
    }

    protected function syncNewCreatedProducts()
    {
        $this->getProductDataToAdd();
    }

    protected function syncNewUpdatedProducts()
    {
        $this->getProductDataToUpdate();
    }

    protected function getProductDataToAdd()
    {
        $lastUploadEntityIdFromCreated = 0;

        $productCollection = $this->productCollection
            ->addFieldToFilter('entity_id', ['from' => $lastUploadEntityIdFromCreated + 1]);

        $productData = [];
        foreach ($productCollection as $product) {
            array_push($productData, $this->getProductDetails($product));
        }

        //var_dump($productData); exit;

        return $productData;
    }

    protected function getProductDataToUpdate()
    {
//        $fromDate = $this->config->getStartSyncFromDate();
//        $lastUploadEntityIdFromUpdated = $this->config->getLastUploadEntityIdFromUpdated();
//        $lastUploadEntityIdFromCreated = $this->config->getLastUploadEntityIdFromCreated();
//
//        $quoteCollection = $this->quote->getCollection()
//            ->setOrder('entity_id', \Magento\Framework\Data\Collection::SORT_ORDER_ASC);
//
//        if (null !== $fromDate and false === empty($fromDate)) {
//            $quoteCollection->addFieldToFilter('created_at', ['from' => $fromDate]);
//            $quoteCollection->addFieldToFilter('updated_at', ['from' => $fromDate]);    // bug in database
//        }
//
//        // Select newly updated
//        //if (null !== $lastUploadDate and false === empty($lastUploadDate)) {
//        $quoteCollection->addFieldToFilter('entity_id', ['from' => $lastUploadEntityIdFromUpdated + 1]);
//        $quoteCollection->addFieldToFilter('entity_id', ['to' => $lastUploadEntityIdFromCreated]);  // don't updating rows when don't exist yet
//        $quoteCollection->addFieldToFilter('updated_at', ['neq' => '0000-00-00 00:00:00']);     // not equal new carts
//        //}
//
//        return $quoteCollection;
    }

}
