<?php
namespace WeltPixel\Backend\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Logger extends \Magento\Framework\Logger\Monolog
{
    const XML_PATH_WELTPIXEL_DEVELOPER_LOGGING = 'weltpixel_backend_developer/logging/disable_broken_reference';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Logger constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param array $name
     * @param array $handlers
     * @param array $processors
     */
    public function __construct(ScopeConfigInterface $scopeConfig, $name, array $handlers = [], array $processors = [])
    {
        $this->scopeConfig = $scopeConfig;
        $handlers = array_values($handlers);

        parent::__construct($name, $handlers, $processors);
    }

    /**
     * Adds a log record at the WARNING level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    public function warning($message, array $context = array())
    {
        $result = $this->_parseLogMessage($message, $context);
        if ($result !== false) {
            return parent::warning($message, $context);
        }
        return $result;
    }

    /**
     * Adds a log record at the INFO level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    public function info($message, array $context = array())
    {
        $result = $this->_parseLogMessage($message, $context);
        if ($result !== false) {
            return parent::info($message, $context);
        }
        return $result;
    }

    /**
     * @param $message
     * @param array $context
     * @return Boolean
     */
    protected function _parseLogMessage($message, $context)
    {
        $isLogEnabled = $this->scopeConfig->getValue(self::XML_PATH_WELTPIXEL_DEVELOPER_LOGGING, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $pos = strpos($message, 'Broken reference');
        if (!$isLogEnabled && ($pos !== false) ) {
            return false;
        }

        return true;
    }
}
