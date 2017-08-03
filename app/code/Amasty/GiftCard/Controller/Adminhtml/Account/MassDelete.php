<?php
namespace Amasty\GiftCard\Controller\Adminhtml\Account;

use Magento\Framework\Controller\ResultFactory;

class MassDelete extends \Amasty\GiftCard\Controller\Adminhtml\Account
{
    /**
     * @return mixed
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->accountCollection);
        $collectionSize = $collection->getSize();

        foreach ($collection as $model) {
            $model->delete();
        }
        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}