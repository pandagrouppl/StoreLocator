<?php
namespace WeltPixel\Backend\Controller\Adminhtml\Licenses;

class Index extends \WeltPixel\Backend\Controller\Adminhtml\Licenses
{
    const ADMIN_RESOURCE = 'WeltPixel_Backend::Modules_License';

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $this->wpHelper->checkAndUpdate();
        return  $resultPage = $this->resultPageFactory->create();
    }
}