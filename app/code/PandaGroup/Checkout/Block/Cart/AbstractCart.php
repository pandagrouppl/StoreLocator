<?php

namespace PandaGroup\Checkout\Block\Cart;

class AbstractCart
{
    public function afterGetItemRenderer(\Magento\Checkout\Block\Cart\AbstractCart $subject, $result)
    {
        $result->setTemplate('PandaGroup_Checkout::cart/item/default.phtml');
        return $result;
    }

}
