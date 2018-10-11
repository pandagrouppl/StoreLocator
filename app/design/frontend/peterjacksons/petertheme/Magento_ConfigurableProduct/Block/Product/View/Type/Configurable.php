<?php
///**
// * Copyright © 2013-2017 Magento, Inc. All rights reserved.
// * See COPYING.txt for license details.
// */
//namespace Magento\ConfigurableProduct\Block\Product\View\Type;
//
//use Magento\ConfigurableProduct\Model\ConfigurableAttributeData;
//use Magento\Customer\Helper\Session\CurrentCustomer;
//use Magento\Framework\App\ObjectManager;
//use Magento\Framework\Locale\Format;
//use Magento\Framework\Pricing\PriceCurrencyInterface;
//
///**
// * Catalog super product configurable part block.
// *
// * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
// */
//class Configurable extends \Magento\Catalog\Block\Product\View\AbstractView
//{
//
//    /**
//     * Composes configuration for js
//     *
//     * @return string
//     */
//    public function getJsonConfig()
//    {
//        $store = $this->getCurrentStore();
//        $currentProduct = $this->getProduct();
//
//        $regularPrice = $currentProduct->getPriceInfo()->getPrice('regular_price');
//        $finalPrice = $currentProduct->getPriceInfo()->getPrice('final_price');
//
//        $options = $this->helper->getOptions($currentProduct, $this->getAllowProducts());
//        $attributesData = $this->configurableAttributeData->getAttributesData($currentProduct, $options);
//
//        $config = [
//            'attributes' => $attributesData['attributes'],
//            'template' => str_replace('%s', '<%- data.price %>', $store->getCurrentCurrency()->getOutputFormat()),
//            'currencyFormat' => $store->getCurrentCurrency()->getOutputFormat(),
//            'optionPrices' => $this->getOptionPrices(),
//            'priceFormat' => $this->localeFormat->getPriceFormat(),
//            'prices' => [
//                'oldPrice' => [
//                    'amount' => $this->localeFormat->getNumber($regularPrice->getAmount()->getValue()),
//                ],
//                'basePrice' => [
//                    'amount' => $this->localeFormat->getNumber($finalPrice->getAmount()->getBaseAmount()),
//                ],
//                'finalPrice' => [
//                    'amount' => $this->localeFormat->getNumber($finalPrice->getAmount()->getValue()),
//                ],
//            ],
//            'productId' => $currentProduct->getId(),
//            'chooseText' => __('Select Size'),
//            'images' => $this->getOptionImages(),
//            'index' => isset($options['index']) ? $options['index'] : [],
//        ];
//
//        if ($currentProduct->hasPreconfiguredValues() && !empty($attributesData['defaultValues'])) {
//            $config['defaultValues'] = $attributesData['defaultValues'];
//        }
//
//        $config = array_merge($config, $this->_getAdditionalConfig());
//
//        return $this->jsonEncoder->encode($config);
//    }
//}
