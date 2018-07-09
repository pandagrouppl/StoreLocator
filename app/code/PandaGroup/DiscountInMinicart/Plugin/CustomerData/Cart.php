<?php

namespace PandaGroup\DiscountInMinicart\Plugin\CustomerData;

/**
 * Cart source
 */
class Cart extends \Magento\Checkout\CustomerData\Cart
{
    /**
     * Plugin to add discount to CustomerData object
     *
     * {@inheritdoc}
     */
    public function afterGetSectionData(\Magento\Checkout\CustomerData\Cart $subject, $result)
    {
        $discountAmount = $this->getQuote()->getSubtotal() - $this->getQuote()->getSubtotalWithDiscount();

        $result['discount_amount'] = $discountAmount;
        $result['discount'] = $this->checkoutHelper->formatPrice(-$discountAmount);

        return $result;
    }
}
