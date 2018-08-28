<?php

namespace PandaGroup\LooknbuyLicenseBreaker\Observer;

use Magento\Framework\Event\ObserverInterface;

class Mdfrd extends \Magedelight\Looknbuy\Observer\Mdfrd implements ObserverInterface
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $event = $observer->getEvent()->getName();
        // Disable license sending
    }
}
