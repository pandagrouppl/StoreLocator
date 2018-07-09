<?php
namespace PandaGroup\AnzExtender\Controller\Onepage;

class Error extends \Magento\Checkout\Controller\Onepage
{
    /**
     * Order error action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Unsuccessful Payment'));
        return $resultPage;
    }
}
