<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\Shopby\Controller\Adminhtml\Option;

class Settings extends \Amasty\Shopby\Controller\Adminhtml\Option
{
    public function execute()
    {
        $resultLayout = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_LAYOUT);
        return $resultLayout;
    }

}
