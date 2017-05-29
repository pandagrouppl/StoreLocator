<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Quickview
 */

/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Amasty\Quickview\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Url\Helper\Data as UrlHelper;
use Magento\Catalog\Api\ProductRepositoryInterface;

class ControllerFrontSendResponseBefore implements ObserverInterface
{
    /**
     * @var UrlHelper
     */
    protected $urlHelper;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /** @var \Magento\Store\Model\StoreManagerInterface */
    protected $_storeManager;

    public function __construct(
        UrlHelper $urlHelper,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->urlHelper = $urlHelper;
        $this->_coreRegistry = $coreRegistry;
        $this->productRepository = $productRepository;
        $this->_storeManager = $storeManager;
        $this->_objectManager = $objectManager;
    }
    /**
     * Checking whether the using static urls in WYSIWYG allowed event
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $request = $observer->getRequest();
        if ($request->getModuleName() == "amasty_quickview") {
            $response = $observer->getResponse();
            $content = $response->getContent();
            $product = $this->_coreRegistry->registry('current_product');
            if ($product && $product->getId()) {
                $currentUenc = $this->urlHelper->getEncodedUrl();
                $refererUrl = $product->getProductUrl();
                $newUenc = $this->urlHelper->getEncodedUrl($refererUrl);
                $content = str_replace($currentUenc, $newUenc, $content);
            }
            $response->setContent($content);
        }

        if ( strpos($request->getPathInfo(), "/checkout/cart/add/") !== false ) {
            $params = $request->getPost();
            $params = $params->toArray();
            $response = $observer->getResponse();
            $content = $response->getContent();
            if(array_key_exists('in_cart', $params) && strpos($content, "checkout") !== false){
                $result = [];
                $content = $this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode($result);
                $response->setContent($content);
            }
        }
    }
}
