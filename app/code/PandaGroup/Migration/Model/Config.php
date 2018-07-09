<?php

namespace PandaGroup\Migration\Model;

class Config extends \Magento\Framework\Model\AbstractModel
{
    /** Section */
    const STORE_LOCATOR_SECTION = 'pandagroup_migration/';

    /** Groups */
    const MIGRATION_BASE_SETTINGS_GROUP     = 'migration_basic_settings/';
    const MIBRATION_BUTTONS_SETTINGS_GROUP  = 'migration_buttons/';

    /** Fields */
    const MIGRATION_MODE_FIELD  = 'migration_mode';


    /** @var \Psr\Log\LoggerInterface  */
    protected $logger;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface  */
    protected $scopeConfig;

    /** @var \Magento\Framework\App\Config\ConfigResource\ConfigInterface  */
    protected $configResource;

    /** @var \Magento\Framework\App\Config\Storage\WriterInterface  */
    protected $configWriter;

    /** @var \Magento\Framework\App\Cache\TypeListInterface  */
    protected $cacheTypeList;

    /** @var \Magento\Framework\App\Cache\Frontend\Pool  */
    protected $cacheFrontendPool;

    protected $configModel;


    /**
     * Config constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \PandaGroup\Migration\Logger\Logger $logger
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\App\Config\ConfigResource\ConfigInterface $configResource
     * @param \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
     * @param \Magento\Config\Model\ResourceModel\Config $configModel
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \PandaGroup\Migration\Logger\Logger $logger,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface $configResource,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Magento\Config\Model\ResourceModel\Config $configModel
    ) {
        parent::__construct($context, $registry);
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->configResource = $configResource;
        $this->configWriter = $configWriter;
        $this->cacheTypeList = $cacheTypeList;
        $this->cacheFrontendPool = $cacheFrontendPool;
        $this->configModel = $configModel;
    }

    /**
     * Retrieve Migration Mode
     *
     * @param null $store
     * @return string
     */
    public function getMigrationMode($store = null)
    {
        return (string) $this->scopeConfig->getValue(
            self::STORE_LOCATOR_SECTION . self::MIGRATION_BASE_SETTINGS_GROUP . self::MIGRATION_MODE_FIELD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Retrieve theme id
     *
     * @return int
     */
    public function getThemeId()
    {
        return (int) $this->scopeConfig->getValue(
            'design/theme/theme_id',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES,
            1
        );
    }

    /**
     * Set theme id
     *
     * @param $themeId
     */
    public function setThemeId($themeId)
    {
        try {
            $this->configResource->saveConfig(
                'design/theme/theme_id',
                $themeId,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORES,
                1
            );

//            $this->configWriter->save('design/theme/theme_id',  $themeId);
//
//            $this->configModel->saveConfig(
//                'design/theme/theme_id',
//                $themeId,
//                'default',
//                0
//            );

        } catch (\Exception $e) {
            $this->logger->addError('Error while saving theme id', $e->getMessage());
        }

        $this->cleanCache();
    }


    public function cleanCache()
    {
        $types = array('config','layout','block_html','collections','reflection','db_ddl','eav','config_integration','config_integration_api','full_page','translate','config_webservice');
        foreach ($types as $type) {
            $this->cacheTypeList->cleanType($type);
        }
        foreach ($this->cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
    }

}
