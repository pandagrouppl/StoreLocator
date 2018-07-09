<?php

namespace PandaGroup\Salesforce\Model\Api\DataExtension;

class Row extends \PandaGroup\Salesforce\Model\Api\DataExtension
{
    /** @var \FuelSdk\ET_DataExtension_Row  */
    protected $dataExtensionRow;

    /**
     * Row constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \PandaGroup\Salesforce\Logger\Logger $logger
     * @param \PandaGroup\Salesforce\Model\Config $config
     * @param \PandaGroup\Salesforce\Model\Api\Client $client
     * @param \FuelSdk\ET_DataExtension $dataExtension
     * @param \FuelSdk\ET_DataExtension_Row $dataExtensionRow
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \PandaGroup\Salesforce\Logger\Logger $logger,
        \PandaGroup\Salesforce\Model\Config $config,
        \PandaGroup\Salesforce\Model\Api\Client $client,
        \FuelSdk\ET_DataExtension $dataExtension,
        \FuelSdk\ET_DataExtension_Row $dataExtensionRow
    ) {
        $this->dataExtensionRow = $dataExtensionRow;
        parent::__construct($context, $registry, $logger, $config, $client, $dataExtension);
    }

    /**
     * Add a row to a DataExtension
     *
     * @param $dataExtensionName
     * @param array $data
     * @return array|\FuelSdk\ET_DataExtension_Row|mixed|null|object
     */
    public function addRow($dataExtensionName, $data = [])
    {
        $postDRRow = $this->dataExtensionRow;
        $postDRRow->authStub = $this->client->getClient();
        $postDRRow->props = $data;
        $postDRRow->Name = $dataExtensionName;
        $postResult = $postDRRow->post();
        $postStatus = $this->checkResponseStatus('add_row_to_data_extension', $postResult);

        if (true === $postStatus) {
            return $postResult->results;
        }
        return null;
    }

    /**
     * Add a row by customer key to a DataExtension (Specify CustomerKey instead of Name)
     *
     * @param $customerKey
     * @param array $data
     * @return bool
     */
    public function addRowByCustomerKey($customerKey, $data = [])
    {
//        $data2 = [
//            'Entity Id' =>  '19578',
//            'Store Id' =>  '1',
//            'Created At' =>  '11/30/2017',
//            'Updated At' =>  '',
//            'Converted At' =>  '',
//            'Is Active' =>  '1',
//            'Is Virtual' =>  '0',
//            'Is Multi Shipping' =>  '0',
//            'Items Count' =>  '1',
//            'Items Qty' =>  '1.0000',
//            'Orig Order Id' =>  '0' ,
//            'Store To Base Rate' =>  '0.0000',
//            'Store To Quote Rate' =>  '0.0000',
//            'Base Currency Code' =>  'AUD',
//            'Store Currency Code' =>  'AUD',
//            'Quote Currency Code' =>  'AUD',
//            'Grand Total' =>  '399.0000',
//            'Base Grand Total' =>  '399.0000',
//            'Checkout Method' =>  '',
//            'Customer Id' =>  '',
//            'Customer Tax Class Id' =>  '3',
//            'Customer Group Id' =>  '0',
//            'Customer Email' =>  '',
//            'Customer Prefix' =>  '',
//            'Customer Firstname' =>  '',
//            'Customer Middlename' =>  '',
//            'Customer Lastname' =>  '',
//            'Customer Suffix' =>  '',
//            'Customer Dob' =>  '' ,
//            'Customer Note' =>  '',
//            'Customer Note Notify' =>  '1',
//            'Customer Is Guest' =>  '0',
//            'Remote Ip' =>  '172.17.0.1',
//            'Applied Rule Ids' =>  '',
//            'Reserved Order Id' =>  '',
//            'Password Hash' =>  '' ,
//            'Coupon Code' =>  '' ,
//            'Global Currency Code' =>  'AUD',
//            'Base To Global Rate' =>  '1.0000' ,
//            'Base To Quote Rate' =>  '1.0000' ,
//            'Customer Taxvat' =>  '',
//            'Customer Gender' =>  '',
//            'Subtotal' =>  '399.0000',
//            'Base Subtotal' =>  '399.0000',
//            'Subtotal With Discount' =>  '399.0000',
//            'Base Subtotal With Discount' =>  '399.0000',
//            'Is Changed' =>  '1',
//            'Trigger Recollect' =>  '0',
//            'Ext Shipping Info' =>  '',
//            'Is Persistent' =>  '0' ,
//            'Gift Message Id' => '',
//        ];

        $postDRRow = $this->dataExtensionRow;
        $postDRRow->authStub = $this->client->getClient();
        $postDRRow->props = $data;
        $postDRRow->CustomerKey = $customerKey;
        $postResult = $postDRRow->post();
        $postStatus = $this->checkResponseStatus('add_row_by_customer_key_to_data_extension', $postResult);

        if (true === $postStatus) {

            foreach ($postResult->results as $result) {
                $statusCode = $result->StatusCode;      // e.g. string 'OK' (length=2)
                $statusMessage = $result->StatusMessage;// e.g. string 'Data Extension created.' (length=23)

                $msg = 'Result status: ' . $statusCode . '. ' . $statusMessage;
                $this->logger->addInfo($msg);
            }

            return true;

        } else {
            foreach ($postResult->results as $result) {
                $statusCode = $result->StatusCode;      // e.g. string 'OK' (length=2)
                $statusMessage = $result->StatusMessage;// e.g. string 'Data Extension created.' (length=23)
                $errorMessage = $result->ErrorMessage;  // e.g. Cannot insert duplicate key in object 'C7280190.Carts - Magento2 Shop'.

                $msg = 'Result status: ' . $statusCode . '. ' . $statusMessage . '. Details: ' . $errorMessage;
                $this->logger->addError($msg);
            }
            return false;
        }
    }

    /**
     * @param $customerKey
     * @param array $data
     * @return array|\FuelSdk\ET_DataExtension_Row|mixed|null|object
     */
    public function getAllDataExtensionsRows($customerKey, $data = [])
    {
        $getDERows = $this->dataExtensionRow;
        $getDERows->authStub = $this->client->getClient();
        $getDERows->props = $data;
        $getDERows->CustomerKey = $customerKey;
        $getResult = $getDERows->get();

        $getStatus = $this->checkResponseStatus('get_all_data_extension_rows', $getResult);

        if (true === $getStatus) {
            return $getResult->results;
        }
        return null;
    }

    /**
     * Update a row in a DataExtension
     *
     * @param $customerKey
     * @param array $data
     * @return bool
     */
    public function updateRow($customerKey, $data = [])
    {
        $patchDRRow = $this->dataExtensionRow;
        $patchDRRow->authStub = $this->client->getClient();
        $patchDRRow->props = $data;
        $patchDRRow->CustomerKey = $customerKey;
        $patchResult = $patchDRRow->patch();

        $patchStatus = $this->checkResponseStatus('update_row_in_data_extension', $patchResult);

        if (true === $patchStatus) {

            foreach ($patchResult->results as $result) {
                $statusCode = $result->StatusCode;      // e.g. string 'OK' (length=2)
                $statusMessage = $result->StatusMessage;// e.g. string 'Data Extension created.' (length=23)

                $msg = 'Result status: ' . $statusCode . '. ' . $statusMessage;
                $this->logger->addInfo($msg);
            }

            return true;

        } else {
            foreach ($patchResult->results as $result) {
                $statusCode = $result->StatusCode;      // e.g. string 'OK' (length=2)
                $statusMessage = $result->StatusMessage;// e.g. string 'Data Extension created.' (length=23)
                $errorMessage = $result->ErrorMessage;  // e.g. Cannot insert duplicate key in object 'C7280190.Carts - Magento2 Shop'.

                $msg = 'Result status: ' . $statusCode . '. ' . $statusMessage . '. Details: ' . $errorMessage;
                $this->logger->addError($msg);
            }
            return false;
        }
    }

    /**
     * Get rows from Data Extension using filter
     *
     * @param $customerKey
     * @param $data
     * @param $key
     * @param $value
     * @param string $operator
     * @return array
     */
    public function getRowByFilter($customerKey, $data, $key, $value, $operator = 'equals')
    {
        $getDERows = $this->dataExtensionRow;
        $getDERows->authStub = $this->client->getClient();
        $getDERows->props = $data;
        $getDERows->CustomerKey = $customerKey;
        $getDERows->filter = ['Property' => $key, 'SimpleOperator' => $operator, 'Value' => $value];
        $getResult = $getDERows->get();

        $getStatus = $this->checkResponseStatus('get_row_from_data_extension_using_filter', $getResult);

        $data = [];
        if (true === $getStatus) {

            foreach ($getResult->results as $result) {

                $data = $result->Properties->Property;

                $statusCode = 'OK';
                $statusMessage = 'Get DataExtensionObject';
                $msg = 'Result status: ' . $statusCode . '. ' . $statusMessage;
                $this->logger->addInfo($msg);
            }

        } else {
            foreach ($getResult->results as $result) {
                $statusCode = $result->StatusCode;      // e.g. string 'OK' (length=2)
                $statusMessage = $result->StatusMessage;// e.g. string 'Data Extension created.' (length=23)
                $errorMessage = $result->ErrorMessage;  // e.g. Cannot insert duplicate key in object 'C7280190.Carts - Magento2 Shop'.

                $msg = 'Result status: ' . $statusCode . '. ' . $statusMessage . '. Details: ' . $errorMessage;
                $this->logger->addError($msg);
            }
        }
        return $data;
    }

    /**
     * Delete a row from a DataExtension
     *
     * @param $customerKey
     * @param $data
     * @return array|\FuelSdk\ET_DataExtension_Row|mixed|null|object
     */
    public function deleteRow($customerKey, $data)
    {
        $deleteDRRow = $this->dataExtensionRow;
        $deleteDRRow->authStub = $this->client->getClient();
        $deleteDRRow->props = $data;
        $deleteDRRow->CustomerKey = $customerKey;
        $deleteResult = $deleteDRRow->delete();

        $deleteStatus = $this->checkResponseStatus('delete_row_from_data_extension', $deleteResult);

        if (true === $deleteStatus) {
            return $deleteResult->results;
        }
        return null;
    }
}
