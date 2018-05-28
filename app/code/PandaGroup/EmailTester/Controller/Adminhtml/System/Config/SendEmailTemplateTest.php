<?php

namespace PandaGroup\EmailTester\Controller\Adminhtml\System\Config;

class SendEmailTemplateTest extends \Magento\Backend\App\Action
{
    /** @var \Magento\Framework\Controller\Result\JsonFactory  */
    protected $resultJsonFactory;

    /** @var \PandaGroup\EmailTester\Model\EmailTester  */
    protected $emailTester;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \PandaGroup\EmailTester\Model\EmailTester $emailTester
    )
    {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->emailTester = $emailTester;
        parent::__construct($context);
    }

    /**
     * Update product attributes
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $json = $this->resultJsonFactory->create();

        $email = $this->_request->getParam('email');
        $templateIdentifier = $this->_request->getParam('template');

        $result = $this->emailTester->sendEmailTemplateTest($email, $templateIdentifier);

        $json->setData($result);
        return $json;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('PandaGroup_EmailTester::config');
    }
}
?>