<?php
/*

Plumrocket Inc.

NOTICE OF LICENSE

This source file is subject to the End-user License Agreement
that is available through the world-wide-web at this URL:
http://wiki.plumrocket.net/wiki/EULA
If you are unable to obtain it through the world-wide-web, please
send an email to support@plumrocket.com so we can send you a copy immediately.

@package    Plumrocket_Base-v2.x.x
@copyright  Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
@license    http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement

*/

namespace Plumrocket\Base\Observer;
use Magento\Framework\Event\ObserverInterface;
/**
 * Base observer
 */
class Reindex implements ObserverInterface
{
    /**
     * @var \Plumrocket\Base\Model\AdminNotificationFeedFactory
     */
    protected $_product;

    /**
     * @param \Plumrocket\Base\Model\AdminNotificationFeedFactory $feedFactory
     * @param \Magento\Backend\Model\Auth\Session $backendAuthSession
     */
    public function __construct(
        \Plumrocket\Base\Model\Product $product
    ) {
        $this->_product = $product;
    }

    /**
     * Predispath admin action controller
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->_product->reindex();
    }

}