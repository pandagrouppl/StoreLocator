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
abstract class AbstractSystemConfig implements ObserverInterface
{
    protected $_objectManager;

    protected $_messageManager;
    protected $_cacheTypeList;
    protected $_eventManager;

    protected $_customer;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Message\Manager $messageManager,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\Event\ManagerInterface $eventManager
    ) {
        $this->_messageManager  = $messageManager;
        $this->_objectManager   = $objectManager;
        $this->_cacheTypeList = $cacheTypeList;
        $this->_eventManager = $eventManager;
    }


    protected function _getSection($observer)
    {
        $controller = $observer->getEvent()->getControllerAction();
        $req     = $controller->getRequest();
        $current = $req->getParam('section');
        $website = $req->getParam('website');
        $store   = $req->getParam('store');

        $_configStructure = $this->_objectManager->get('\Magento\Config\Model\Config\Structure');
        if (!$current) {
            $section = $_configStructure->getFirstSection();
        } else {
            $section = $_configStructure->getElement($current);
        }

        if ($section) {
            if ($this->_hasS($section)) {
                return $section;
            }
        }

        return false;
    }


    protected function _isPlumSection($section)
    {
        $data = $section->getData();
        if (isset($data['tab'])) {
            return (string) $data['tab'] == 'plu' . 'mroc' . 'ket';
        }
        return false;
    }


    protected function _hasS($section)
    {
        if (!$this->_isPlumSection($section)) {
            return false;
        }

        $scopeConfig = $this->_objectManager->get('\Magento\Framework\App\Config\ScopeConfigInterface');
        $v = $scopeConfig->getValue($section->getId() . '/' . 'gen'.'eral', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, 0);
        if (is_array($v)) {
            return (array_key_exists('ser' . strrev('lai'), $v));
        }

        return false;
    }


    protected function _getProductBySection($section)
    {
        $i = 'ser' . strrev('lai'); $j = 'gen'.'eral';
        foreach ($section->getChildren() as $group) {

            if ($group->getId() == $j) {
                foreach ($group->getChildren() as $field) {
                    if ($field->getId() == 'version') {
                        $d = $field->getData();
                        $r = explode('\\', $d['frontend_model']);

                        return $this->_objectManager->create('\Plumrocket\Base\Model\Product')->load($r[1]);
                    }
                }
            }
        }

        return null;
    }
}