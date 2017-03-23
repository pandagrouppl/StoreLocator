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
 * @copyright   Copyright (c) 2016 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

namespace Plumrocket\SocialLoginFree\Controller\Account;

class LoginPost extends \Magento\Framework\App\Action\Action
{

    public function execute()
    {
        if ($redirectUrl = $this->getRequest()->getParam(\Magento\Customer\Model\Url::REFERER_QUERY_PARAM_NAME)) {
            $redirectUrl = base64_decode($redirectUrl);
            $this->getResponse()->setRedirect($redirectUrl);
        } else {
            $this->_redirect('/');
        }
    }

}