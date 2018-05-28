<?php

namespace PandaGroup\Quickview\Controller\Ajax;

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
            $page->addHandle('quickview_catalog_product_view'); // core template -> custom template default: ('catalog_product_view')
            if ( $product ) {
                $type = $product->getTypeId();
                $page->addHandle('quickview_catalog_product_view_type_' . $type); // core template -> custom template default: ('catalog_product_view_type_')
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