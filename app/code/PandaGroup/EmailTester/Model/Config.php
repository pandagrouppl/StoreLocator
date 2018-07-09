<?php

namespace PandaGroup\EmailTester\Model;

class Config extends \Magento\Framework\Model\AbstractModel
{
    /** Section */
    const EMAIL_TESTER_SECTION = 'pandagroup_emailtester/';

    /** Groups */
    const EMAIL_TESTER_BASE_SETTINGS_GROUP = 'emailtester_basic_settings/';

    /** Fields */
    const EMAIL_TESTER_EMAIL_FIELD = 'email_text';

    /** @var \Psr\Log\LoggerInterface  */
    protected $logger;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface  */
    protected $scopeConfig;


    /**
     * Config constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \PandaGroup\EmailTester\Logger\Logger $logger
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \PandaGroup\EmailTester\Logger\Logger $logger,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context, $registry);
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Retrieve EmailTester Email
     *
     * @param null $store
     * @return string
     */
    public function getEmailToSendTest($store = null)
    {
        return (string) $this->scopeConfig->getValue(
            self::EMAIL_TESTER_SECTION . self::EMAIL_TESTER_BASE_SETTINGS_GROUP . self::EMAIL_TESTER_EMAIL_FIELD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}
