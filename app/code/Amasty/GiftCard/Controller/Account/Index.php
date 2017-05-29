<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Controller\Account;

class Index extends \Amasty\GiftCard\Controller\Account
{

    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Gift Cards'));
        $currentCustomerId = $this->_getSession()->getCustomerId();
        if ($currentCustomerId) {
            $listCards = $this->accountCollection->savedByCustomer($currentCustomerId);
            $this->coreRegistry->register('customer_am_gift_cards', $listCards);
        }
        $this->_view->renderLayout();
    }
}