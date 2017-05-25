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
 * @package     Plumrocket_PopupLogin
 * @copyright   Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

namespace Plumrocket\Popuplogin\Helper;

class Data extends Main
{
    protected $_configSectionId = 'prpopuplogin';

    public function moduleEnabled($store = null)
    {
        return (bool)$this->getConfig($this->_configSectionId.'/general/enabled')
            && ((bool)$this->getConfig($this->_configSectionId.'/login/show')
            || (bool)$this->getConfig($this->_configSectionId.'/registration/show')
            || (bool)$this->getConfig($this->_configSectionId.'/forgotpassword/show'));
    }


    public function getConfigSectionId()
    {
        return $this->_configSectionId;
    }


    public function disableExtension()
    {
        $resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection('core_write');
        $connection->delete(
            $resource->getTableName('core_config_data'),
            [$connection->quoteInto('path = ?', $this->_configSectionId.'/general/enabled')]
        );

        $config = $this->_objectManager->get('\Magento\Config\Model\Config');
        $config->setDataByPath($this->_configSectionId.'/general/enabled', 0);
        $config->save();
    }
}
