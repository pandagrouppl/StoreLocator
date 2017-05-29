<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\ShopbyRoot\Controller;

use Amasty\ShopbySeo\Helper\Url;
use Amasty\ShopbySeo\Helper\UrlParser;
use Magento\Framework\Module\Manager;
use Magento\Store\Model\ScopeInterface;

class Router implements \Magento\Framework\App\RouterInterface
{
    /** @var \Magento\Framework\App\ActionFactory */
    protected $actionFactory;

    /** @var \Magento\Framework\App\ResponseInterface */
    protected $response;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /** @var  Manager */
    protected $moduleManager;

    /** @var  \Magento\Framework\Registry */
    protected $registry;

    /** @var  UrlParser */
    protected $urlParser;

    /** @var  Url */
    protected $urlHelper;

    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\App\ResponseInterface $response,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\RequestFactory $requestFactory,
        UrlParser $urlParser,
        Url $urlHelper,
        Manager $moduleManager)
    {
        $this->actionFactory = $actionFactory;
        $this->response = $response;
        $this->scopeConfig = $scopeConfig;
        $this->moduleManager = $moduleManager;
        $this->registry = $registry;
        $this->urlParser = $urlParser;
        $this->urlHelper = $urlHelper;
    }

    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        $shopbyPageUrl = $this->scopeConfig->getValue('amshopby_root/general/url', ScopeInterface::SCOPE_STORE);
        $identifier = trim($request->getPathInfo(), '/');

        $brandUrlKeyMatched = false;
        if ($this->moduleManager->isEnabled('Amasty_ShopbyBrand')) {
            $urlKey = trim($this->scopeConfig->getValue('amshopby_brand/general/url_key', ScopeInterface::SCOPE_STORE));
            $brandUrlKeyMatched = $urlKey == $identifier && $this->registry->registry('amasty_shopby_seo_parsed_params');
        }

        if($identifier == $shopbyPageUrl || $brandUrlKeyMatched) {
            // Forward Shopby

            if ($this->isRouteAllowed($request)) {
                $request->setModuleName('amshopby')->setControllerName('index')->setActionName('index');
                $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $identifier);
                $params = array_merge($this->parseAmShopByParams($request), $request->getParams());
                $request->setParams($params);
                return $this->actionFactory->create('Magento\Framework\App\Action\Forward');
            }
        }

        if ($this->moduleManager->isEnabled('Amasty_ShopbySeo') && $this->scopeConfig->getValue('amasty_shopby_seo/url/mode', ScopeInterface::SCOPE_STORE) && !$this->registry->registry('amasty_shopby_seo_parsed_params')) {
            if ($this->scopeConfig->isSetFlag('amasty_shopby_seo/url/add_suffix_shopby')) {
                $identifier = $this->urlHelper->removeCategorySuffix($identifier);
            }
            $params = $this->urlParser->parseSeoPart($identifier);
            if ($params) {
                $this->registry->register('amasty_shopby_seo_parsed_params', $params);

                // Forward to very short brand-like url
                if ($this->isRouteAllowed($request)) {
                    $request->setModuleName('amshopby')->setControllerName('index')->setActionName('index');
                    $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $shopbyPageUrl);
                    $params = array_merge($params, $request->getParams());
                    $request->setParams($params);
                    return $this->actionFactory->create('Magento\Framework\App\Action\Forward');
                }
            }
        }
    }

    protected function isRouteAllowed(\Magento\Framework\App\RequestInterface $request)
    {
        if ($this->scopeConfig->isSetFlag('amshopby_root/general/enabled', ScopeInterface::SCOPE_STORE)) {
            return true;
        }
        $attribute_code = $this->scopeConfig->getValue('amshopby_brand/general/attribute_code', ScopeInterface::SCOPE_STORE);
        if ($attribute_code) {

            $seoParams = $this->registry->registry('amasty_shopby_seo_parsed_params');
            $seoBrandPresent = isset($seoParams) && array_key_exists($attribute_code, $seoParams);
            if ($request->getParam($attribute_code) || $seoBrandPresent) {
                return true;
            }
        }

        $this->registry->unregister('amasty_shopby_seo_parsed_params');
        return false;
    }

    public function parseAmShopByParams($request)
    {
        $params = [];
        if ($request->getParam('amshopby')) {
            foreach($request->getParams() as $key => $values) {

                if ($key == 'amshopby') {
                    foreach ($values as $key => $item) {
                        $params[$key] = implode(",", $item);
                    }
                } else {
                    $params[$key] = $values;
                }
            }
        }

        return $params;
    }
}
