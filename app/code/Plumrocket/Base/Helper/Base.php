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

class Base extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_objectManager;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Helper\Context $context
    ) {

        $this->_objectManager = $objectManager;
        parent::__construct($context);
    }


    public function getConfigSectionId()
    {
        return $this->_configSectionId;
    }


    public function getConfig($path, $store = null, $scope = null)
    {
        if ($scope === null) {
            $scope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        }
        return $this->scopeConfig->getValue($path, $scope, $store);
    }


    public static function backtrace($title = 'Debug Backtrace:', $echo = true)
    {
        $output     = "";
        $output .= "<hr /><div>" . $title . '<br /><table border="1" cellpadding="2" cellspacing="2">';

        $stacks     = debug_backtrace();

        $output .= "<thead><tr><th><strong>File</strong></th><th><strong>Line</strong></th><th><strong>Function</strong></th>".
            "</tr></thead>";
        foreach($stacks as $_stack)
        {
            if (!isset($_stack['file'])) $_stack['file'] = '[PHP Kernel]';
            if (!isset($_stack['line'])) $_stack['line'] = '';

            $output .=  "<tr><td>{$_stack["file"]}</td><td>{$_stack["line"]}</td>".
                "<td>{$_stack["function"]}</td></tr>";
        }
        $output .=  "</table></div><hr /></p>";
        if ($echo) {
            echo $output;
        } else {
            return $output;
        }
    }

    public function moduleExists($moduleName)
    {
        $hasModule = $this->_objectManager->get('Magento\Framework\Module\Manager')->isEnabled('Plumrocket_' . $moduleName);
        if($hasModule) {
            return $this->_objectManager->get('Plumrocket\\'. $moduleName .'\Helper\Data')->moduleEnabled()? 2 : 1;
        }

        return false;
    }

}
