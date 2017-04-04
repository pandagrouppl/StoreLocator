<?php

namespace MagicToolbox\Magic360\Controller\Adminhtml\Settings;

use MagicToolbox\Magic360\Controller\Adminhtml\Settings;

class Index extends \MagicToolbox\Magic360\Controller\Adminhtml\Settings
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
        $resultRedirect->setPath('magic360/*/edit', ['active_tab' => $activeTab]);
        return $resultRedirect;
    }
}
