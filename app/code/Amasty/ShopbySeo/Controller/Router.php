<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\ShopbySeo\Controller;


use Amasty\ShopbySeo\Helper\Url;
use Amasty\ShopbySeo\Helper\UrlParser;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Magento\Framework\Module\Manager;

class Router implements \Magento\Framework\App\RouterInterface
{
    /** @var \Magento\Framework\App\ActionFactory */
    protected $actionFactory;

    /** @var \Magento\Framework\App\ResponseInterface */
    protected $_response;

    /** @var  Url */
    protected $urlHelper;

    /** @var  \Magento\Framework\Registry */
    protected $registry;

    /** @var  UrlParser */
    protected $urlParser;

    /** @var  UrlFinderInterface */
    protected $urlFinder;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /** @var  Manager */
    protected $moduleManager;

    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\App\ResponseInterface $response,
        \Magento\Framework\Registry $registry,
        UrlParser $urlParser,
        Url $urlHelper,
        UrlFinderInterface $urlFinder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        Manager $moduleManager
    ) {
        $this->actionFactory = $actionFactory;
        $this->_response = $response;
        $this->registry = $registry;
        $this->urlHelper = $urlHelper;
        $this->urlParser = $urlParser;
        $this->urlFinder = $urlFinder;
        $this->scopeConfig = $scopeConfig;
        $this->moduleManager = $moduleManager;
    }

    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        if (!$this->urlHelper->isSeoUrlEnabled()) {
            return;
        }

        $identifier = trim($request->getPathInfo(), '/');
        if (!preg_match('@^(.*)/([^/]+)@', $identifier, $matches))
            return;

        $seoPart = $this->urlHelper->removeCategorySuffix($matches[2]);
        $suffixMoved = $seoPart != $matches[2];
        $alias = $matches[1];

        $params = $this->urlParser->parseSeoPart($seoPart);
        if ($params === false) {
            return;
        }

        /**
         * for brand pages with key, e.g. /brand/adidas
         */
        $matchedAlias = null;
        if ($this->moduleManager->isEnabled('Amasty_ShopbyBrand')) {
            $brandKey = trim($this->scopeConfig->getValue('amshopby_brand/general/url_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
            if ($brandKey == $alias) {
                $matchedAlias = $alias;
            }
        }

        /* For regular seo category */
        if(!$matchedAlias) {
            $category = $suffixMoved ? $this->urlHelper->addCategorySuffix($alias) : $alias;
            $rewrite = $this->urlFinder->findOneByData([
                UrlRewrite::REQUEST_PATH => $category,
            ]);
            if ($rewrite) {
                $matchedAlias = $category;
            }
        }

        if ($matchedAlias) {
            $this->registry->register('amasty_shopby_seo_parsed_params', $params);
            $request->setParams($params);
            $request->setPathInfo($matchedAlias);
            return $this->actionFactory->create('Magento\Framework\App\Action\Forward');
        }
    }

}
