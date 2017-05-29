<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */


namespace Amasty\GiftCard\Plugin\Quote;

class AddToCart
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

	/**
	 * before add gift card check amount_custom to convert this to base currency
	 * @param $subject
	 * @param $productInfo
	 * @param null $requestInfo
	 *
	 * @return array
	 */
	public function beforeAddProduct(
	    $subject,
	    $productInfo,
	    $requestInfo = null
    ) {
	    if ($productInfo->getTypeId() == "amgiftcard") {
		    $quote             = $subject->getQuote();
		    $baseCurrencyCode  = $quote->getBaseCurrencyCode();
		    $quoteCurrencyCode = $quote->getQuoteCurrencyCode();
		    if ($baseCurrencyCode !== $quoteCurrencyCode
		        && $requestInfo['am_giftcard_amount_custom'] !== ""
		    ) {
			    $price = $this->amHelper->currencyConvert(
			    	$requestInfo['am_giftcard_amount_custom'],
				    $quoteCurrencyCode,
				    $baseCurrencyCode
			    );
			    $requestInfo['am_giftcard_amount_custom'] = $price;
		    }
	    }

		return [$productInfo, $requestInfo];
    }
}