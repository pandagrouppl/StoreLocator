<?php

namespace PandaGroup\GiftCardExtender\Model;

class Config extends \Magento\Framework\Model\AbstractModel
{
    const GIFT_CARD_REDIRECT_STATUS = 'pandagroup_gift_card/gift_card_extended_settings/redirects_enable';

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface  */
    protected $scopeConfig;


    /**
     * Config constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context, $registry);
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Retrieve status of redirect corrector (Redirect to #payment section on checkout)
     *
     * @return bool
     */
    public function getRedirectEnableStatus() {
        return (bool) $this->scopeConfig->getValue(
            self::GIFT_CARD_REDIRECT_STATUS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
