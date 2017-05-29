<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Plugin\Ajax;

use Amasty\Shopby\Helper\State;

class CmsControllerIndexIndexAjax extends Ajax
{
    /** @var \Amasty\Shopby\Model\Layer\Cms\Manager  */
    protected $cmsManager;

    public function __construct(
        \Amasty\Shopby\Helper\Data $helper,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Amasty\Shopby\Model\Layer\Cms\Manager $cmsManager,
        State $stateHelper
    )
    {
        $this->cmsManager = $cmsManager;
        parent::__construct($helper, $resultRawFactory, $stateHelper);
    }


    public function afterExecute(
        \Magento\Cms\Controller\Index\Index $action,
        $resultPage
    ) {
        if(!$this->isAjax($action) || !$resultPage instanceof \Magento\Framework\View\Result\Page )
        {
            return $resultPage;
        }

        $cmsBlock = null;

        foreach($resultPage->getLayout()->getAllBlocks() as $cmsBlock){
            if ($cmsBlock instanceof \Magento\Cms\Block\Widget\Block){

                $cmsBlock->toHtml();
                foreach($resultPage->getLayout()->getAllBlocks() as $block){
                    if ($block->getData('use_improved_navigation') == 1 &&
                        $block->getProductCollection() instanceof \Magento\Catalog\Model\ResourceModel\Product\Collection){

                        $this->cmsManager->setCmsCollection($block->getProductCollection());
                        break;
                    }
                }
                if ($this->cmsManager->isCmsPageNavigation()){
                    break;
                }


            }
        }

        $cmsBlock->getLayout()->unsetElement('widget.products.list.pager');
        
        $responseData = $this->getAjaxResponseData($resultPage);

        if ($cmsBlock){
            $responseData['cmsPageData'] = $cmsBlock->toHtml();
            $cmsBlock->getLayout()->unsetElement('widget.products.list.pager');
        }

        $response = $this->prepareResponse($responseData);

        return $response;
    }
}
