<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Controller\Adminhtml\Code;

use Magento\Framework\Controller\ResultFactory;

class MassDelete extends \Amasty\GiftCard\Controller\Adminhtml\Code
{
    /**
     * @return mixed
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->codeSetCollection);
        $collectionSize = $collection->getSize();

        foreach ($collection as $model) {
            $model->delete();
        }
        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}