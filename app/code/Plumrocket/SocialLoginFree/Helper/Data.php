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

namespace Plumrocket\SocialLoginFree\Helper;

class Data extends Main
{
    const REFERER_QUERY_PARAM_NAME = 'pslogin_referer';
    const REFERER_STORE_PARAM_NAME = 'pslogin_referer_store';
    const SHOW_POPUP_PARAM_NAME = 'pslogin_show_popup';
    const API_CALL_PARAM_NAME = 'pslogin_api_call';
    const FAKE_EMAIL_PREFIX = 'temp-email-ps';
    const TIME_TO_EDIT = 300;
    const DEBUG_MODE = false;

    protected $_configSectionId = 'psloginfree';
    protected $_buttons = null;
    protected $_buttonsPrepared = null;

    public function moduleEnabled()
    {
        return (bool)$this->getConfig($this->_configSectionId.'/general/enable');
    }

    /*public function getConfigSectionId()
    {
        return $this->_configSectionId;
    }*/

    public function validateIgnore()
    {
        return (bool)$this->getConfig($this->_configSectionId .'/general/validate_ignore');
    }

    public function getShareData()
    {
        return $this->getConfig($this->_configSectionId .'/share');
    }

    public function shareEnabled()
    {
        return $this->moduleEnabled() && $this->getConfig($this->_configSectionId .'/share/enable');
    }

    public function forLoginEnabled()
    {
        return (bool)$this->getConfig($this->_configSectionId .'/general/enable_for_login');
    }

    public function forRegisterEnabled()
    {
        return (bool)$this->getConfig($this->_configSectionId .'/general/enable_for_register');
    }

    public function photoEnabled()
    {
        return $this->moduleEnabled() && $this->getConfig($this->_configSectionId .'/general/enable_photo');
    }

    public function modulePositionEnabled($position)
    {
        $enabled = true;

        $this->moduleEnabled() or $enabled = false;

        switch($position) {
            case 'login':
                $this->forLoginEnabled() or $enabled = false;
                break;

            case 'register':
                $this->forRegisterEnabled() or $enabled = false;
                break;
        }

        return $enabled;
    }

    public function hasButtons()
    {
        if(!$this->moduleEnabled()) {
            return false;
        }

        if($this->_objectManager->get('Magento\Customer\Model\Session')->isLoggedIn()) {
            return false;
        }

        return (bool)$this->getButtons();
    }

    public function getPhotoPath($checkIsEnabled = true, $customerId = null)
    {
        if($checkIsEnabled && !$this->photoEnabled()) {
            return false;
        }

        if ($customerId === null) {
            $session = $this->_objectManager->get('Magento\Customer\Model\Session');
            if(!$session->isLoggedIn()) {
                return false;
            }

            if(!$customerId = $session->getCustomerId()) {
                return false;
            }
        } elseif (!is_numeric($customerId) || $customerId <= 0) {
            return false;
        }

        $directoryRead = $this->_objectManager->get('Magento\Framework\Filesystem')->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $path = 'pslogin'. DIRECTORY_SEPARATOR .'photo'. DIRECTORY_SEPARATOR . $customerId .'.'. \Plumrocket\SocialLoginFree\Model\Account::PHOTO_FILE_EXT;
        $pathUrl = $this->_objectManager->get('Magento\Store\Model\Store')->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) .'pslogin/photo/' . $customerId .'.'. \Plumrocket\SocialLoginFree\Model\Account::PHOTO_FILE_EXT;

        if(!$directoryRead->isExist($path)) {
            return false;
        }

        return $pathUrl;
    }

    public function isGlobalScope()
    {
        return $this->_objectManager->get('Magento\Customer\Model\Customer')->getSharingConfig()->isGlobalScope();
        // return (bool)($this->getConfig('customer/account_share/scope') == 0);
    }

    public function getRedirect()
    {
        return [
            'login' => $this->getConfig($this->_configSectionId .'/general/redirect_for_login'),
            'login_link' => $this->getConfig($this->_configSectionId .'/general/redirect_for_login_link'),
            'register' => $this->getConfig($this->_configSectionId .'/general/redirect_for_register'),
            'register_link' => $this->getConfig($this->_configSectionId .'/general/redirect_for_register_link'),
        ];
    }

    public function getCallbackURL($provider, $byRequest = false)
    {
        $request = $this->_getRequest();
        $websiteCode = $request->getParam('website');

        $storeManager = $this->_objectManager->get('Magento\Store\Model\StoreManager');

        $defaultStoreId = $storeManager
            ->getWebsite( $byRequest? $websiteCode : null )
            ->getDefaultGroup()
            ->getDefaultStoreId();

        if(!$defaultStoreId) {
            $websites = $storeManager->getWebsites(true);
            foreach($websites as $website) {
                $defaultStoreId = $website
                    ->getDefaultGroup()
                    ->getDefaultStoreId();

                if ($defaultStoreId) {
                    break;
                }
            }
        }

        if(!$defaultStoreId) {
            $defaultStoreId = 1;
        }

        $url = $storeManager->getStore($defaultStoreId)->getUrl('pslogin/account/login', ['type' => $provider, 'key' => null, '_nosid' => true]);

        $url = str_replace(
            '/' . $this->_objectManager->get('Magento\Backend\Helper\Data')->getAreaFrontName() . '/',
            '/',
            $url
        );

        if(false !== ($length = stripos($url, '?'))) {
            $url = substr($url, 0, $length);
        }

        if($byRequest) {
            /*if($this->getConfig('web/url/use_store')) {
                // $url = str_replace('admin/', '', $url);
            }*/
            if($this->getConfig('web/seo/use_rewrites')) {
                $url = str_replace('index.php/', '', $url);
            }
        }

        return $url;
    }

    public function getTypes($onlyEnabled = true)
    {
        $groups = $this->getConfig($this->_configSectionId);

        unset(
            $groups['general'],
            $groups['share']
        );

        $types = [];
        foreach ($groups as $name => $fields) {
            if($onlyEnabled && empty($fields['enable'])) {
                continue;
            }
            $types[] = $name;
        }

        return $types;
    }

    public function getButtons()
    {
        if (null === $this->_buttons) {
            $types = $this->getTypes(false);

            $this->_buttons = [];
            foreach ($types as $type) {
                $type = $this->_objectManager->get('Plumrocket\SocialLoginFree\Model\\'. ucfirst($type));

                if($type->enabled()) {
                    $button = $type->getButton();
                    $this->_buttons[ $button['type'] ] = $button;
                }
            }
        }
        return $this->_buttons;
    }

    public function getPreparedButtons($part = null)
    {
        if(null === $this->_buttonsPrepared) {
            $this->_buttonsPrepared = [
                'visible' => [],
                'hidden' => []
            ];
            $buttons = $this->getButtons();

            $storeName = $this->_getRequest()->getParam('store');
            $sortableString = $this->getConfig($this->_configSectionId .'/general/sortable', $storeName);
            $sortable = null;
            parse_str($sortableString, $sortable);

            if(is_array($sortable)) {
                foreach ($sortable as $partName => $partButtons) {
                    foreach ($partButtons as $button) {
                        if(isset($buttons[$button])) {
                            $this->_buttonsPrepared[$partName][] = $buttons[$button];
                            unset($buttons[$button]);
                        }
                    }
                }

                // If has not sortabled enabled buttons.
                if(!empty($buttons)) {
                    if(empty($this->_buttonsPrepared['visible'])) {
                        $this->_buttonsPrepared['visible'] = [];
                    }
                    $this->_buttonsPrepared['visible'] = array_merge($this->_buttonsPrepared['visible'], $buttons);
                }

                // If visible list is empty.
                if(empty($this->_buttonsPrepared['visible'])) {
                    $this->_buttonsPrepared['visible'] = $this->_buttonsPrepared['hidden'];
                    $this->_buttonsPrepared['hidden'] = [];
                }

                // Set visible.
                foreach($this->_buttonsPrepared['visible'] as &$btn) {
                    $btn['visible'] = true;
                }
            }
        }

        return isset($this->_buttonsPrepared[$part]) ?
                $this->_buttonsPrepared[$part] :
                array_merge($this->_buttonsPrepared['visible'], $this->_buttonsPrepared['hidden']);
    }

    public function refererLink($value = false)
    {
        // Customer session.
        $session = $this->_objectManager->get('Magento\Customer\Model\Session');
        $prevValueByCustomer = $session->getData(self::REFERER_QUERY_PARAM_NAME);

        if($value) {
            $session->setData(self::REFERER_QUERY_PARAM_NAME, $value);
        }elseif($value === null) {
            $session->unsetData(self::REFERER_QUERY_PARAM_NAME);
        }

        return $prevValueByCustomer;
    }

    public function getCookieRefererLink()
    {
        // $referer = $this->_objectManager->get('Magento\Framework\Stdlib\Cookie\PhpCookieManager')
        return $cookieReferer = $this->_objectManager->get('Magento\Framework\Stdlib\CookieManagerInterface')
            ->getCookie(self::REFERER_QUERY_PARAM_NAME);
    }

    public function refererStore($value = false)
    {
        // Customer session.
        $session = $this->_objectManager->get('Magento\Customer\Model\Session');
        $prevValueByCustomer = $session->getData(self::REFERER_STORE_PARAM_NAME);

        if($value) {
            $session->setData(self::REFERER_STORE_PARAM_NAME, $value);
        }elseif($value === null) {
            $session->unsetData(self::REFERER_STORE_PARAM_NAME);
        }

        return $prevValueByCustomer;
    }

    public function getRefererLinkSkipModules()
    {
        return ['customer/account', /*'checkout',*/ 'pslogin/account'];
    }

    public function showPopup()
    {
        $cookieManager = $this->_objectManager->get('Magento\Framework\Stdlib\CookieManagerInterface');
        $publicCookieMetadata = $this->_objectManager->create(
            'Magento\Framework\Stdlib\Cookie\PublicCookieMetadata',
            ['metadata' => []]
        );
        $publicCookieMetadata
            ->setDuration(600)
            ->setPath('/');

        $cookieManager->setPublicCookie(self::SHOW_POPUP_PARAM_NAME, 1, $publicCookieMetadata);
    }

    public function apiCall($params = null)
    {
        $session = $this->_objectManager->get('Magento\Customer\Model\Session');
        $show = $session->getData(self::API_CALL_PARAM_NAME);

        if($params) {
            $session->setData(self::API_CALL_PARAM_NAME, $params);
        }else{
            $session->unsetData(self::API_CALL_PARAM_NAME);
        }

        return $show;
    }

    public function getRedirectUrl($after = 'login')
    {
        $redirectUrl = null;
        $redirect = $this->getRedirect();
        switch($redirect[$after]) {

            case '__referer__':
                $links = [];
                if ($referer = $this->_getRequest()->getParam(\Magento\Customer\Model\Url::REFERER_QUERY_PARAM_NAME)) {
                    $links[] = $this->urlDecoder->decode($referer);
                }

                /*if ($referer = $this->getCookieRefererLink()) {
                    $links[] = $referer;
                }*/

                if ($referer = $this->refererLink()) {
                    $links[] = $referer;
                }

                foreach ($links as $url) {
                    // Rebuild referer URL to handle the case when SID was changed
                    $referer = $this->_objectManager->get('Magento\Framework\Url')
                        ->getRebuiltUrl($url);

                    if ($this->isUrlInternal($referer)) {
                        $redirectUrl = $referer;
                        break;
                    }
                }

                break;

            case '__custom__':
                $redirectUrl = $redirect["{$after}_link"];
                if (!$this->isUrlInternal($redirectUrl)) {
                    $redirectUrl = $this->_objectManager->get('Magento\Store\Model\Store')->getBaseUrl() . $redirectUrl;
                }
                break;

            case '__dashboard__':
                $redirectUrl = $this->_objectManager->get('Magento\Customer\Model\Url')->getDashboardUrl();
                break;

            default:
                if(is_numeric($redirect[$after])) {
                    $redirectUrl = $this->_objectManager->get('Magento\Cms\Helper\Page')->getPageUrl($redirect[$after]);
                }
        }

        if (!$redirectUrl) {
            $redirectUrl = $this->_objectManager->get('Magento\Customer\Model\Url')->getDashboardUrl();
        }

        return $redirectUrl;
    }

    public function isUrlInternal($url)
    {
        return (stripos($url, 'http') === 0);
    }

    public function moduleInvitationsEnabled()
    {
        return $this->moduleExists('Invitations') === 2;
    }

    public function hasIntegrationModules()
    {
        return $this->moduleExists('Popuplogin') || $this->moduleExists('Newsletterpopup') || $this->moduleExists('AdvancedReviewAndReminder');
    }

    public function isFakeMail($email = null)
    {
        if(null === $email) {
            $session = $this->_objectManager->get('Magento\Customer\Model\Session');
            if($session->isLoggedIn()) {
                $email = $session->getCustomer()->getEmail();
            }
        }
        return (bool)(strpos($email, self::FAKE_EMAIL_PREFIX) === 0);
    }

    public function getCheckoutJsViewAuthentication()
    {
        if ($this->moduleEnabled() && $this->getConfig($this->_configSectionId.'/general/replace_templates')) {
            $viewFile = 'Plumrocket_SocialLoginFree/js/view/checkout/authentication';
        } else {
            $viewFile = 'Magento_Checkout/js/view/authentication';
        }

        return $viewFile;
    }

    public function getCheckoutJsViewFormElementEmail()
    {
        if ($this->moduleEnabled() && $this->getConfig($this->_configSectionId.'/general/replace_templates')) {
            $viewFile = 'Plumrocket_SocialLoginFree/js/view/checkout/form/element/email';
        } else {
            $viewFile = 'Magento_Checkout/js/view/form/element/email';
        }

        return $viewFile;
    }

    public function getCustomerJsViewAuthenticationPopup()
    {
        if ($this->moduleEnabled() && $this->getConfig($this->_configSectionId.'/general/replace_templates')) {
            $viewFile = 'Plumrocket_SocialLoginFree/js/view/customer/authentication-popup';
        } else {
            $viewFile = 'Magento_Customer/js/view/authentication-popup';
        }

        return $viewFile;
    }

    public function getDebugMode()
    {
        return self::DEBUG_MODE;
    }
}