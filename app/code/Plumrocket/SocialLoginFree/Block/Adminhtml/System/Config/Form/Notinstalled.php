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

class Notinstalled extends \Magento\Config\Block\System\Config\Form\Field
{
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
		// $config 		= \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Config\Model\Config');
    	// $moduleNode	= $config->getDataByPath('config');
        // $name 		= $moduleNode->name;
        $name 			= 'Twitter & Facebook Login';
        $url 			= 'https://store.plumrocket.com/magento-2-extensions/social-login-pro-magento2-extension.html';

        return '<div class="psloginfree-notinstalled" style="padding:10px;background-color:#fff;border:1px solid #ddd;margin-bottom:7px;">'.
			__('The free version of "%1" extension does not include this network. Please <a href="%2" target="_blank">upgrade to Social Login Pro magento extension</a> in order to receive 50+ social login networks.', $name, $url)
		.'</div>';
    }		            
}