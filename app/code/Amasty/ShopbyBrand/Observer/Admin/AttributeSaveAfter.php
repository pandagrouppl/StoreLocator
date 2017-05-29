<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\ShopbyBrand\Observer\Admin;

use Amasty\ShopbyBrand\Helper\Data as BrandHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;

class AttributeSaveAfter implements \Magento\Framework\Event\ObserverInterface
{
    /** @var BrandHelper  */
    private $brandHelper;

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    public function __construct(
        BrandHelper $settingHelper,
        ScopeConfigInterface $configInterface
    ) {
        $this->brandHelper = $settingHelper;
        $this->scopeConfig = $configInterface;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $attrCode   = $this->scopeConfig->getValue('amshopby_brand/general/attribute_code', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($attrCode == $observer->getEvent()->getAttribute()->getAttributeCode()) {
            $this->brandHelper->updateBrandOptions();
        }
    }
}
