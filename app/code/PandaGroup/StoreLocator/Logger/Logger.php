<?php

namespace PandaGroup\StoreLocator\Logger;

class Logger extends \Monolog\Logger
{
    private function canLog()
    {
        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();

        /** @var \PandaGroup\StoreLocator\Helper\ConfigProvider $configProvider */
        $configProvider = $objectManager->create('PandaGroup\StoreLocator\Helper\ConfigProvider');
        return $configProvider->getDebugStatus();
    }

    public function addDebug($message, array $context = array())
    {
        if (false === $this->canLog()) return true;
        return $this->addRecord(static::DEBUG, $message, $context);
    }

    public function addInfo($message, array $context = array())
    {
        if (false === $this->canLog()) return true;
        return $this->addRecord(static::INFO, $message, $context);
    }

    public function addNotice($message, array $context = array())
    {
        return $this->addRecord(static::NOTICE, $message, $context);
    }

    public function addWarning($message, array $context = array())
    {
        return $this->addRecord(static::WARNING, $message, $context);
    }

    public function addError($message, array $context = array())
    {
        return $this->addRecord(static::ERROR, $message, $context);
    }

    public function addCritical($message, array $context = array())
    {
        return $this->addRecord(static::CRITICAL, $message, $context);
    }

    public function addAlert($message, array $context = array())
    {
        return $this->addRecord(static::ALERT, $message, $context);
    }

    public function addEmergency($message, array $context = array())
    {
        return $this->addRecord(static::EMERGENCY, $message, $context);
    }

    public function debug($message, array $context = array())
    {
        return $this->addRecord(static::DEBUG, $message, $context);
    }

    public function info($message, array $context = array())
    {
        if (false === $this->canLog()) return true;
        return $this->addRecord(static::INFO, $message, $context);
    }

    public function notice($message, array $context = array())
    {
        if (false === $this->canLog()) return true;
        return $this->addRecord(static::NOTICE, $message, $context);
    }

    public function warn($message, array $context = array())
    {
        if (false === $this->canLog()) return true;
        return $this->addRecord(static::WARNING, $message, $context);
    }

    public function warning($message, array $context = array())
    {
        if (false === $this->canLog()) return true;
        return $this->addRecord(static::WARNING, $message, $context);
    }

    public function err($message, array $context = array())
    {
        return $this->addRecord(static::ERROR, $message, $context);
    }

    public function error($message, array $context = array())
    {
        if (false === $this->canLog()) return true;
        return $this->addRecord(static::ERROR, $message, $context);
    }

    public function crit($message, array $context = array())
    {
        return $this->addRecord(static::CRITICAL, $message, $context);
    }

    public function critical($message, array $context = array())
    {
        return $this->addRecord(static::CRITICAL, $message, $context);
    }

    public function alert($message, array $context = array())
    {
        return $this->addRecord(static::ALERT, $message, $context);
    }

    public function emerg($message, array $context = array())
    {
        return $this->addRecord(static::EMERGENCY, $message, $context);
    }
}
