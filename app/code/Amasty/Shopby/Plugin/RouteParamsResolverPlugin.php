<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Plugin;

use Amasty\ShopbyBrand\Helper\Content;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class RouteParamsResolverPlugin
{
    /**
     * @var \Magento\Catalog\Model\Layer
     */
    protected $layer;

    /** @var \Magento\Framework\Url\QueryParamsResolverInterface  */
    protected $queryParamsResolver;

    /** @var \Amasty\Shopby\Model\Request  */
    protected $shopbyRequest;

    /** @var ScopeConfigInterface  */
    private $scopeConfig;

    /** @var Content  */
    private $contentHelper;

    function __construct(
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Amasty\Shopby\Model\Resolver $amResolver,
        \Magento\Framework\Url\QueryParamsResolverInterface $queryParamsResolver,
        \Amasty\Shopby\Model\Request $shopbyRequest,
        ScopeConfigInterface $scopeConfig,
        Content $contentHelper
    ){
        $this->layer = $amResolver->loadFromParent($layerResolver)->get();
        $this->queryParamsResolver = $queryParamsResolver;
        $this->shopbyRequest = $shopbyRequest;
        $this->scopeConfig = $scopeConfig;
        $this->contentHelper = $contentHelper;
    }

    /**
     * @param \Magento\Framework\Url\RouteParamsResolver $subject
     * @param \Closure $proceed
     * @param array $data
     * @param bool|true $unsetOldParams
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function aroundSetRouteParams(
        \Magento\Framework\Url\RouteParamsResolver $subject,
        \Closure $proceed,
        array $data,
        $unsetOldParams = true
    ){
        if (!array_key_exists('_current', $data)) {
            return $proceed($data, $unsetOldParams);
        }

        $queryParams = $this->queryParamsResolver->getQueryParams();
        $filters = $this->layer->getState()->getFilters();
        foreach($filters as $filter) {
            $filterParam = $this->shopbyRequest->getFilterParam($filter->getFilter());
            if (!empty($filterParam)) {
                $queryParams[$filter->getFilter()->getRequestVar()] = $filterParam;
            }
        }

        //Brand is missing in State
        $attributeCode = $this->scopeConfig->getValue('amshopby_brand/general/attribute_code', ScopeInterface::SCOPE_STORE);
        if ($attributeCode != '') {
            $brand = $this->contentHelper->getCurrentBranding();
            if ($brand) {
                $queryParams[$attributeCode] = $brand->getValue();
            }
        }

        $queryParams[\Amasty\Shopby\Block\Navigation\UrlModifier::VAR_REPLACE_URL] = null;;
        $queryParams['amshopby'] = null;

        if (array_key_exists('price', $queryParams)){
            $data['price'] = null; //fix for catalogsearxch pages
        }

        $result = $proceed($data, $unsetOldParams);
        $this->queryParamsResolver->addQueryParams($queryParams);

        return $result;
    }
}
