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

namespace Plumrocket\SocialLoginFree\Block\Adminhtml\System\Config\Form;

class Sortable extends \Magento\Config\Block\System\Config\Form\Field
{
    protected $_helper;

    public function _construct()
    {
        parent::_construct();

        $this->_helper = \Magento\Framework\App\ObjectManager::getInstance()->get('Plumrocket\SocialLoginFree\Helper\Data');
        $this->setTemplate('system/config/sortable.phtml');
    }

    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->element = $element;
        return $this->toHtml();
    }

    public function getButtons()
    {
        return $this->_helper->getButtons();
    }

    public function getPreparedButtons($part)
    {
        return $this->_helper->getPreparedButtons($part);
    }

}