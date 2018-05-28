<?php

namespace PandaGroup\Salesforce\Model\Api;

class Client extends \Magento\Framework\Model\AbstractModel
{
    /** @var \PandaGroup\Salesforce\Logger\Logger  */
    protected $logger;

    /** @var \PandaGroup\Salesforce\Model\Config  */
    protected $config;

    private $client = null;


    /**
     * Client constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \PandaGroup\Salesforce\Logger\Logger $logger
     * @param \PandaGroup\Salesforce\Model\Config $config
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \PandaGroup\Salesforce\Logger\Logger $logger,
        \PandaGroup\Salesforce\Model\Config $config
    ) {
        parent::__construct($context,$registry);
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * Get Client
     *
     * @return \FuelSdk\ET_Client|null
     */
    public function getClient()
    {
        if (null !== $this->client) {
            return $this->client;
        }

        $getWSDL = true;
        $debug = true;
        $params = $this->config->getConnectionParameters();

        try {
            $client = new \FuelSdk\ET_Client($getWSDL, $debug, $params);

            /********* Check connection *********/
//            $getDE = new \FuelSdk\ET_DataExtension();
//            $getDE->authStub = $client;
//            $getDE->props = array("CustomerKey", "Name");
//            $getResult = $getDE->get();
//
//            if (200 !== $getResult->code) {
//                $this->logger->addCritical('Can\'t connect to Salesforce. ' . $getResult->message);
//            }
            /********* Check connection *********/

            $this->client = $client;
            return $client;
        } catch (\Exception $e) {
            $this->logger->addError($e->getMessage());
        }

        return null;
    }
}
