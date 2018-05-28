<?php
namespace PandaGroup\Westfield\Controller\Adminhtml\Product;

class Export extends \Magento\Framework\App\Action\Action
{
    /** @var \Magento\Framework\Controller\ResultFactory  */
    protected $resultRedirect;

    /** @var \Magento\Framework\Message\ManagerInterface  */
    protected $messageManager;

    /** @var \PandaGroup\Westfield\Model\Api  */
    protected $api;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \PandaGroup\Westfield\Model\Api $api
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \PandaGroup\Westfield\Model\Api $api
    ) {
        $this->resultRedirect = $context->getResultFactory();
        $this->messageManager = $context->getMessageManager();
        $this->api = $api;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirect->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());

        $status = $this->api->createFullXml();
        if (true === $status) {
            $this->messageManager->addSuccessMessage('Export successfully done.');
        } else {
            $this->messageManager->addErrorMessage('Export undone.');
            // log error
        }

        return $resultRedirect;
    }
}