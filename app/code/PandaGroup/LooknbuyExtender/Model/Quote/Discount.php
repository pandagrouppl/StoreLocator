<?php

/**
 * PandaGroup
 *
 * @category    PandaGroup
 * @copyright   Copyright(c) 2018 PandaGroup (https://pandagroup.co)
 * @author      Krzysztof Ratajczyk <kratajczyk@pandagroup.co>
 */

namespace PandaGroup\LooknbuyExtender\Model\Quote;

/**
 * Class Discount
 */
class Discount extends \Magedelight\Looknbuy\Model\Quote\Discount
{
    public function collect(
    \Magento\Quote\Model\Quote $quote, \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment, \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        \Magento\Quote\Model\Quote\Address\Total\AbstractTotal::collect($quote, $shippingAssignment, $total);
        $address = $shippingAssignment->getShipping()->getAddress();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $this->helper = $objectManager->create('Magedelight\Looknbuy\Helper\Data');
        $this->_priceModel = $objectManager->create('Magento\Catalog\Model\Product\Type\Price');

        $label = $this->helper->getDiscountLabel();
        $count = 0;
        $appliedCartDiscount = 0;
        $totalDiscountAmount = 0;
        $subtotalWithDiscount = 0;
        $baseTotalDiscountAmount = 0;
        $baseSubtotalWithDiscount = 0;

        $lookIds = explode(',', $quote->getData('look_ids'));
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        if ($lookIds[0] == '') {
            unset($lookIds[0]);
        }

        $this->_qtyArrays = $this->_objectManager->create('Magedelight\Looknbuy\Model\Looknbuy')->calculateProductQtys($lookIds);

        $items = $quote->getAllItems();

        if (!count($items)) {
            $address->setLookDiscountAmount($totalDiscountAmount);
            $address->setBaseLookDiscountAmount($baseTotalDiscountAmount);

            return $this;
        }

        $addressQtys = $this->_calculateAddressQtys($address);

        $finalLookIds = $this->_validateLookIds($addressQtys, $lookIds);
        if (is_array($addressQtys) && count($addressQtys) > 0) {
            $count += array_sum(array_values($addressQtys));
        }

        foreach ($finalLookIds as $id) {
            $look = $this->_objectManager->create('Magedelight\Looknbuy\Model\Looknbuy')->load($id);
            $excludeFromBaseProductFlag = ($look->getExcludeBaseProduct() == 0) ? false : true;
            $totalAmountOfLook = 0;
            $tempArray = array();
            foreach ($items as $item) {
                if ($item instanceof \Magento\Quote\Model\Quote\Address\Item) {
                    $quoteItem = $item->getAddress()->getQuote()->getItemById($item->getQuoteItemId());
                } else {
                    $quoteItem = $item;
                }
                $product = $quoteItem->getProduct();
                $product->setCustomerGroupId($quoteItem->getQuote()->getCustomerGroupId());
                if (isset($this->_qtyArrays[$quoteItem->getProduct()->getId()][$id])) {
                    if (!in_array($quoteItem->getProduct()->getId(), $tempArray)) {
                        if ($excludeFromBaseProductFlag && $product->getId() == $look->getProductId()) {
                            continue;
                        }
                        $tempArray[] = $quoteItem->getProduct()->getId();

                        $qty = $this->_qtyArrays[$quoteItem->getProduct()->getId()][$id];
                        $price = $quoteItem->getDiscountCalculationPrice();
                        $calcPrice = $quoteItem->getCalculationPrice();
                        $itemPrice = $price === null ? $calcPrice : $price;
                        $totalAmountOfLook += $itemPrice * $qty;
                    }
                }
            }

            if ($look->getDiscountType() == 1) {
                $totalDiscountAmount += $look->getDiscountPrice();

                $baseTotalDiscountAmount += $look->getDiscountPrice();
            } else {
                $totalDiscountAmount += ($look->getDiscountPrice() * $totalAmountOfLook) / 100;
                $baseTotalDiscountAmount += ($look->getDiscountPrice() * $totalAmountOfLook) / 100;
            }
        }

        $totalDiscountAmount = round($totalDiscountAmount, 2);
        $baseTotalDiscountAmount = round($baseTotalDiscountAmount, 2);

        $this->helper = $this->_objectManager->create('Magento\Tax\Helper\Data');

        $totaldata = $total->getData();

        $subTotal = $totaldata['subtotal'];
        $baseSubTotal = $totaldata['base_subtotal'];
        if ($totalDiscountAmount > 0 && $this->helper->applyTaxAfterDiscount()) {
            if ($count > 0) {
                $divided = $totalDiscountAmount / $count;
                $baseDivided = $baseTotalDiscountAmount / $count;
                foreach ($items as $item) {
                    $dividedItemDiscount = round(($item->getRowTotal() * $totalDiscountAmount) / $subTotal, 2);
                    $baseDividedItemDiscount = round(($item->getBaseRowTotal() * $baseTotalDiscountAmount) / $baseSubTotal, 2);

                    $oldDiscountAmount = $item->getDiscountAmount();
                    $oldBaseDiscountAmount = $item->getBaseDiscountAmount();
                    $origionalDiscountAmount = $item->getOriginalDiscountAmount();
                    $baseOrigionalDiscountAmount = $item->getBaseOriginalDiscountAmount();

                    $item->setDiscountAmount($oldDiscountAmount + $dividedItemDiscount);
                    $item->setBaseDiscountAmount($oldBaseDiscountAmount + $baseDividedItemDiscount);
                    $item->setOriginalDiscountAmount($origionalDiscountAmount + $dividedItemDiscount);
                    $item->setBaseOriginalDiscountAmount($baseOrigionalDiscountAmount + $baseDividedItemDiscount);
                }
            }
        }

        $address->setLookDiscountAmount($totalDiscountAmount);

        $address->setBaseLookDiscountAmount($baseTotalDiscountAmount);
        $quote->setLookDiscountAmount($totalDiscountAmount);
        $quote->setBaseLookDiscountAmount($baseTotalDiscountAmount);

        $discountAmount = -$totalDiscountAmount;

        if ($total->getDiscountDescription()) {
            // If a discount exists in cart and another discount is applied, the add both discounts.
            $appliedCartDiscount = $total->getDiscountAmount();
            $discountAmount = $total->getDiscountAmount() + $discountAmount;
            $label = $total->getDiscountDescription().', '.$label;
        }

        /** FIX PANDAGROUP START - problem with free shipping coupons */
//        $getSubTotal = $total->getSubtotal();
//        $tempDiscount = str_replace('-', '', $discountAmount);
//        if ($tempDiscount > $getSubTotal) {
//            $discountAmount = '-'.$getSubTotal;
//        }
        /** FIX PANDAGROUP END - problem with free shipping coupons */

        $total->setDiscountDescription($label);
        $total->setDiscountAmount($discountAmount);
        $total->setBaseDiscountAmount($discountAmount);
        $total->setSubtotalWithDiscount($total->getSubtotal() + $discountAmount);
        $total->setBaseSubtotalWithDiscount($total->getBaseSubtotal() + $discountAmount);

        if (isset($appliedCartDiscount)) {
            $total->addTotalAmount($this->getCode(), $discountAmount - $appliedCartDiscount);
            $total->addBaseTotalAmount($this->getCode(), $discountAmount - $appliedCartDiscount);
        } else {
            $total->addTotalAmount($this->getCode(), $discountAmount);
            $total->addBaseTotalAmount($this->getCode(), $discountAmount);
        }

        return $this;
    }

}