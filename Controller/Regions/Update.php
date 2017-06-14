<?php

namespace PandaGroup\StoreLocator\Controller\Regions;

class Update extends \Magento\Framework\App\Action\Action
{
    /** @var  \Magento\Framework\View\Result\PageFactory */
    protected $resultPageFactory;

    /** @var  \PandaGroup\StoreLocator\Model\StoreLocator */
    protected $storeLocatorModel;

    /**
     * Constructor
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \PandaGroup\StoreLocator\Model\StoreLocator $storeLocatorModel
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->storeLocatorModel = $storeLocatorModel;
        parent::__construct($context);
    }

    /**
     * Update all stores regions in the database, which have incorrect region
     */
    public function execute()
    {
        $this->storeLocatorModel->updateRegions();

        $this->_redirect('storelocator');

        return;
    }
}