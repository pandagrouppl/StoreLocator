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

    public function afterGetSectionData(\Magento\Checkout\CustomerData\Cart $subject, $result)
    {

        $subtotal_mc = $this->getQuote()->getGrandTotal();
        $result['subtotal_minicart'] = $this->checkoutHelper->formatPrice($subtotal_mc);

        return $result;
    }
}

