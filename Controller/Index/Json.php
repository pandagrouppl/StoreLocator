<?php

namespace PandaGroup\StoreLocator\Controller\Index;

class Json extends \Magento\Framework\App\Action\Action
{
    /** @var  \Magento\Framework\Controller\Result\JsonFactory */
    protected $resultJsonFactory;

    /**      * @param \Magento\Framework\App\Action\Context $context      */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    )
    {
        $this->resultJsonFactory = $resultJsonFactory;
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

        $response = $this->_objectManager->create('PandaGroup\StoreLocator\Model\StoreLocator')->getStoriesData();

        $result->setData($response);
        return $result;

    }
}