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

namespace Plumrocket\Base\Helper;

class Data extends Main
{

    protected $_configSectionId = 'plumbase';


    public function moduleEnabled($store = null)
    {
        return true;
    }


    public function isAdminNotificationEnabled()
    {
        $m = 'Mage_Admin'.'Not'.'ification';
        return //(($module = Mage::getConfig()->getModuleConfig($m))
            //&& ($module->is('active', 'true'))
            //&& !Mage::getStoreConfig($this->_getAd().'/'.$m));
            !$this->scopeConfig->isSetFlag($this->_getAd().'/'.$m, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }


    protected function _getAd()
    {
        return 'adva'.'nced/modu'.
            'les_dis'.'able_out'.'put';
    }


}
