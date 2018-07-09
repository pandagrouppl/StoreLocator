<?php

namespace PandaGroup\Salesforce\Controller\Adminhtml\System\Config;

class CreateDataExtensions extends \Magento\Backend\App\Action
{
    /** @var \Magento\Framework\Controller\Result\RedirectFactory  */
    protected $redirectFactory;

    /** @var \PandaGroup\Salesforce\Model\DataExtension  */
    protected $dataExtension;


    /**
     * CreateDataExtensions constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory
     * @param \PandaGroup\Salesforce\Model\DataExtension $dataExtension
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory,
        \PandaGroup\Salesforce\Model\DataExtension $dataExtension
    ) {
        $this->redirectFactory = $redirectFactory;
        $this->dataExtension = $dataExtension;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if (true === $this->dataExtension->createDataExtensions()) {
            $this->messageManager->addSuccessMessage('Salesforce data extensions was successfully created.');
        } else {
            $this->messageManager->addErrorMessage('Exception occurred during create cart data extension.');
        }

        $resultRedirect->setRefererUrl();
        return $resultRedirect;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('PandaGroup_Salesforce::config');
    }
}
