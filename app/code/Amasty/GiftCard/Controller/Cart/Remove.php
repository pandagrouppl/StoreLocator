<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Controller\Cart;

class Remove extends \Amasty\GiftCard\Controller\Cart
{
    public function execute()
    {
        try {
            $id = $this->getRequest()->getParam('code_id');
            $quoteModel = $this->quoteGiftCard->create();
            $this->quoteGiftCardResource->load($quoteModel, $id, 'code_id');
            $this->quoteGiftCardResource->delete($quoteModel);
            $this->messageManager->addSuccessMessage(__('Gift Card was removed.'));
        } catch (\Exception $e) {
            // display error message
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        $this->_redirect('checkout/cart/');
        return;
    }
}