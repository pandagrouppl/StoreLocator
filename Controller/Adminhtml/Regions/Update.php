<?php

namespace PandaGroup\StoreLocator\Controller\Adminhtml\Regions;

class Update extends \Magento\Backend\App\Action
{
    /** @var  \Magento\Framework\View\Result\PageFactory */
    protected $resultPageFactory;

    /** @var  \PandaGroup\StoreLocator\Model\StoreLocator */
    protected $storeLocatorModel;

    /**
     * Constructor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
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