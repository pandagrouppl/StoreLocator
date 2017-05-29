<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */


namespace Amasty\GiftCard\Controller\Adminhtml\Account;

use Magento\Backend\App\Action;
use Magento\Framework\ObjectManagerInterface;

class Order extends \Amasty\GiftCard\Controller\Adminhtml\Account
{

    public function execute()
    {
        $model = $this->accountFactory->create();
        if ($id = +$this->_request->getParam('id')) {
            $this->accountResource->load($model, $id);
        }
        $this->_coreRegistry->register('current_amasty_giftcard_account', $model);

        $resultLayout = $this->resultLayoutFactory->create();
        $resultLayout->getLayout()->getBlock('amgiftcard.orders.grid')
            ->setAllowedOrders($this->getRequest()->getPost('amgiftcard_allowed', null));

        return $resultLayout;
    }

}
