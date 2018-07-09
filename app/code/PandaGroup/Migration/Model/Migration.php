<?php

namespace PandaGroup\Migration\Model;

class Migration extends \Magento\Framework\Model\AbstractModel
{
    /** @var \PandaGroup\Migration\Logger\Logger  */
    protected $logger;

    /** @var \PandaGroup\Migration\Model\Config  */
    protected $config;

    /** @var \Magento\Framework\Message\ManagerInterface  */
    protected $messageManager;

    /** @var \Magento\Catalog\Model\ResourceModel\Eav\Attribute  */
    protected $attributeModel;

    /** @var \Magento\Catalog\Model\ResourceModel\Config  */
    protected $catalogConfig;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface  */
    protected $scopeConfig;

    /** @var \Magento\Framework\App\Config\ConfigResource\ConfigInterface  */
    protected $configResource;

    /** @var \Magento\Framework\App\Filesystem\DirectoryList  */
    protected $directoryList;


    /**
     * Migration constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \PandaGroup\Migration\Model\Config $config
     * @param \PandaGroup\Migration\Logger\Logger $logger
     * @param \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attributeModel
     * @param \Magento\Catalog\Model\ResourceModel\Config $catalogConfig
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \PandaGroup\Migration\Model\Config $config,
        \PandaGroup\Migration\Logger\Logger $logger,
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attributeModel,
        \Magento\Catalog\Model\ResourceModel\Config $catalogConfig,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface $configResource,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    ) {
        parent::__construct($context,$registry);
        $this->logger = $logger;
        $this->messageManager = $messageManager;
        $this->config = $config;
        $this->attributeModel = $attributeModel;
        $this->catalogConfig = $catalogConfig;
        $this->scopeConfig = $scopeConfig;
        $this->configResource = $configResource;
        $this->directoryList = $directoryList;
    }

    /**
     * Disable 'Size' attributes from Storefront filter select
     *
     * @return array
     */
    public function setUsedForSortByOptionToAttributes()
    {
        $attributes = $this->catalogConfig->getAttributesUsedForSortBy();

        $qtyUpdated = 0;
        $errorMessage = '';
        $attrLabels = '';
        foreach ($attributes as $attribute) {
            $attrId = $attribute['attribute_id'];
            $attrName = $attribute['store_label'];
            $attrLabel = $attribute['frontend_label'];

            if (false !== strpos($attrName, 'Size')) {
                $attributeModel = $this->attributeModel->load($attrId);
                $attributeModel->setData('used_for_sort_by', 0);
                try {
                    $attributeModel->save();
                    $qtyUpdated++;
                    $attrLabels .= $attrLabel . ', ';
                } catch (\Exception $e) {
                    $errorMessage .= $e->getMessage() . ' ';
                }
            }
        }

        if (true === empty($errorMessage)) {
            if ($qtyUpdated === 0) {
                $jsonMessage = 'There aren\'t any product attributes to update.';
            } else {
                $jsonMessage = 'Successfully updated ' . $qtyUpdated . ' of product attributes (' . $attrLabels .').';
            }
            $done = 1;
            $this->logger->addInfo($jsonMessage);
        } else {
            $done = 0;
            $jsonMessage = 'Attributes was not updated because of problem. Details: ' . $errorMessage;
            $this->logger->addError($jsonMessage);
        }

        $result = [
            'done'      => $done,
            'message'   => $jsonMessage
        ];

        return $result;
    }

    /**
     * Set 'Peter' theme
     *
     * @return array
     */
    public function setTheme()
    {
        $themeIdToSet = 4;

        $currentThemeId = $this->config->getThemeId();

        $done = 1;
        try {
            if ($currentThemeId !== $themeIdToSet) {
                $this->setThemeProperties();
                $this->setLogoImage();
                $this->config->setThemeId($themeIdToSet);
                //$this->setThemeInDatabase();
                $jsonMessage = 'Theme was updated successfully.';
                //$this->gulpTheme();
            } else {
                $jsonMessage = 'Theme is already correctly set.';
            }
            $this->logger->addInfo($jsonMessage);

            // Check if theme is updated
//            if ($currentThemeId != $this->config->getThemeId()) {
//                $done = 0;
//                $jsonMessage = 'Theme was not updated because of unknown problem.';
//                $this->logger->addError($jsonMessage);
//            } else {
//                $this->logger->addInfo($jsonMessage);
//            }
        } catch (\Exception $e) {
            $done = 0;
            $jsonMessage = 'Theme was not updated because of problem. Details: ' . $e->getMessage();
            $this->logger->addError($jsonMessage);
        }

        $result = [
            'done'      => $done,
            'message'   => $jsonMessage
        ];

        return $result;
    }

    public function gulpTheme()
    {
        $path = $this->directoryList->getRoot() . '/tools';
        $lastLine = system('cd ' . $path . ' && gulp compile', $retVal);
    }

    /**
     * Set theme type to physical
     *
     * @return bool
     */
    public function setThemeProperties()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');

        try {
            $connection = $resource->getConnection();
            $tableName = $resource->getTableName('theme');

            $sql = "UPDATE " . $tableName . " SET `type` = '0' WHERE `theme_path` = 'peterjacksons/petertheme'";
            $connection->query($sql);

        } catch (\Exception $e) {
            $this->logger->addError('Error while changing virtual theme type to physical', $e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Set logo image
     *
     * @return bool
     */
    public function setLogoImage()
    {
        try {
            $this->configResource->saveConfig(
                'design/header/logo_src',
                'stores/1/pj-logo-black_1.svg',
                'default',
                0
            );
        } catch (\Exception $e) {
            $this->logger->addError('Error while saving logo image configuration', $e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Set StoreLocator configuration
     *
     * @return array
     */
    public function setStoreLocatorConfiguration()
    {
        try {
            $this->configResource->saveConfig(
                'pandagroup_store_locator/store_locator_settings/api_key_text',
                'AIzaSyD06oeZOxRpKwKCg3G0pEilZmgunVdgTUA',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'pandagroup_store_locator/store_locator_settings/country_select',
                'AU',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'pandagroup_store_locator/store_locator_map_settings/lat_text',
                '-31.2532183',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'pandagroup_store_locator/store_locator_map_settings/lng_text',
                '146.921099',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'pandagroup_store_locator/store_locator_map_settings/zoom_select',
                '5',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'pandagroup_store_locator/store_locator_settings/time_format_select',
                '12',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'pandagroup_store_locator/store_locator_advanced/debug_enable',
                '1',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'pandagroup_store_locator/store_locator_map_settings/pin_image',
                'images/icon/pinpj2.png',
                'default',
                0
            );
            $done = 1;
            $jsonMessage = 'StoreLocator configuration was successfully set.';
        } catch (\Exception $e) {
            $done = 0;
            $jsonMessage = 'StoreLocator configuration was not updated because of problem. Details: ' . $e->getMessage();
            $this->logger->addError('Error while saving StoreLocator configuration', $e->getMessage());
        }

        $result = [
            'done'      => $done,
            'message'   => $jsonMessage
        ];

        return $result;
    }

    /**
     * Set ANZ Payment configuration
     *
     * @return array
     */
    public function setAnzPaymentConfiguration()
    {
        try {
            $this->configResource->saveConfig(
                'payment/migs/active',
                '0',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'payment/migs/title',
                'ANZ eGate Payment (2-Party Payments)',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'payment/migs/ssl_enable',
                '0',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'payment/migs/debug',
                '1',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'payment/migs/cctypes',
                'AE,VI,MC',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'payment/migs/payment_action',
                'authorize_capture',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'payment/migs/allowspecific',
                '0',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'payment/migs/sort_order',
                NULL,
                'default',
                0
            );
            $this->configResource->saveConfig(
                'payment/migs_hosted/active',
                '1',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'payment/migs_hosted/title',
                'ANZ eGate Payment (3-Party Payments)',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'payment/migs_hosted/use_3d',
                '1',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'payment/migs_hosted/debug',
                '1',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'payment/migs_hosted/allowspecific',
                '0',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'payment/migs_hosted/sort_order',
                NULL,
                'default',
                0
            );

            $done = 1;
            $jsonMessage = 'ANZ Payment configuration was successfully set.';
        } catch (\Exception $e) {
            $done = 0;
            $jsonMessage = 'ANZ Payment configuration was not updated because of problem. Details: ' . $e->getMessage();
            $this->logger->addError('Error while saving ANZ Payment configuration', $e->getMessage());
        }

        $result = [
            'done'      => $done,
            'message'   => $jsonMessage
        ];

        return $result;
    }

    /**
     * Set Careers configuration
     *
     * @return array
     */
    public function setCareersConfiguration()
    {
        try {
            $this->configResource->saveConfig(
                'pandagroup_careers/career_page_advanced_settings/debug_enable',
                '1',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'pandagroup_careers/career_page_advanced_settings/remove_enable',
                '0',
                'default',
                0
            );

            if ('production' === $this->config->getMigrationMode()) {
                $this->configResource->saveConfig(
                    'pandagroup_careers/career_page_settings/email_text',
                    'nick@peterjacksons.com',
                    'default',
                    0
                );
            } else {
                $this->configResource->saveConfig(
                    'pandagroup_careers/career_page_settings/email_text',
                    'kbrzozowski@light4website.com',
                    'default',
                    0
                );
            }

            $this->configResource->saveConfig(
                'pandagroup_careers/career_page_settings/extensions_text',
                '.doc, .docx, .txt, .pdf, .zip',
                'default',
                0
            );
            $done = 1;
            $jsonMessage = 'Careers configuration was successfully set.';
        } catch (\Exception $e) {
            $done = 0;
            $jsonMessage = 'Careers configuration was not updated because of problem. Details: ' . $e->getMessage();
            $this->logger->addError('Error while saving Careers configuration', $e->getMessage());
        }

        $result = [
            'done'      => $done,
            'message'   => $jsonMessage
        ];

        return $result;
    }

    /**
     * Set Varnish configuration
     *
     * @return array
     */
    public function setVarnishConfiguration()
    {
        try {
            $this->configResource->saveConfig(
                'system/full_page_cache/caching_application',
                '2',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'system/full_page_cache/varnish/backend_port',
                '8080',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'system/full_page_cache/varnish/backend_host',
                'localhost',
                'default',
                0
            );
            $done = 1;
            $jsonMessage = 'Varnish configuration was successfully set.';
        } catch (\Exception $e) {
            $done = 0;
            $jsonMessage = 'Varnish configuration was not updated because of problem. Details: ' . $e->getMessage();
            $this->logger->addError('Error while saving Varnish configuration', $e->getMessage());
        }

        $result = [
            'done'      => $done,
            'message'   => $jsonMessage
        ];

        return $result;
    }

    /**
     * Set Gmail SMTP configuration
     *
     * @return array
     */
    public function setGmailSmtpConfiguration()
    {
        try {
            $this->configResource->saveConfig(
                'system/gmailsmtpapp/debug/from_email',
                NULL,
                'default',
                0
            );
            $this->configResource->saveConfig(
                'system/gmailsmtpapp/debug/email',
                'kbrzozowski@light4website.com',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'system/gmailsmtpapp/set_return_path',
                '1',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'system/gmailsmtpapp/set_from',
                '1',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'system/gmailsmtpapp/set_reply_to',
                '1',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'system/gmailsmtpapp/password',
                '0:2:itXRl8UzD1vSiylHPx6eHiPgzJ3a0X99:oSkMpZkwjaIKH6QyAygD9GvNRPEaAzE8KZLCCDUe0Vc=',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'system/gmailsmtpapp/username',
                'brzozowskidevtest@gmail.com',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'system/gmailsmtpapp/smtpport',
                '587',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'system/gmailsmtpapp/smtphost',
                'smtp.gmail.com',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'system/gmailsmtpapp/ssl',
                'tls',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'system/gmailsmtpapp/auth',
                'LOGIN',
                'default',
                0
            );
            $this->configResource->saveConfig(
                'system/gmailsmtpapp/name',
                'localhost',
                'default',
                0
            );

            if ('production' === $this->config->getMigrationMode()) {
                $this->configResource->saveConfig(
                    'system/gmailsmtpapp/active',
                    '0',
                    'default',
                    0
                );
            } else {
                $this->configResource->saveConfig(
                    'system/gmailsmtpapp/active',
                    '1',
                    'default',
                    0
                );
            }
            $done = 1;
            $jsonMessage = 'Gmail SMTP configuration was successfully set.';
        } catch (\Exception $e) {
            $done = 0;
            $jsonMessage = 'Gmail SMTP configuration was not updated because of problem. Details: ' . $e->getMessage();
            $this->logger->addError('Error while saving Gmail SMTP configuration', $e->getMessage());
        }

        $result = [
            'done'      => $done,
            'message'   => $jsonMessage
        ];

        return $result;
    }

    public function clearStoreLocatorData()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');

        try {
            $connection = $resource->getConnection();
            $tableName = $resource->getTableName('storelocator');

            $sql = "UPDATE " . $tableName . " SET `monday_open` = CONCAT(`monday_open`, '0') WHERE `monday_open` LIKE '%:_'";
            $connection->query($sql);
            $sql = "UPDATE " . $tableName . " SET `monday_open_break` = CONCAT(`monday_open_break`, '0') WHERE `monday_open_break` LIKE '%:_'";
            $connection->query($sql);
            $sql = "UPDATE " . $tableName . " SET `monday_close` = CONCAT(`monday_close`, '0') WHERE `monday_close` LIKE '%:_'";
            $connection->query($sql);
            $sql = "UPDATE " . $tableName . " SET `monday_close_break` = CONCAT(`monday_close_break`, '0') WHERE `monday_close_break` LIKE '%:_'";
            $connection->query($sql);

            $sql = "UPDATE " . $tableName . " SET `tuesday_open` = CONCAT(`tuesday_open`, '0') WHERE `tuesday_open` LIKE '%:_'";
            $connection->query($sql);
            $sql = "UPDATE " . $tableName . " SET `tuesday_open_break` = CONCAT(`tuesday_open_break`, '0') WHERE `tuesday_open_break` LIKE '%:_'";
            $connection->query($sql);
            $sql = "UPDATE " . $tableName . " SET `tuesday_close` = CONCAT(`tuesday_close`, '0') WHERE `tuesday_close` LIKE '%:_'";
            $connection->query($sql);
            $sql = "UPDATE " . $tableName . " SET `tuesday_close_break` = CONCAT(`tuesday_close_break`, '0') WHERE `tuesday_close_break` LIKE '%:_'";
            $connection->query($sql);

            $sql = "UPDATE " . $tableName . " SET `wednesday_open` = CONCAT(`wednesday_open`, '0') WHERE `wednesday_open` LIKE '%:_'";
            $connection->query($sql);
            $sql = "UPDATE " . $tableName . " SET `wednesday_open_break` = CONCAT(`wednesday_open_break`, '0') WHERE `wednesday_open_break` LIKE '%:_'";
            $connection->query($sql);
            $sql = "UPDATE " . $tableName . " SET `wednesday_close` = CONCAT(`wednesday_close`, '0') WHERE `wednesday_close` LIKE '%:_'";
            $connection->query($sql);
            $sql = "UPDATE " . $tableName . " SET `wednesday_close_break` = CONCAT(`wednesday_close_break`, '0') WHERE `wednesday_close_break` LIKE '%:_'";
            $connection->query($sql);

            $sql = "UPDATE " . $tableName . " SET `thursday_open` = CONCAT(`thursday_open`, '0') WHERE `thursday_open` LIKE '%:_'";
            $connection->query($sql);
            $sql = "UPDATE " . $tableName . " SET `thursday_open_break` = CONCAT(`thursday_open_break`, '0') WHERE `thursday_open_break` LIKE '%:_'";
            $connection->query($sql);
            $sql = "UPDATE " . $tableName . " SET `thursday_close` = CONCAT(`thursday_close`, '0') WHERE `thursday_close` LIKE '%:_'";
            $connection->query($sql);
            $sql = "UPDATE " . $tableName . " SET `thursday_close_break` = CONCAT(`thursday_close_break`, '0') WHERE `thursday_close_break` LIKE '%:_'";
            $connection->query($sql);

            $sql = "UPDATE " . $tableName . " SET `friday_open` = CONCAT(`friday_open`, '0') WHERE `friday_open` LIKE '%:_'";
            $connection->query($sql);
            $sql = "UPDATE " . $tableName . " SET `friday_open_break` = CONCAT(`friday_open_break`, '0') WHERE `friday_open_break` LIKE '%:_'";
            $connection->query($sql);
            $sql = "UPDATE " . $tableName . " SET `friday_close` = CONCAT(`friday_close`, '0') WHERE `friday_close` LIKE '%:_'";
            $connection->query($sql);
            $sql = "UPDATE " . $tableName . " SET `friday_close_break` = CONCAT(`friday_close_break`, '0') WHERE `friday_close_break` LIKE '%:_'";
            $connection->query($sql);

            $sql = "UPDATE " . $tableName . " SET `saturday_open` = CONCAT(`saturday_open`, '0') WHERE `saturday_open` LIKE '%:_'";
            $connection->query($sql);
            $sql = "UPDATE " . $tableName . " SET `saturday_open_break` = CONCAT(`saturday_open_break`, '0') WHERE `saturday_open_break` LIKE '%:_'";
            $connection->query($sql);
            $sql = "UPDATE " . $tableName . " SET `saturday_close` = CONCAT(`saturday_close`, '0') WHERE `saturday_close` LIKE '%:_'";
            $connection->query($sql);
            $sql = "UPDATE " . $tableName . " SET `saturday_close_break` = CONCAT(`saturday_close_break`, '0') WHERE `saturday_close_break` LIKE '%:_'";
            $connection->query($sql);

            $sql = "UPDATE " . $tableName . " SET `sunday_open` = CONCAT(`sunday_open`, '0') WHERE `sunday_open` LIKE '%:_'";
            $connection->query($sql);
            $sql = "UPDATE " . $tableName . " SET `sunday_open_break` = CONCAT(`sunday_open_break`, '0') WHERE `sunday_open_break` LIKE '%:_'";
            $connection->query($sql);
            $sql = "UPDATE " . $tableName . " SET `sunday_close` = CONCAT(`sunday_close`, '0') WHERE `sunday_close` LIKE '%:_'";
            $connection->query($sql);
            $sql = "UPDATE " . $tableName . " SET `sunday_close_break` = CONCAT(`sunday_close_break`, '0') WHERE `sunday_close_break` LIKE '%:_'";
            $connection->query($sql);

            $connection->

            $done = 1;
            $jsonMessage = 'StoreLocator data was successfully clear.';
        } catch (\Exception $e) {
            $done = 0;
            $jsonMessage = 'StoreLocator data was not updated because of problem. Details: ' . $e->getMessage();
            $this->logger->addError('Error while saving StoreLocator data', $e->getMessage());
        }

        $result = [
            'done'      => $done,
            'message'   => $jsonMessage
        ];

        return $result;
    }

}
