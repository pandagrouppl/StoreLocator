<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Plugin\Ajax;

use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\Page;

class CategoryViewAjax extends Ajax
{

    /**
     * @param Action $controller
     * @param Page $page
     *
     * @return \Magento\Framework\Controller\Result\Raw|Page
     */
    public function afterExecute(Action $controller, $page)
    {
        if(!$this->isAjax($controller) || !$page instanceof Page)
        {
            return $page;
        }

        $responseData = $this->getAjaxResponseData($page);
        $response = $this->prepareResponse($responseData);
        return $response;
    }
}
