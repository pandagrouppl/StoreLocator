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
use Magento\Framework\ObjectManagerInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;

class RegistrationSuccessObserver implements ObserverInterface
{
    protected $_helper;
    protected $_objectManager;
    protected $_session;
    protected $_request;

    public function __construct(
        HelperData $helper,
        ObjectManagerInterface $objectManager,
        Session $customerSession,
        RequestInterface $httpRequest
    ) {
        $this->_helper = $helper;
        $this->_objectManager = $objectManager;
        $this->_session = $customerSession;
        $this->_request = $httpRequest;
    }

    public function execute(Observer $observer)
    {
        if(!$this->_helper->moduleEnabled()) {
            return;
        }

        $data = $this->_session->getData('pslogin');
        
        if(!empty($data['provider']) && !empty($data['timeout']) && $data['timeout'] > time()) {
            $model = $this->_objectManager->get('Plumrocket\SocialLoginFree\Model\\'. ucfirst($data['provider']));
            
            $customerId = null;
            if($customer = $observer->getCustomer()) {
                $customerId = $customer->getId();
            }

            if($customerId) {
                $model->setUserData($data);

                // Remember customer.
                $model->setCustomerIdByUserId($customerId);

                // Load photo.
                if($this->_helper->photoEnabled()) {
                    $model->setCustomerPhoto($customerId);
                }
            }

        }

        // Show share-popup.
        $this->_helper->showPopup();

        // Set redirect url.
        $redirectUrl = $this->_helper->getRedirectUrl('register');
        $this->_request->setParam(\Magento\Framework\App\Response\RedirectInterface::PARAM_NAME_SUCCESS_URL, $redirectUrl);
    }
}
