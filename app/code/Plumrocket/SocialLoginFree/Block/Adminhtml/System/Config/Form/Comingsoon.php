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

class Comingsoon extends \Magento\Config\Block\System\Config\Form\Field
{
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return '<div style="padding:10px;background-color:#fff;color:#d83820;border:1px solid #ddd;margin-bottom:7px;">'.
			__('This network is coming soon. It will be available via free update.')
		.'</div>';
    }		            
}