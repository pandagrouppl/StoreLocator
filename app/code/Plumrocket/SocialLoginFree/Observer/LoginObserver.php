<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket_SocialLoginFree
 * @copyright   Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

namespace Plumrocket\SocialLoginFree\Observer;

use Plumrocket\SocialLoginFree\Helper\Data as HelperData;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Model\Session;

class LoginObserver implements ObserverInterface
{
    protected $_helper;
    protected $_session;

    public function __construct(
        HelperData $helper,
        Session $customerSession
    ) {
        $this->_helper = $helper;
        $this->_session = $customerSession;
    }

    public function execute(Observer $observer)
    {
        if(!$this->_helper->moduleEnabled()) {
            return;
        }

        // Set redirect url.
        $redirectUrl = $this->_helper->getRedirectUrl('login');
        $this->_session->setBeforeAuthUrl($redirectUrl);
    }
}
