<?php

namespace PandaGroup\Westfield\Model\Api;

class AbstractApi extends \Magento\Framework\DataObject
{
    const WESTFIELD_ENABLED = 'light4website_westfield/general/enabled';
    const WESTFIELD_USERNAME = 'light4website_westfield/general/username';
    const WESTFIELD_PASSWORD = 'light4website_westfield/general/password';
    const WESTFIELD_GATEWAY_URL_PRODUCTION_FULL = 'light4website_westfield/general/gateway_url_production_full';
    const WESTFIELD_GATEWAY_URL_PRODUCTION_PARTIAL = 'light4website_westfield/general/gateway_url_production_partial';

    const WESTFIELD_USERNAME_TEST = 'light4website_westfield/general/username_test';
    const WESTFIELD_PASSWORD_TEST = 'light4website_westfield/general/password_test';
    const WESTFIELD_GATEWAY_URL_TEST_FULL = 'light4website_westfield/general/gateway_url_test_full';
    const WESTFIELD_GATEWAY_URL_TEST_PARTIAL = 'light4website_westfield/general/gateway_url_test_partial';

    const WESTFIELD_COLOR_ATTRIBUTE_CODE = 'light4website_westfield/general/color_attribute_code';

    const WESTFIELD_TEST_MODE = 'light4website_westfield/general/test_mode';

    const WESTFIELD_FILE_FULL_TYPE = 'full';
    const WESTFIELD_FILE_PARTIAL_TYPE = 'partial';
    const WESTFIELD_DIRECTORY_FULL = 'westfield/full';
    const WESTFIELD_DIRECTORY_PARTIAL = 'westfield/partial';

    protected $enabled = null;
    protected $username = null;
    protected $password = null;
    protected $gatewayUrlFull = null;
    protected $gatewayUrlPartial = null;
    protected $colorAttributeCode = null;
    protected $testMode = null;
    protected $filename = 'westfield.xml';

    protected $objectManager;
    protected $configModel;
    protected $directoryModel;
    protected $log = null;
    protected $status = null;
    protected $encryptorInterface;
    protected $_stockItemRepository;

    public function __construct(
        \Magento\Framework\Filesystem\DirectoryList $dir,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \PandaGroup\Westfield\Logger\Logger $logger,
        \PandaGroup\Westfield\Model\Status $status,
        \Magento\Framework\Encryption\EncryptorInterface $encryptorInterface,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
    ) {
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->configModel = $scopeConfig;
        $this->directoryModel = $dir;
        $this->log = $logger;
        $this->status = $status;
        $this->encryptorInterface = $encryptorInterface;
        $this->_stockItemRepository = $stockItemRepository;
        $this->setApiCredentials();
        $this->createDirectoryForXmlFiles();
        parent::__construct();
    }

    private function setApiCredentials($storeId = null) {
        if (true === $this->isTestMode()) {
            $this->setUsername((string) $this->getConfig(self::WESTFIELD_USERNAME_TEST, $storeId));
            $this->setPassword((string) $this->encryptorInterface->decrypt($this->getConfig(self::WESTFIELD_PASSWORD_TEST, $storeId)));
            $this->setGatewayUrlFull((string) $this->getConfig(self::WESTFIELD_GATEWAY_URL_TEST_FULL, $storeId));
            $this->setGatewayUrlPartial((string) $this->getConfig(self::WESTFIELD_GATEWAY_URL_TEST_PARTIAL, $storeId));
        } else {
            $this->setUsername($this->getConfig(self::WESTFIELD_USERNAME, $storeId));
            $this->setPassword($this->encryptorInterface->decrypt($this->getConfig(self::WESTFIELD_PASSWORD, $storeId)));
            $this->setGatewayUrlFull((string) $this->getConfig(self::WESTFIELD_GATEWAY_URL_PRODUCTION_FULL, $storeId));
            $this->setGatewayUrlPartial((string) $this->getConfig(self::WESTFIELD_GATEWAY_URL_PRODUCTION_PARTIAL, $storeId));
        }

        $this->setColorAttributeCode((string) $this->getConfig(self::WESTFIELD_COLOR_ATTRIBUTE_CODE, $storeId));
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setGatewayUrlFull($gatewayUrlFull) {
        $this->gatewayUrlFull = $gatewayUrlFull;
    }

    public function getGatewayUrlFull() {
        return $this->gatewayUrlFull;
    }

    public function setGatewayUrlPartial($gatewayUrlPartial) {
        $this->gatewayUrlPartial = $gatewayUrlPartial;
    }

    public function getGatewayUrlPartial() {
        return $this->gatewayUrlPartial;
    }

    public function setColorAttributeCode($code) {
        $this->colorAttributeCode = $code;
    }

    public function getColorAttributeCode() {
        return $this->colorAttributeCode;
    }

    protected function isEnabled($storeId = null) {
        if (true === is_null($this->enabled)) {
            $this->enabled = (bool)$this->getConfig(self::WESTFIELD_ENABLED, $storeId);
        }

        return $this->enabled;
    }

    protected function isTestMode($storeId = null) {
        if (true === is_null($this->testMode)) {
            $this->testMode = (bool) $this->getConfig(self::WESTFIELD_TEST_MODE, $storeId);
        }

        return (bool) $this->testMode;
    }

    protected function getWestfieldDirectoryFull() {
        return $this->directoryModel->getPath('var') . DIRECTORY_SEPARATOR . self::WESTFIELD_DIRECTORY_FULL;
    }

    protected function getWestfieldDirectoryPartial() {
        return $this->directoryModel->getPath('var') . DIRECTORY_SEPARATOR . self::WESTFIELD_DIRECTORY_PARTIAL;
    }

    protected function getWestfieldFile($type) {
        $file = '';

        switch ($type) {
            case self::WESTFIELD_FILE_FULL_TYPE:
                $file = $this->getWestfieldDirectoryFull() . DIRECTORY_SEPARATOR . $this->filename;
                break;

            case self::WESTFIELD_FILE_PARTIAL_TYPE:
                $file = $this->getWestfieldDirectoryPartial() . DIRECTORY_SEPARATOR . $this->filename;
                break;
        }

        return $file;
    }

    protected function createDirectoryForXmlFiles() {
        if (false === is_dir($this->getWestfieldDirectoryFull())) {
            mkdir($this->getWestfieldDirectoryFull(), 0777, true);
        }

        if (false === is_dir($this->getWestfieldDirectoryPartial())) {
            mkdir($this->getWestfieldDirectoryPartial(), 0777, true);
        }
    }

    private function getConfig($config, $storeId)
    {
        return $this->configModel->getValue(
            $config,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getLog() {
        return $this->log;
    }

    protected function getStatus() {
        return $this->status;
    }

}
