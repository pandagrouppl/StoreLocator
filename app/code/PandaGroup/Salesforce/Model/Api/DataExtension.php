<?php

namespace PandaGroup\Salesforce\Model\Api;

class DataExtension extends \Magento\Framework\Model\AbstractModel
{
    /** @var \PandaGroup\Salesforce\Logger\Logger */
    protected $logger;

    /** @var \PandaGroup\Salesforce\Model\Config */
    protected $config;

    /** @var \PandaGroup\Salesforce\Model\Api\Client */
    protected $client;

    /** @var \FuelSdk\ET_DataExtension */
    protected $dataExtension;


    /**
     * Client constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \PandaGroup\Salesforce\Logger\Logger $logger
     * @param \PandaGroup\Salesforce\Model\Config $config
     * @param \PandaGroup\Salesforce\Model\Api\Client $client
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \PandaGroup\Salesforce\Logger\Logger $logger,
        \PandaGroup\Salesforce\Model\Config $config,
        \PandaGroup\Salesforce\Model\Api\Client $client,
        \FuelSdk\ET_DataExtension $dataExtension
    ) {
        parent::__construct($context, $registry);
        $this->logger = $logger;
        $this->config = $config;
        $this->client = $client;
        $this->dataExtension = $dataExtension;
    }

    /**
     * Check status of request and log errors
     *
     * @param $taskName
     * @param $postResult
     * @return bool
     */
    protected function checkResponseStatus($taskName, $postResult)
    {
        if ($postResult->code != '200') {
            $msg = 'Returns code: ' . $postResult->code . ' for \'' . $taskName . '\'. More details: ' . $postResult->message;
            $this->logger->addError($msg);
            return false;
        }

        /*************** Status with error ***************/
        if ($postResult->status === false) {
            foreach ($postResult->results as $result) {
                $statusCode = $result->StatusCode;
                $statusMessage = $result->StatusMessage;
                $errorCode = $result->ErrorCode;
                $msg = 'Request for \'' . $taskName . '\' failed. '."\n".'Status code: ' . $statusCode ."\n".'Message: ' . $statusMessage ."\n".'Error code: ' . $errorCode . '.';
                $this->logger->addWarning($msg);
                echo "<br>" . $statusMessage . " Error code: " . $errorCode . "<br>";
            }
            return false;
        }
        /*************** Status with error ***************/

        return true;
    }

    /**
     * Get all Data Extensions
     *
     * @return array|\FuelSdk\ET_DataExtension|mixed|null|object
     */
    public function getAllDataExtensions()
    {
        $getDE = $this->dataExtension;
        $getDE->authStub = $this->client->getClient();
        $getDE->props = ["CustomerKey", "Name"];
        $getResult = $getDE->get();

        $getStatus = $this->checkResponseStatus('get_all_data_extensions', $getResult);

        if (true === $getStatus) {
            // print_r('More Results: '.($getResult->moreResults ? 'true' : 'false')."\n");
            return $getResult->results;
        }
        return null;
    }

    /**
     * Create a Data Extension
     *
     * @param $dataExtensionName
     * @param $customerKey
     * @param $columnsData
     * @return null|string
     */
    public function createDataExtension($dataExtensionName, $customerKey, $columnsData)
    {
        $postDE = $this->dataExtension;
        $postDE->authStub = $this->client->getClient();
        $postDE->props = ["Name" => $dataExtensionName, "CustomerKey" => $customerKey];
        $postDE->columns = $columnsData;
        $postResult = $postDE->post();

        $postStatus = $this->checkResponseStatus('create_new_data_extension', $postResult);

        if (true === $postStatus) {
            foreach ($postResult->results as $result) {
                $statusCode = $result->StatusCode;      // e.g. string 'OK' (length=2)
                $statusMessage = $result->StatusMessage;// e.g. string 'Data Extension created.' (length=23)
                $ordinalID = $result->OrdinalID;        // e.g. int 0
                $newID = $result->NewID;                // e.g. int 0
                $newObjectID = $result->NewObjectID;    // e.g. string 'af258c45-46e6-e711-b222-1402ec659470' (length=36)

                $object = $result->Object;
                $partnerKey = $object->PartnerKey;
                $objectID = $object->ObjectID;
                $customerKey = $object->CustomerKey;
                $name = $object->Name;

                $msg = 'Result status: ' . $statusCode . '. ' . $statusMessage . ' New Object ID: ' . $objectID . '. Customer Key: ' . $customerKey . '. Name: ' . $name . '.';
                $this->logger->addInfo($msg);
            }
            return (string) $customerKey;
        }
        return null;
    }


    /**
     * Update a Data Extension (Add New Column)
     *
     * @param $dataExtensionName
     * @param $customerKey
     * @param $columnData
     * @return array|\FuelSdk\ET_DataExtension|mixed|null|object
     */
    public function addNewColumn($dataExtensionName, $customerKey, $columnData)
    {
        $patchDE = $this->dataExtension;
        $patchDE->authStub = $this->client->getClient();
        $patchDE->props = ["Name" => $dataExtensionName, "CustomerKey" => $customerKey];
        $patchDE->columns = $columnData;
        $patchResult = $patchDE->patch();

        $patchStatus = $this->checkResponseStatus('add_new_column_to_data_extension', $patchResult);

        if (true === $patchStatus) {
            return $patchResult->results;
        }
        return null;
    }

    /**
     * Get single Data Extension
     *
     * @param $customerKey
     * @param array $data
     * @return array|\FuelSdk\ET_DataExtension|mixed|null|object
     */
    public function getDataExtension($customerKey, $data = [])
    {
        $getDE = $this->dataExtension;
        $getDE->authStub = $this->client->getClient();
        $getDE->props = $data;
        $getDE->filter = array('Property' => 'CustomerKey', 'SimpleOperator' => 'equals', 'Value' => $customerKey);
        $getResult = $getDE->get();

        $getStatus = $this->checkResponseStatus('get_single_data_extension', $getResult);

        if (true === $getStatus) {
            // print_r('More Results: ' . ($getResult->moreResults ? 'true' : 'false') . "\n");
            return $getResult->results;
        }
        return null;
    }

    /**
     * Delete a Data Extension
     *
     * @param $dataExtensionName
     * @param $customerKey
     * @return array|\FuelSdk\ET_DataExtension_Row|mixed|null|object
     * @internal param $data
     */
    public function deleteDataExtension($dataExtensionName, $customerKey)
    {
        $deleteDE = $this->dataExtension;
        $deleteDE->authStub = $this->client->getClient();
        $deleteDE->props = ["Name" => $dataExtensionName, "CustomerKey" => $customerKey];
        $deleteResult = $deleteDE->delete();

        $deleteStatus = $this->checkResponseStatus('delete_data_extension', $deleteResult);

        if (true === $deleteStatus) {
            return $deleteResult->results;
        }
        return null;
    }
}
