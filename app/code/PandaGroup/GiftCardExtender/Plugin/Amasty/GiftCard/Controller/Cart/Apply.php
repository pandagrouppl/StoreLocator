<?php

namespace PandaGroup\GiftCardExtender\Plugin\Amasty\GiftCard\Controller\Cart;

class Apply extends \PandaGroup\GiftCardExtender\Plugin\Amasty\GiftCard\Controller\Redirect
{
    /**
     * Plugin After to change default redirect address in depends of source page (cart/checkout)
     */
    public function afterExecute(\Amasty\GiftCard\Controller\Cart\Apply $subject, $result)
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect = $this->setCorrectRedirect($resultRedirect);
        return $resultRedirect;
    }
}
