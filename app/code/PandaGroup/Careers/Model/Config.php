<?php

namespace PandaGroup\Careers\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractModel
{
    /**
     * Target path to save resume files
     *
     * @var string
     */
    const TARGET_PATH = DIRECTORY_SEPARATOR . 'careers' . DIRECTORY_SEPARATOR;

    /**
     * Configuration path to 'career_page_settings' settings group
     *
     * @var string
     */
    const CAREER_PAGE_SETTINGS = 'pandagroup_careers/career_page_settings/';

    /**
     * Configuration path to 'career_page_advanced_settings' settings group
     *
     * @var string
     */
    const CAREER_PAGE_ADVANCED_SETTINGS = 'pandagroup_careers/career_page_advanced_settings/';



    /** @var \Magento\Framework\App\Filesystem\DirectoryList  */
    protected $directoryList;

    /** @var \Psr\Log\LoggerInterface  */
    protected $logger;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface  */
    protected $scopeConfig;


    /**
     * Config constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \PandaGroup\Careers\Logger\Logger $logger
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \PandaGroup\Careers\Logger\Logger $logger,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context,$registry);
        $this->directoryList = $directoryList;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
    }


    /**
     * Retrieve Email address to send career message
     *
     * @return string
     */
    public function getCareerEmailConfig()
    {
        return (string) $this->scopeConfig->getValue(
            self::CAREER_PAGE_SETTINGS . 'email_text',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve allowed files extension which can be attached to the Email message
     *
     * @return string
     */
    public function getFliesExtension()
    {
        return (string) $this->scopeConfig->getValue(
            self::CAREER_PAGE_SETTINGS . 'extensions_text',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve target path used to save resume
     *
     * @return string
     */
    public function getTargetPath()
    {
        $pathDir = $this->directoryList->getPath('var');
        return $pathDir . self::TARGET_PATH;
    }

    /**
     * Retrieve status of 'Remove resume after send by Email'
     *
     * @return bool
     */
    public function getRemoveAfterSendingStatus() {

        return (bool) $this->scopeConfig->getValue(
            self::CAREER_PAGE_ADVANCED_SETTINGS . 'remove_enable',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve debug status used to turn on/off logging
     *
     * @return bool
     */
    public function getDebugStatus() {
        return (bool) $this->scopeConfig->getValue(
            self::CAREER_PAGE_ADVANCED_SETTINGS . 'debug_enable',
            ScopeInterface::SCOPE_STORE
        );
    }
}
