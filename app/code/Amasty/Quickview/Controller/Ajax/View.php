<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Quickview
 */
namespace Amasty\Quickview\Controller\Ajax;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class View extends \Magento\Catalog\Controller\Product\View
{
    /**
     * Product view action
     *
     * @return \Magento\Framework\Controller\Result\Forward|\Magento\Framework\Controller\Result\Redirect
     **/
    public function execute()
    {
        // Render page
        try {
            $page = $this->resultPageFactory->create(false, ['isIsolated' => true]);

            $product = $this->_initProduct();
            $page->addHandle('catalog_product_view');
            if ( $product ) {
                $type = $product->getTypeId();
                $page->addHandle('catalog_product_view_type_' . $type);
            }
            return $page;
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return $this->noProductRedirect();
        } catch (\Exception $e) {
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            $resultForward = $this->resultForwardFactory->create();
            $resultForward->forward('noroute');
            return $resultForward;
        }
    }
}
