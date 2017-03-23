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

namespace Plumrocket\SocialLoginFree\Block;

class General extends \Magento\Framework\View\Element\Template
{
    protected $_objectManager;
    protected $_dataHelper;

    protected function _construct()
    {
        parent::_construct();

        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_dataHelper = $this->_objectManager->get('Plumrocket\SocialLoginFree\Helper\Data');
        }

	protected function _toHtml()
    {
        if(!$this->_dataHelper->moduleEnabled()) {
            return;
        }

        return parent::_toHtml();
    }

    public function getSkipModules()
    {
        $skipModules = $this->_dataHelper->getRefererLinkSkipModules();
        return json_encode($skipModules);
    }
}