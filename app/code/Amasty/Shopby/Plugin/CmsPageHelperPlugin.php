<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Plugin;

use Magento\Framework\App\Action\Action;

class CmsPageHelperPlugin
{
    const LAYER_CMS = 'amshopby_cms';

    /** @var \Magento\Framework\View\Result\PageFactory  */
    protected $resultPageFactory;

    /** @var \Magento\Cms\Model\PageFactory  */
    protected $pageFactory;

    /** @var \Amasty\Shopby\Model\Cms\PageFactory  */
    protected $shopbyPageFactory;

    /** @var \Magento\Catalog\Model\Layer\Resolver  */
    protected $layerResolver;


    /**
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Cms\Model\PageFactory $pageFactory
     * @param \Amasty\Shopby\Model\Cms\PageFactory $shopbyPageFactory
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     */
    function __construct(
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Cms\Model\PageFactory $pageFactory,
        \Amasty\Shopby\Model\Cms\PageFactory $shopbyPageFactory,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver
    ){
        $this->resultPageFactory = $resultPageFactory;
        $this->pageFactory = $pageFactory;
        $this->shopbyPageFactory = $shopbyPageFactory;
        $this->layerResolver = $layerResolver;
    }

    /**
     * @param \Magento\Cms\Helper\Page $helper
     * @param \Closure $proceed
     * @param Action $action
     * @param null $pageId
     * @return \Magento\Framework\View\Result\Page
     */
    public function aroundPrepareResultPage(
        \Magento\Cms\Helper\Page $helper,
        \Closure $proceed,
        Action $action,
        $pageId = null
    ){
        $duplicatePageId = $pageId;

        if ($pageId !== null) {
            $delimiterPosition = strrpos($pageId, '|');
            if ($delimiterPosition) {
                $pageId = substr($pageId, 0, $delimiterPosition);
            }
        }

        $page = $this->pageFactory->create()->load($pageId);
        $shopbyPage = $this->shopbyPageFactory->create()->load($page->getId(), 'page_id');

        if ($shopbyPage->getEnabled()) {
            $this->layerResolver->create(self::LAYER_CMS);
            $resultPage = $this->resultPageFactory->create();
            $resultPage->addHandle('amshopby_cms_navigation');
        }

        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $proceed($action, $duplicatePageId);

        return $resultPage;
    }
}