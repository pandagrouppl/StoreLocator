<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Model\Product;

use Magento\Customer\Api\GroupManagementInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class Price extends \Magento\Catalog\Model\Product\Type\Price
{

    protected $_minMaxAmount = [];

    public function getAmounts($product)
    {
        $prices = $product->getData('am_giftcard_prices');

        if (is_null($prices)) {
            if ($attribute = $product->getResource()->getAttribute('am_giftcard_prices')) {
                $attribute->getBackend()->afterLoad($product);
                $prices = $product->getData('am_giftcard_prices');
            }
        }

        return ($prices) ? $prices : array();
    }

    public function isMultiAmount($product)
    {
        $minMaxAmount = $this->getMinMaxAmount($product);

        return $minMaxAmount['min'] != $minMaxAmount['max'] || is_null($minMaxAmount['max']);
    }

    public function getMinMaxAmount($product)
    {
        if(!isset($this->_minMaxAmount[$product->getId()])) {
            $min = $max = null;
            foreach($this->getAmounts($product) as $amount) {
                $min = is_null($min) ? $amount['price'] : min($min, $amount['price']);
                $max = is_null($max) ? $amount['price'] : max($max, $amount['price']);
            }

            if($product->getAmAllowOpenAmount())
            {
                if(is_null($min)) {
                    $min = 0;
                }

                $min = min($min, (int)$product->getAmOpenAmountMin());

                $max = $product->getAmOpenAmountMax() ? max($max, $product->getAmOpenAmountMax()) : $max;
            }

            $this->_minMaxAmount[$product->getId()] = array('min'=>$min, 'max' => $max);
        }
        return $this->_minMaxAmount[$product->getId()];

    }

    public function getFinalPrice($qty=null, $product)
    {
        $finalPrice = $product->getPrice();
        if ($product->hasCustomOptions()) {
            $customOption = $product->getCustomOption('am_giftcard_amount');
            if ($customOption) {
                $customValue = $customOption->getValue();
                $giftCardPriceType = $product->getAmGiftcardPriceType();
                if(is_null($giftCardPriceType)) {
                    $giftCardPriceType = $product->getResource()->getAttributeRawValue(
                        $product->getId(),
                        'am_giftcard_price_type',
                        $this->_storeManager->getStore()->getId()
                    );
                }
                if($giftCardPriceType == \Amasty\GiftCard\Model\GiftCard::PRICE_TYPE_PERCENT) {
                    $pricePercent = $product->getAmGiftcardPricePercent();
                    if(is_null($pricePercent)) {
                        $pricePercent = $product->getResource()->getAttributeRawValue(
                            $product->getId(),
                            'am_giftcard_price_percent',
                            $this->_storeManager->getStore()->getId());
                    }
                    $customValue *= $pricePercent / 100;
                    $customValue = $this->priceCurrency->round($customValue);
                }
                $finalPrice += $customValue;
            }
        }

        $finalPrice = $this->_applyOptionsPrice($product, $qty, $finalPrice);

        $product->setData('final_price', $finalPrice);
        $product->setData('price', $finalPrice);
        return max(0, $product->getData('final_price'));
    }

}