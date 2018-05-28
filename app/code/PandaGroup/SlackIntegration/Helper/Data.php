<?php

namespace PandaGroup\SlackIntegration\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    protected $storeManager;
    protected $objectManager;

    const PATH_SLACKINTEGRATION_GENERAL = 'slackintegration_config/general/';
    const PATH_SLACKINTEGRATION_CHANNEL = 'slackintegration_config/channel/';

    public function __construct(Context $context,
                                ObjectManagerInterface $objectManager,
                                StoreManagerInterface $storeManager)
    {
        $this->objectManager = $objectManager;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    public function getConfigValue($filed, $storeId = null)
    {
        return $this->scopeConfig->getValue($filed, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::PATH_SLACKINTEGRATION_GENERAL . $code, $storeId);
    }

    public function getChannelConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::PATH_SLACKINTEGRATION_CHANNEL . $code, $storeId);
    }
}

?>