<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\Shopby\Plugin\Ajax;

use Amasty\Shopby\Helper\State;
use Magento\Framework\App\Action\Action;

class Ajax
{
    /**
     * @var \Amasty\Shopby\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /** @var State  */
    protected $stateHelper;

    public function __construct(
        \Amasty\Shopby\Helper\Data $helper,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        State $stateHelper
    ) {
        $this->helper = $helper;
        $this->resultRawFactory = $resultRawFactory;
        $this->stateHelper = $stateHelper;
    }

    protected function isAjax(Action $controller)
    {
        $isAjax = $controller->getRequest()->isAjax();
        $isScroll = $controller->getRequest()->getParam('is_scroll');
        return $this->helper->isAjaxEnabled() && $isAjax && !$isScroll;
    }

    /**
     * @param \Magento\Framework\View\Result\Page $page
     *
     * @return array
     */
    protected function getAjaxResponseData(\Magento\Framework\View\Result\Page $page)
    {
        $layout = $page->getLayout();

        $products = $layout->getBlock('category.products');
        if (!$products) {
            $products = $layout->getBlock('search.result');
        }
        $navigation = $layout->getBlock('catalog.leftnav');
        if (!$navigation) {
            $navigation = $layout->getBlock('catalogsearch.leftnav');
        }
        $applyButton = $layout->getBlock('amasty.shopby.applybutton.sidebar');

        $navigationTop = $layout->getBlock('amshopby.catalog.topnav');
        $applyButtonTop = $layout->getBlock('amasty.shopby.applybutton.topnav');
        $h1 = $layout->getBlock('page.main.title');
        $title = $page->getConfig()->getTitle();
        $breadcrumbs = $layout->getBlock('breadcrumbs');

        $htmlCategoryData = '';
        $children = $layout->getChildNames('category.view.container');
        foreach ($children as $child) {
            $htmlCategoryData .= $layout->renderElement($child);
        }
        $htmlCategoryData = '<div class="category-view">' . $htmlCategoryData . '</div>';

        $shopbyCollapse = $layout->getBlock('catalog.navigation.collapsing');
        $shopbyCollapseHtml = '';
        if($shopbyCollapse) {
            $shopbyCollapseHtml = $shopbyCollapse->toHtml();
        }
        if ($navigation) {
            $navigation->toHtml();
        }

        $responseData = [
            'categoryProducts'=> $products ? $products->toHtml() : '',
            'navigation' => ($navigation ? $navigation->toHtml() : '') . $shopbyCollapseHtml . ($applyButton ? $applyButton->toHtml() : ''),
            'navigationTop' => ($navigationTop ? $navigationTop->toHtml() : '') . ($applyButtonTop ? $applyButtonTop->toHtml() : ''),
            'breadcrumbs' => $breadcrumbs ? $breadcrumbs->toHtml() : '',
            'h1' => $h1 ? $h1->toHtml() : '',
            'title' => $title->get(),
            'categoryData' => $htmlCategoryData,
            'url' => $this->stateHelper->getCurrentUrl(),
        ];

        $responseData = $this->removeAjaxParam($responseData);

        return $responseData;
    }

    protected function removeAjaxParam($responseData)
    {
        array_walk($responseData, function (&$html) {
            $spec64 = '+/=';
            $specUrl = '-_,';
            $html = preg_replace_callback('@aHR0cDov[A-Za-z0-9_-]+@u', function($match) use ($specUrl, $spec64) {
                $originalUrl = base64_decode(strtr($match[0], $specUrl, $spec64));
                if ($originalUrl === false) {
                    return $match[0];
                }
                $url = str_replace('?isAjax=1&amp;', '?', $originalUrl);
                $url = str_replace('?isAjax=1', '', $url);
                $url = str_replace('&isAjax=1', '', $url);
                return ($originalUrl == $url) ? $match[0] : rtrim(strtr(base64_encode($url), $spec64, $specUrl), ',');
            }, $html);
        });
        return $responseData;
    }

    /**
     * @param array $data
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    protected function prepareResponse(array $data)
    {
        $response = $this->resultRawFactory->create();
        $response->setHeader('Content-type', 'text/plain');
        $response->setContents(json_encode($data));
        return $response;
    }
}
