<?php

namespace PandaGroup\Migration\Controller\Adminhtml\System\Config;

class SetAnzPaymentConf extends \Magento\Backend\App\Action
{
    /** @var \Magento\Framework\Controller\Result\JsonFactory  */
    protected $resultJsonFactory;

    /** @var \PandaGroup\Migration\Model\Migration  */
    protected $migration;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \PandaGroup\Migration\Model\Migration $migration
    )
    {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->migration = $migration;
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

        $result = $this->migration->SetAnzPaymentConfiguration();

        $json->setData($result);
        return $json;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('PandaGroup_Migration::config');
    }
}
?>