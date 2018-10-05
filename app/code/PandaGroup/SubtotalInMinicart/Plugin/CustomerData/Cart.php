<?php

namespace PandaGroup\SubtotalInMinicart\Plugin\CustomerData;

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

// Jak mieć odpowiednią kwotę (subtotal) - nie zmienione nazwy zmiennych,
// ale w miejscu DISCOUNT w minicart widoczna kwota jest ceną pomniejszoną o znizki

    public function afterGetSectionData(\Magento\Checkout\CustomerData\Cart $subject, $result)
    {
        $discountAmount = $this->getQuote()->getSubtotalWithDiscount();

        $result['discount_amount'] = $discountAmount;
        $result['discount'] = $this->checkoutHelper->formatPrice($discountAmount);

        return $result;
    }
}
