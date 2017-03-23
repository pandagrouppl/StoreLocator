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

namespace Plumrocket\SocialLoginFree\Controller\Account;

class DoUse extends \Plumrocket\SocialLoginFree\Controller\AbstractAccount
{
    public function execute()
    {
        $session = $this->_getSession();
        if ($session->isLoggedIn() && !$this->getRequest()->getParam('call')) {
            return $this->_windowClose();
        }

        $type = $this->getRequest()->getParam('type');
        $className = 'Plumrocket\SocialLoginFree\Model\\'. ucfirst($type);
        if(!$type || !class_exists($className)) {
            return $this->_windowClose();
        }

        $model = $this->_objectManager->get($className);
        if(!$this->_getHelper()->moduleEnabled() || !$model->enabled()) {
            return $this->_windowClose();
        }

        if($call = $this->getRequest()->getParam('call')) {
            $this->_getHelper()->apiCall([
                'type'      => $type,
                'action'    => $call,
            ]);
        }else{
            $this->_getHelper()->apiCall(null);
        }

        // Set current store.
        $currentStoreId = $this->_objectManager->get('Magento\Store\Model\StoreManager')->getStore()->getId();
        if ($currentStoreId) {
            $this->_getHelper()->refererStore($currentStoreId);
        }

        // Set redirect url.
        if ($referer = $this->_getHelper()->getCookieRefererLink()) {
            $this->_getHelper()->refererLink($referer);
        }

        switch($model->getProtocol()) {

            case 'OAuth':
                if($link = $model->getProviderLink()) {
                    return $this->_redirect($link);
                }else{
                    $this->getResponse()->setBody(__('This Login Application was not configured correctly. Please contact our customer support.'));
                }
                break;

            case 'OpenID':
            case 'BrowserID':
            default:
                return $this->_windowClose();
        }
    }
}