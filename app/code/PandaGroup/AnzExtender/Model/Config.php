<?php

namespace PandaGroup\AnzExtender\Model;

class Config extends \Magento\Framework\Model\AbstractModel
{
    /** Section */
    const ANZ_EXTENDER_SECTION = 'pandagroup_anz_extender/';

    /** Groups */
    const ANZ_EXTENDER_ADDITIONAL_SETTINGS_GROUP = 'anz_additional_settings/';

    /** Fields */
    const SEND_EMAIL_AFTER_CANCELED_ORDER_FIELD = 'send_email_after_canceled_order_enable';

    /** @var \PandaGroup\AnzExtender\Logger\Logger  */
    protected $logger;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface  */
    protected $scopeConfig;


    /**
     * Config constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \PandaGroup\AnzExtender\Logger\Logger $logger
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \PandaGroup\AnzExtender\Logger\Logger $logger,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context, $registry);
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Retrieve Canceled Order Customer Email Enable Status
     *
     * @param null $store
     * @return bool
     */
    public function canSendEmailToCustomerAfterCanceledOrder($store = null)
    {
        return (bool) $this->scopeConfig->getValue(
            self::ANZ_EXTENDER_SECTION . self::ANZ_EXTENDER_ADDITIONAL_SETTINGS_GROUP . self::SEND_EMAIL_AFTER_CANCELED_ORDER_FIELD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}
