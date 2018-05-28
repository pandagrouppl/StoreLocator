<?php

namespace PandaGroup\CustomerExtender\Controller\Account;

class Index extends \Magento\Customer\Controller\Account\Index
{
    /**
     * Default customer account page
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('customer/account/edit/');
        return $resultRedirect;
    }
}
