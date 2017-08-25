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


        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $transportBuilder = $objectManager->create('Magento\Framework\Mail\Template\TransportBuilder');
        $storeManager = $objectManager->create('Magento\Store\Model\StoreManagerInterface');

        $store = $storeManager->getStore()->getId();
        $transport = $transportBuilder->setTemplateIdentifier('customer_create_account_email_template')
            ->setTemplateOptions(['area' => 'frontend', 'store' => $store])
            ->setTemplateVars(
                [
                    'store' => $storeManager->getStore(),
                ]
            )
            ->setFrom('general')
            // you can config general email address in Store -> Configuration -> General -> Store Email Addresses
            ->addTo('akowalczewski@light4website.com', 'Adrianos  K.')
            ->getTransport();
        $transport->sendMessage();

        echo 'sent';
        exit;




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
