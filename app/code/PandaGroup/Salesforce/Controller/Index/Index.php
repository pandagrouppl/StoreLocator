<?php

namespace PandaGroup\Salesforce\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    /** @var  \Magento\Framework\View\Result\Page */
    protected $resultPageFactory;


    /**
     * Index constructor.
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Salesforce action
     */
    public function execute()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        /** @var \PandaGroup\Salesforce\Model\Api\Client $client */
        //$client = $objectManager->create('PandaGroup\Salesforce\Model\Api\Client');
        //$client->getClient();

        /** @var \PandaGroup\Salesforce\Model\DataExtension\Carts $carts */
        $carts = $objectManager->create('PandaGroup\Salesforce\Model\DataExtension\Carts');
        //$carts->createCartsDataExtension();
        //$carts->syncCartsDataExtension();


        /** @var \PandaGroup\Salesforce\Model\DataExtension\Products $productsDataExtension */
        $productsDataExtension = $objectManager->create('PandaGroup\Salesforce\Model\DataExtension\Products');
        //$productsDataExtension->syncProductsDataExtension();

        echo "\nFINISH DEBUGGING"; exit;
    }
}
