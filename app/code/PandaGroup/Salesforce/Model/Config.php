<?php

namespace PandaGroup\Salesforce\Model;

use Magento\Store\Model\ScopeInterface;

class Config extends \Magento\Framework\Model\AbstractModel
{
    /** Section */
    const PANDAGROUP_SALESFORCE_SECTION = 'pandagroup_salesforce/';

    /** Groups */
    const BASE_SETTINGS_GROUP       = 'salesforce_base_settings/';
    const API_SETTINGS_GROUP        = 'salesforce_api_settings/';
    const ADVANCED_SETTINGS_GROUP   = 'salesforce_advanced_settings/';
    const SYNC_SETTINGS_GROUP       = 'salesforce_sync_settings/';
    const INTERNAL_DATA_GROUP       = 'salesforce_internal_data/';

    /** Fields */
    const ENABLE_STATUS_FIELD           = 'status_enable';
    const API_DEFAULT_WDSL_FIELD        = 'api_defaultwsdl_text';
    const API_CLIENT_ID_FIELD           = 'api_clientid_text';
    const API_CLIENT_SECRET_FIELD       = 'api_clientsecret_text';
    const API_APP_SIGNATURE_FIELD       = 'api_appsignature_text';
    const API_XML_LOC_FIELD             = 'api_xmlloc_text';
    const API_PROXY_HOST_FIELD          = 'api_proxyhost_text';
    const API_PROXY_PORT_FIELD          = 'api_proxyport_text';
    const API_PROXY_USERNAME_FIELD      = 'api_proxyusername_text';
    const API_PROXY_PASSWORD_FIELD      = 'api_proxypassword_text';
    const API_BASE_URL_FIELD            = 'api_baseUrl_text';
    const API_BASE_AUTH_URL_FIELD       = 'api_baseAuthUrl_text';
    const DATA_EXTENSION_PREFIX_FIELD   = 'data_extension_prefix_text';
    const START_FROM_DATE_FIELD         = 'start_from_date';
    const MAX_ROW_QTY_PER_SYNC_FIELD    = 'max_row_qty_per_sync_text';
    const DEBUG_STATUS_FIELD            = 'debug_enable';

    /** Internal fields */
    const LAST_UPLOAD_ENTITY_ID_FROM_CREATED    = 'last_upload_entity_id_from_created';
    const LAST_UPLOAD_ENTITY_ID_FROM_UPDATED    = 'last_upload_entity_id_from_updated';
    const LAST_UPLOAD_DATE                      = 'last_upload_date';
    const LAST_UPLOAD_STATUS                    = 'last_upload_status';

    /** @var \Magento\Framework\App\Filesystem\DirectoryList  */
    protected $directoryList;

    /** @var \Psr\Log\LoggerInterface  */
    protected $logger;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface  */
    protected $scopeConfig;

    /** @var \Magento\Framework\App\Config\ConfigResource\ConfigInterface  */
    protected $configResource;


    /**
     * Config constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \PandaGroup\Salesforce\Logger\Logger $logger
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \PandaGroup\Salesforce\Logger\Logger $logger,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface $configResource
    ) {
        parent::__construct($context,$registry);
        $this->directoryList = $directoryList;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->configResource = $configResource;
    }

    /**
     * Retrieve enable status used to turn on/off logging
     *
     * @return bool
     */
    public function getEnableStatus()
    {
        return (bool) $this->scopeConfig->getValue(
            self::PANDAGROUP_SALESFORCE_SECTION . self::BASE_SETTINGS_GROUP . self::ENABLE_STATUS_FIELD,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve debug status used to turn on/off logging
     *
     * @return bool
     */
    public function getDebugStatus()
    {
        return (bool) $this->scopeConfig->getValue(
            self::PANDAGROUP_SALESFORCE_SECTION . self::ADVANCED_SETTINGS_GROUP . self::DEBUG_STATUS_FIELD,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve prefix for data extensions customer keys
     *
     * @return string
     */
    public function getDataExtensionPrefix()
    {
        return (string) $this->scopeConfig->getValue(
            self::PANDAGROUP_SALESFORCE_SECTION . self::SYNC_SETTINGS_GROUP . self::DATA_EXTENSION_PREFIX_FIELD,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve date of start sync
     *
     * @return string
     */
    public function getStartSyncFromDate()
    {
        $settingDate = $this->scopeConfig->getValue(
            self::PANDAGROUP_SALESFORCE_SECTION . self::SYNC_SETTINGS_GROUP . self::START_FROM_DATE_FIELD,
            ScopeInterface::SCOPE_STORE
        );
        $fromDate = date_create_from_format('d/m/Y', $settingDate);
        $fromDate = $fromDate->format('Y-m-d');
        return $fromDate;
    }

    /**
     * Retrieve the maximum row quantity per one synchronisation
     *
     * @return int
     */
    public function getMaxRowQtyPerSync()
    {
        return (int) $this->scopeConfig->getValue(
            self::PANDAGROUP_SALESFORCE_SECTION . self::SYNC_SETTINGS_GROUP . self::MAX_ROW_QTY_PER_SYNC_FIELD,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve ...
     *
     * @return string|null
     */
    public function getLastUploadEntityIdFromCreated()
    {
        return $this->scopeConfig->getValue(
            self::PANDAGROUP_SALESFORCE_SECTION . self::INTERNAL_DATA_GROUP . self::LAST_UPLOAD_ENTITY_ID_FROM_CREATED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Set ...
     *
     * @param $entityId
     */
    public function setLastUploadEntityIdFromCreated($entityId)
    {
        $this->configResource->saveConfig(
            self::PANDAGROUP_SALESFORCE_SECTION . self::INTERNAL_DATA_GROUP . self::LAST_UPLOAD_ENTITY_ID_FROM_CREATED,
            $entityId,
            'default',
            0
        );
    }

    /**
     * Retrieve ...
     *
     * @return string|null
     */
    public function getLastUploadEntityIdFromUpdated()
    {
        return $this->scopeConfig->getValue(
            self::PANDAGROUP_SALESFORCE_SECTION . self::INTERNAL_DATA_GROUP . self::LAST_UPLOAD_ENTITY_ID_FROM_UPDATED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Set ...
     *
     * @param $entityId
     */
    public function setLastUploadEntityIdFromUpdated($entityId)
    {
        $this->configResource->saveConfig(
            self::PANDAGROUP_SALESFORCE_SECTION . self::INTERNAL_DATA_GROUP . self::LAST_UPLOAD_ENTITY_ID_FROM_UPDATED,
            $entityId,
            'default',
            0
        );
    }

    /**
     * Set last upload date
     *
     * @param $date
     */
    public function setLastUploadDate($date)
    {
        $this->configResource->saveConfig(
            self::PANDAGROUP_SALESFORCE_SECTION . self::INTERNAL_DATA_GROUP . self::LAST_UPLOAD_DATE,
            $date,
            'default',
            0
        );
    }

    /**
     * Set last upload status
     *
     * @param $status
     */
    public function setLastUploadStatus($status)
    {
        $this->configResource->saveConfig(
            self::PANDAGROUP_SALESFORCE_SECTION . self::INTERNAL_DATA_GROUP . self::LAST_UPLOAD_STATUS,
            $status,
            'default',
            0
        );
    }

    /**
     * Retrieve connection parameters
     *
     * @return array
     */
    public function getConnectionParameters()
    {
        $params = [];

        $defaultwsdl = (string) $this->scopeConfig->getValue(self::PANDAGROUP_SALESFORCE_SECTION . self::API_SETTINGS_GROUP . self::API_DEFAULT_WDSL_FIELD, ScopeInterface::SCOPE_STORE);
        if (false === empty($defaultwsdl)) {
            $params['defaultwsdl'] = $defaultwsdl;
        }

        $clientid = (string) $this->scopeConfig->getValue(self::PANDAGROUP_SALESFORCE_SECTION . self::API_SETTINGS_GROUP . self::API_CLIENT_ID_FIELD, ScopeInterface::SCOPE_STORE);
        if (false === empty($clientid)) {
            $params['clientid'] = $clientid;
        }

        $clientsecret = (string) $this->scopeConfig->getValue(self::PANDAGROUP_SALESFORCE_SECTION . self::API_SETTINGS_GROUP . self::API_CLIENT_SECRET_FIELD, ScopeInterface::SCOPE_STORE);
        if (false === empty($clientsecret)) {
            $params['clientsecret'] = $clientsecret;
        }

        $appsignature = (string) $this->scopeConfig->getValue(self::PANDAGROUP_SALESFORCE_SECTION . self::API_SETTINGS_GROUP . self::API_APP_SIGNATURE_FIELD, ScopeInterface::SCOPE_STORE);
        if (false === empty($appsignature)) {
            $params['appsignature'] = $appsignature;
        }

        $xmlloc = (string) $this->scopeConfig->getValue(self::PANDAGROUP_SALESFORCE_SECTION . self::API_SETTINGS_GROUP . self::API_XML_LOC_FIELD, ScopeInterface::SCOPE_STORE);
        if (false === empty($xmlloc)) {
            $params['xmlloc'] = $xmlloc;
        }

        $proxyhost = (string) $this->scopeConfig->getValue(self::PANDAGROUP_SALESFORCE_SECTION . self::API_SETTINGS_GROUP . self::API_PROXY_HOST_FIELD, ScopeInterface::SCOPE_STORE);
        if (false === empty($proxyhost)) {
            $params['proxyhost'] = $proxyhost;
        }

        $proxyport = (string) $this->scopeConfig->getValue(self::PANDAGROUP_SALESFORCE_SECTION . self::API_SETTINGS_GROUP . self::API_PROXY_PORT_FIELD, ScopeInterface::SCOPE_STORE);
        if (false === empty($proxyport)) {
            $params['proxyport'] = $proxyport;
        }

        $proxyusername = (string) $this->scopeConfig->getValue(self::PANDAGROUP_SALESFORCE_SECTION . self::API_SETTINGS_GROUP . self::API_PROXY_USERNAME_FIELD, ScopeInterface::SCOPE_STORE);
        if (false === empty($proxyusername)) {
            $params['proxyusername'] = $proxyusername;
        }

        $proxypassword = (string) $this->scopeConfig->getValue(self::PANDAGROUP_SALESFORCE_SECTION . self::API_SETTINGS_GROUP . self::API_PROXY_PASSWORD_FIELD, ScopeInterface::SCOPE_STORE);
        if (false === empty($proxypassword)) {
            $params['proxypassword'] = $proxypassword;
        }

        $baseUrl = (string) $this->scopeConfig->getValue(self::PANDAGROUP_SALESFORCE_SECTION . self::API_SETTINGS_GROUP . self::API_BASE_URL_FIELD, ScopeInterface::SCOPE_STORE);
        if (false === empty($baseUrl)) {
            $params['baseUrl'] = $baseUrl;
        }

        $baseAuthUrl = (string) $this->scopeConfig->getValue(self::PANDAGROUP_SALESFORCE_SECTION . self::API_SETTINGS_GROUP . self::API_BASE_AUTH_URL_FIELD, ScopeInterface::SCOPE_STORE);
        if (false === empty($baseAuthUrl)) {
            $params['baseAuthUrl'] = $baseAuthUrl;
        }

        return $params;
    }
}
