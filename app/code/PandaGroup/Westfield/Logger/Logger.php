<?php

namespace PandaGroup\Westfield\Logger;

 class Logger extends \Monolog\Logger
{
     const WESTFIELD_DEBUG_MODE = 'light4website_westfield/settings/debug_mode';

     protected $debugMode = null;

     public function logToFile($message, $level = 100, $force = false)
     {
         if ($this->isDebugMode() || $force) {
             $this->addRecord($level, $message);
         }
     }

     protected function isDebugMode()
     {
         if (is_null($this->debugMode)) {
             $om = \Magento\Framework\App\ObjectManager::getInstance();
             $scopeConfig = $om->create('\Magento\Framework\App\Config\ScopeConfigInterface');
             $this->debugMode = (bool)$scopeConfig->getValue(self::WESTFIELD_DEBUG_MODE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
         }

         return $this->debugMode;
     }
}
