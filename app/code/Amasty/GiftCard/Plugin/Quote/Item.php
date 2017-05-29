<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */


namespace Amasty\GiftCard\Plugin\Quote;

class Item
{
	/**
	 * @var \Amasty\GiftCard\Helper\Data
	 */
	protected $amHelper;

	public function __construct(
		\Amasty\GiftCard\Helper\Data $amHelper
	) {
		$this->amHelper = $amHelper;
	}

    public function afterGetPrice(
        \Magento\Quote\Model\Quote\Item $item,
        $price
    )
    {
        $product = $item->getProduct();
        if ($product->getTypeId() == \Amasty\GiftCard\Model\Product\Type\GiftCard::TYPE_GIFTCARD_PRODUCT) {
            if (isset($item->getOptionsByCode()['info_buyRequest'])
                && isset($item->getOptionsByCode()['info_buyRequest']['value'])
            ) {
                $options = unserialize($item->getOptionsByCode()['info_buyRequest']['value']);

                if (isset($options['am_giftcard_amount_custom']) && $options['am_giftcard_amount_custom']) {
	                $optionByCode = $item->getOptionByCode( 'am_giftcard_amount_custom' )->getValue();
	                if ( $optionByCode == false ) {
		                $price = $item->getOptionByCode( 'am_giftcard_amount' )->getValue();
	                } else {
		                $price = $optionByCode;
	                }
                }
	            $feeType = $product->getAmGiftcardFeeType();
	            /*missing gift card products options on checkout cart*/
	            if($feeType == null){
		            $product->getResource()->load($product, $product->getId());
		            $feeType = $product->getAmGiftcardFeeType();
	            }
                $feeValue = (float)$product->getAmGiftcardFeeValue();
                if ($feeType == \Amasty\GiftCard\Model\GiftCard::PRICE_TYPE_PERCENT){
                    $price += $price * $feeValue / 100;
                } elseif ($feeType == \Amasty\GiftCard\Model\GiftCard::PRICE_TYPE_FIXED) {
                    $price = $price + $feeValue;
                }
            }
        }

        return $price;
    }
}