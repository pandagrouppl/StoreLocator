<?php

namespace PandaGroup\StoreLocator\Controller\Index;

class Json extends \Magento\Framework\App\Action\Action
{
    /** @var  \Magento\Framework\Controller\Result\JsonFactory */
    protected $resultJsonFactory;

    /** @var  \PandaGroup\StoreLocator\Model\StoreLocator */
    protected $storeLocatorModel;

    /**
     * Json constructor.
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \PandaGroup\StoreLocator\Model\StoreLocator $storeLocatorModel
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \PandaGroup\StoreLocator\Model\StoreLocator $storeLocatorModel
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->storeLocatorModel = $storeLocatorModel;
        parent::__construct($context);
    }

    /**
     * Blog Index, shows a list of recent blog posts.
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();

//        if (isset($_SERVER['REMOTE_ADDR']) AND ($_SERVER['REMOTE_ADDR'] !== $_SERVER['SERVER_ADDR'])) {
//
//            $response = [
//                'error'     => '401',
//                'message'   => 'Invalid address. No access'
//            ];
//
//            $result->setData($response);
//            return $result;
//        }

        $response = $this->storeLocatorModel->getStoresData();

        $result->setData($response);
        return $result;
    }
}
