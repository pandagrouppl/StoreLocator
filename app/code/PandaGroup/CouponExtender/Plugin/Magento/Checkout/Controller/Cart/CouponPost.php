<?php

namespace PandaGroup\CouponExtender\Plugin\Magento\Checkout\Controller\Cart;

class CouponPost extends \PandaGroup\CouponExtender\Plugin\Magento\Checkout\Controller\Redirect
{
    /**
     * Plugin After to change default redirect address in depends of source page (cart/checkout)
     */
    public function afterExecute(\Magento\Checkout\Controller\Cart\CouponPost $subject, $result)
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect = $this->setCorrectRedirect($resultRedirect);
        return $resultRedirect;
    }
}
