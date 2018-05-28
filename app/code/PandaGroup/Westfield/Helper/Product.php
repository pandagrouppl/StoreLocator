<?php

namespace PandaGroup\Westfield\Helper;

class Product extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return bool
     */
    public function isNew($product)
    {
        if ($product->getData('featured_product')) {
            return true;
        }

        if ($product->getData('news_from_date') == null && $product->getData('news_to_date') == null) {
            return false;
        }

        if ($product->getData('news_from_date') !== null) {
            if (date('Y-m-d', strtotime($product->getData('news_from_date'))) > date('Y-m-d', time())) {
                return false;
            }
        }

        if ($product->getData('news_to_date') !== null) {
            if (date('Y-m-d', strtotime($product->getData('news_to_date'))) < date('Y-m-d', time())) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return bool
     */
    public function isSale($product)
    {
        $specialPrice = $product->getSpecialPrice();
        $specialPriceFromDate = $product->getSpecialFromDate();
        $specialPriceToDate = $product->getSpecialToDate();
        $today = time();

        if ($specialPrice) {
            if ($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate)
                || $today >= strtotime($specialPriceFromDate) && is_null($specialPriceToDate)
            ) {
                return true;
            }
        }

        return false;
    }
}
