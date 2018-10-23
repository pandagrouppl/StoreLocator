<?php

namespace WeltPixel\Backend\Plugin;

use Magento\Framework\App\Config\ScopeConfigInterface;
use \Psr\Log\LoggerInterface;

/**
 * @deprecated since Magento 2.2.4
 */
class Logger
{

    const XML_PATH_WELTPIXEL_DEVELOPER_LOGGING = 'weltpixel_backend_developer/logging/disable_broken_reference';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param LoggerInterface $subject
     * @param \Closure $proceed
     * @param string $message
     * @param array $context
     * @return Boolean
     */
    public function aroundWarning(
        LoggerInterface $subject,
        \Closure $proceed,
        $message,
        array $context = []
    )
    {
        $result = $this->_parseLogMessage($proceed, $message, $context);
        return $result;
    }

    /**
     * @param LoggerInterface $subject
     * @param \Closure $proceed
     * @param string $message
     * @param array $context
     * @return Boolean
     */
    public function aroundInfo(
        LoggerInterface $subject,
        \Closure $proceed,
        $message,
        array $context = []
    )
    {
        $result = $this->_parseLogMessage($proceed, $message, $context);
        return $result;
    }

    /**
     * @param \Closure $proceed ,
     * @param $message
     * @param array $context
     * @return Boolean
     */
    protected function _parseLogMessage($proceed, $message, $context)
    {
        $isLogEnabled = $this->scopeConfig->getValue(self::XML_PATH_WELTPIXEL_DEVELOPER_LOGGING, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $pos = strpos($message, 'Broken reference');
        if (!$isLogEnabled && ($pos !== false) ) {
            return false;
        }

        $result = $proceed($message, $context);
        return $result;
    }
}
