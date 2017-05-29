<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Plugin\Product;

class Price {
	/**
	 * @var \Amasty\GiftCard\Helper\Data
	 */
	protected $amHelper;

	/**
	 * @var \Magento\Framework\App\State
	 */
	protected $appState;

	public function __construct(
		\Amasty\GiftCard\Helper\Data $amHelper,
		\Magento\Framework\App\State $appState
	) {
		$this->amHelper = $amHelper;
		$this->appState = $appState;
	}
	/**
	 * @param \Magento\Catalog\Block\Product\ListProduct $listProduct
	 * @param \Closure $proceed
	 * @param \Magento\Catalog\Model\Product $product
	 * @param array $price
	 *
	 * @return mixed
	 */
	public function aroundGetProductPrice(
		\Magento\Catalog\Block\Product\ListProduct $listProduct,
		\Closure $proceed,
		\Magento\Catalog\Model\Product $product,
		$price = []
	) {
		$priceItem = $proceed($product, $price);
		$typeProduct = $product->getTypeId();

		if ($this->_isFrontend() && $typeProduct == "amgiftcard") {
			$firstAmountValue        = $this->_getFirstOfAmount( $product );
			$product->getResource()->load($product, $product->getId());
			if ($product->getAmAllowOpenAmount()) {
				$priceOpenAmountMinValue = $product->getAmOpenAmountMin();
			} else {
				$priceOpenAmountMinValue = null;
			}
			if ( $priceOpenAmountMinValue || $firstAmountValue ) {
				if ( $priceOpenAmountMinValue < $firstAmountValue
				     && $priceOpenAmountMinValue != null
				     || $firstAmountValue         == null
				) {
					$formatPrice = $this->amHelper->convertAndFormatPrice( $priceOpenAmountMinValue );
				} else {
					$formatPrice = $this->amHelper->convertAndFormatPrice( $firstAmountValue );
				}

				$strWithPrice = __( "From %1", $formatPrice );
				$regToSearch = "/<span class=\"price\">(.*?)<\/span>/";
				$replacement = "<span class=\"price\">" . $strWithPrice . "</span>";
				$replacement = str_replace('$', '\$', $replacement);

				$finalPrice  = preg_replace( $regToSearch, $replacement, $priceItem );

				return $finalPrice;
			}
		}

		return $priceItem;
	}

	/**
	 * @param $product
	 *
	 * @return mixed
	 */
	protected function _getFirstOfAmount($product) {
		$productModel = $product->getPriceModel();
		$listOfAmounts = $productModel->getAmounts($product);
		if ($listOfAmounts) {
			return $listOfAmounts[0]["price"];
		}

		return null;
	}

	/**
	 * @return bool
	 */
	protected function _isFrontend()
	{
		return $this->appState->getAreaCode() == 'frontend';
	}
}