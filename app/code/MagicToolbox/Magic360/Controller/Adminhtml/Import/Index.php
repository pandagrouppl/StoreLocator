<?php

namespace MagicToolbox\Magic360\Controller\Adminhtml\Import;

use MagicToolbox\Magic360\Controller\Adminhtml\Import;

class Index extends \MagicToolbox\Magic360\Controller\Adminhtml\Import
{
    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('magic360/*/edit');
        return $resultRedirect;
    }
}
