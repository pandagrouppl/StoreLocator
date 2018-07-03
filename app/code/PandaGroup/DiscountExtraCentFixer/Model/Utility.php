<?php

namespace PandaGroup\DiscountExtraCentFixer\Model;

use Magento\Framework\Pricing\PriceCurrencyInterface;

/**
 * Class Utility
 *
 * @package Magento\SalesRule\Model
 */
class Utility extends \Magento\SalesRule\Model\Utility
{
    /**
     * Process "delta" rounding
     *
     * @param \Magento\SalesRule\Model\Rule\Action\Discount\Data $discountData
     * @param \Magento\Quote\Model\Quote\Item\AbstractItem $item
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function deltaRoundingFix(
        \Magento\SalesRule\Model\Rule\Action\Discount\Data $discountData,
        \Magento\Quote\Model\Quote\Item\AbstractItem $item
    ) {
        $store = $item->getQuote()->getStore();
        $discountAmount = $discountData->getAmount();
        $baseDiscountAmount = $discountData->getBaseAmount();

        //TODO Seems \Magento\Quote\Model\Quote\Item\AbstractItem::getDiscountPercent() returns float value
        //that can not be used as array index
        $percentKey = $item->getDiscountPercent();

        if ($percentKey) {
            $delta = isset($this->_roundingDeltas[$percentKey]) ? $this->_roundingDeltas[$percentKey] : 0;
            $baseDelta = isset($this->_baseRoundingDeltas[$percentKey]) ? $this->_baseRoundingDeltas[$percentKey] : 0;

            $discountAmount += $delta;
            $baseDiscountAmount += $baseDelta;

            $this->_roundingDeltas[$percentKey] = $discountAmount - $this->priceCurrency->round($discountAmount);
            $this->_baseRoundingDeltas[$percentKey] = $baseDiscountAmount
                - $this->priceCurrency->round($baseDiscountAmount);
        }

        // Rounded value must be to 4th position
        $discountData->setAmount(round($discountAmount, 4));
        $discountData->setBaseAmount(round($baseDiscountAmount, 4));

        // $discountData->setAmount($this->priceCurrency->round($discountAmount));
        // $discountData->setBaseAmount($this->priceCurrency->round($baseDiscountAmount));

        return $this;
    }
}
