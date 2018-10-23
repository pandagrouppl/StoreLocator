<?php

namespace WeltPixel\Backend\Model;

class License extends \Magento\Framework\Model\AbstractModel
{
    const CACHE_TAG = 'weltixel_license';
    const LICENSE_CONSTANT = "\x57\x45\x4c\x54\x50\x49\x58\x45\x4c";
    const LICENSE_PASSWORD = "\x77\x65\x6c\x74\x70\x69\x78\x65\x6c\x5f\x63\x6f\x73\x6d\x6f";
    const LICENSE_IV = "\x77\x65\x6c\x74\x5f\x69\x76";
    const LICENSE_CIPHER = "\x61\x65\x73\x2d\x31\x32\x38\x2d\x63\x62\x63";
    const LICENCE_KEY_PATH = "\x65\x74\x63" . DIRECTORY_SEPARATOR . "\x6d\x6f\x64\x75\x6c\x65\x2e\x69\x6e\x66\x6f";
    const MODULE_INFO_PREFIX = "\x77\x70\x2f\x69\x6e\x66\x6f\x2f";
    const LICENSE_INFO_PREFIX = "\x77\x70\x2f\x66\x6c\x61\x67\x2f\x69\x6e\x66\x6f";
    const LICENSE_VERSION = "\x31\x2e\x37\x2e\x30";
    const LICENSE_ENDPOINT = "\x68\x74\x74\x70\x3a\x2f\x2f\x6c\x69\x63\x65\x6e\x73\x65\x2e\x77\x65\x6c\x74\x70\x69\x78\x65\x6c\x2e\x63\x6f\x6d";


    /**
     * @var string
     */
    protected $_cacheTag = 'weltixel_license';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'weltixel_license';

    /**
     * @var \Magento\Framework\App\DeploymentConfig
     */
    protected $deploymentConfig;

    /**
     * @var \Magento\Framework\Component\ComponentRegistrarInterface
     */
    protected $componentRegistrar;

    /**
     * @var \Magento\Framework\Filesystem\Directory\ReadFactory
     */
    protected $readFactory;

    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    protected $productMetadata;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlInterface;

    /**
     * @var array
     */
    protected $existingLicenses;

    /**
     * @var null|string
     */
    protected $pearlTheme = null;

    /**
     * @var array
     */
    protected $modulesList = [];

    /**
     * @var array
     */
    protected $modulesUserFriendlyNames = [];

    /**
     * @var array
     */
    protected $_currentModulesList = [];

    /**
     * @var \Magento\Framework\App\Config\Storage\WriterInterface
     */
    protected $configWriter;

    /**
     * @var array
     */
    protected $_wpModulesList = [];

    /**
     * @var int
     */
    protected $_attempt = 0;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $backendSession;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\DeploymentConfig $deploymentConfig
     * @param \Magento\Framework\Component\ComponentRegistrarInterface $componentRegistrar
     * @param \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetadata
     * @param \Magento\Framework\UrlInterface $urlInterface
     * @param \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
     * @param \Magento\Backend\Model\Session $backendSession
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\DeploymentConfig $deploymentConfig,
        \Magento\Framework\Component\ComponentRegistrarInterface $componentRegistrar,
        \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Magento\Backend\Model\Session $backendSession
    )
    {
        parent::__construct($context, $registry);
        $this->deploymentConfig = $deploymentConfig;
        $this->componentRegistrar = $componentRegistrar;
        $this->readFactory = $readFactory;
        $this->productMetadata = $productMetadata;
        $this->urlInterface = $urlInterface;
        $this->existingLicenses = null;
        $this->configWriter = $configWriter;
        $this->backendSession = $backendSession;
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('WeltPixel\Backend\Model\ResourceModel\License');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getIdentifier()];
    }

    /**
     * @return array
     */
    protected function _getExistingLicenses()
    {
        $existingLicensesCollection = $this->getCollection();

        foreach ($existingLicensesCollection as $licNs) {
            $this->existingLicenses[$licNs->getModuleName()] = $licNs->getLicenseKey();
        }
    }

    /**
     * @param $mdN
     * @return string
     */
    public function getLfM($mdN)
    {
        if (!$this->existingLicenses) {
            $this->_getExistingLicenses();
        }
        return (isset($this->existingLicenses[$mdN])) ? $this->existingLicenses[$mdN] : '-';
    }

    /**
     * This might be changed to check inside each module if license is needed
     * @return array
     */
    public function getMdsL()
    {
        if (empty($this->_currentModulesList)) {
            $modules = $this->deploymentConfig->get('modules');
            $licenseModules = [];
            $wpModules = [];
            $moduleTheme = 'WeltPixel_Pearl_Startup';
            $themePath = $this->componentRegistrar->getPath(\Magento\Framework\Component\ComponentRegistrar::THEME, 'frontend/Pearl/weltpixel');
            $isLRqd = $this->_isLRqd($themePath, $moduleTheme);

            if ($themePath) {
                $this->pearlTheme = $moduleTheme;
                $lcK = '-';
                if ($isLRqd) {
                    $lcK = $this->getLfM($moduleTheme);
                    $licenseModules[$moduleTheme] = [
                        "\x6d\x6f\x64\x75\x6c\x65\x5f\x6e\x61\x6d\x65" => $moduleTheme,
                        "\x76\x69\x73\x69\x62\x6c\x65\x5f\x6e\x61\x6d\x65" => (isset($this->modulesUserFriendlyNames[$moduleTheme]))
                            ? $this->modulesUserFriendlyNames[$moduleTheme] : str_replace("_", " ", $moduleTheme) . ' Theme',
                        "\x6c\x69\x63\x65\x6e\x73\x65" => $lcK,
                        "\x76\x65\x72\x73\x69\x6f\x6e" => $this->getComposerVersion('frontend/Pearl/weltpixel', \Magento\Framework\Component\ComponentRegistrar::THEME)
                    ];
                }
                $wpModules[$moduleTheme] = $this->getWpMdsInf($moduleTheme, $themePath, $lcK, $isLRqd, \Magento\Framework\Component\ComponentRegistrar::THEME);
            }

            foreach ($modules as $mdN => $isEnabled) {
                if ($isEnabled && (strpos($mdN, 'WeltPixel_') !== false)
                ) {
                    $path = $this->componentRegistrar->getPath(\Magento\Framework\Component\ComponentRegistrar::MODULE, $mdN);
                    $lcK = '-';
                    $isLRqd = $this->_isLRqd($path, $mdN);
                    if ($isLRqd) {
                        $lcK = $this->getLfM($mdN);
                        $licenseModules[$mdN] = [
                            "\x6d\x6f\x64\x75\x6c\x65\x5f\x6e\x61\x6d\x65" => $mdN,
                            "\x76\x69\x73\x69\x62\x6c\x65\x5f\x6e\x61\x6d\x65" => (isset($this->modulesUserFriendlyNames[$mdN]))
                                ? $this->modulesUserFriendlyNames[$mdN] : str_replace("_", " ", $mdN),
                            "\x6c\x69\x63\x65\x6e\x73\x65" => $lcK,
                            "\x76\x65\x72\x73\x69\x6f\x6e" => $this->getComposerVersion(str_replace("\x5f\x46\x72\x65\x65", '', $mdN), \Magento\Framework\Component\ComponentRegistrar::MODULE)
                        ];
                    }
                    $wpModules[$mdN] = $this->getWpMdsInf($mdN, $path, $lcK, $isLRqd, \Magento\Framework\Component\ComponentRegistrar::MODULE);
                }
            }

            $this->_currentModulesList = $licenseModules;
            $this->_wpModulesList = $wpModules;
        }

        return $this->_currentModulesList;
    }

    /**
     * @param string $licNs
     * @param string $mdN
     * @return bool
     */
    public function isLcVd($licNs, $mdN)
    {
        $magentoVersion = strtolower($this->productMetadata->getEdition());
        if ($magentoVersion != "\x63\x6f\x6d\x6d\x75\x6e\x69\x74\x79") {
            $magentoVersion = "\x65\x6e\x74\x65\x72\x70\x72\x69\x73\x65";
        }
        $constant = self::LICENSE_CONSTANT;
        $baseUrl = $this->urlInterface->getBaseUrl();
        $domain = $this->getDomainFromUrl($baseUrl);

        $iv = substr(hash('sha256', self::LICENSE_IV), 0, 16);
        try {
            $licenseDecoded = openssl_decrypt($licNs, self::LICENSE_CIPHER, self::LICENSE_PASSWORD, 0, $iv);
        } catch (\Exception $ex) {
            return false;
        }

        $moduleInfo = $this->getMdInfVl($mdN);
        if (!$moduleInfo) return false;

        $licenseOptions = explode("|||", $licenseDecoded);

        if (count($licenseOptions) != 5) return false;
        if ($constant != $licenseOptions[4]) return false;
        if ($mdN != $licenseOptions[1]) return false;
        if (($magentoVersion != $licenseOptions[3])  && ($magentoVersion != "\x63\x6f\x6d\x6d\x75\x6e\x69\x74\x79")) return false;

        $matches = [];
        preg_match('/(.local|.dev|.development|.test|.staging|.stage|magentosite.cloud)$/', $domain, $matches);
        if (isset($matches[1])) {
            if ($matches[1] == 'magentosite.cloud') {
                return true;
            }
            $findWhere = substr($licenseOptions[2], 0, strpos($licenseOptions[2], '.'));
            $findMe = substr($domain, 0, strpos($domain, '.'));

            if (strpos($findMe, $findWhere) !== false) {
                return true;
            }
        }
        if ($domain != $licenseOptions[2]) return false;
        return true;
    }


    /**
     * @param string $mdN
     * @param string $licNs
     * @return array|bool
     */
    public function getMdLcnDtls($mdN, $licNs)
    {
        $constant = self::LICENSE_CONSTANT;
        $iv = substr(hash('sha256', self::LICENSE_IV), 0, 16);
        try {
            $licenseDecoded = openssl_decrypt($licNs, self::LICENSE_CIPHER, self::LICENSE_PASSWORD, 0, $iv);
        } catch (\Exception $ex) {
            return false;
        }

        $licenseOptions = explode("|||", $licenseDecoded);

        if (strpos($mdN, 'WeltPixel_Pearl_') !== false) {
            $mdN = 'WeltPixel_Pearl';
        }

        if (count($licenseOptions) != 6) return false;
        if ($constant != $licenseOptions[5]) return false;
        if (strpos($licenseOptions[1], $mdN) === false) return false;

        $details = [
            "\x6d\x6f\x64\x75\x6c\x65" => $licenseOptions[1],
            "\x69\x73\x5f\x74\x68\x65\x6d\x65\x5f\x6d\x6f\x64\x75\x6c\x65" => $licenseOptions[2],
            "\x74\x68\x65\x6d\x65\x5f\x70\x61\x63\x6b\x61\x67\x65\x73" => explode(',', $licenseOptions[3]),
            "\x69\x73\x5f\x6c\x69\x63\x65\x6e\x73\x65\x5f\x6e\x65\x65\x64\x65\x64" => $licenseOptions[4],
        ];

        return $details;
    }

    /**
     * @param $url
     * @return mixed|string
     */
    public function getDomainFromUrl($url)
    {
        $url = strtolower($url);
        // regex can be replaced with parse_url
        preg_match("/^(https|http|ftp):\/\/(.*?)\//", "$url/", $matches);
        $parts = explode(".", $matches[2]);
        $tld = array_pop($parts);
        $tld = strtok($tld,':');
        $host = array_pop($parts);

        $genericTlds = array(
            'aero', 'asia', 'biz', 'cat', 'com', 'coop', 'info', 'int', 'jobs', 'mobi', 'museum', 'name', 'net',
            'org', 'pro', 'tel', 'travel', 'xxx', 'edu', 'gov', 'mil', 'co'
        );

        if (strlen($tld) == 2 && strlen($host) <= 3 && (in_array($host, $genericTlds))) {
            $tld = "$host.$tld";
            $host = array_pop($parts);
        }
        $domain = ($host) ? $host . "." . $tld : $tld;
        return $domain;
    }

    /**
     * @param string $path
     * @param string $mdN
     * @param boolean $forced
     * @return Boolean
     */
    protected function _isLRqd($path, &$mdN, $forced = false)
    {
        $availableModules = $this->getAvlbMds();
        if (!empty($availableModules) && !in_array($mdN, $availableModules)) {
            return false;
        }
        $magentoVersion = strtolower($this->productMetadata->getEdition());
        $directoryRead = $this->readFactory->create($path);
        try {
            $licNs = $directoryRead->readFile(self::LICENCE_KEY_PATH);
        } catch (\Exception $ex) {
            return true;
       }

        $moduleLicenseDetails = $this->getMdLcnDtls($mdN, $licNs);
        if (!$moduleLicenseDetails) return true;

        /** verificare pt modul la functionalitate de modul */
        if ($forced) {
            if ($moduleLicenseDetails['module'] != $mdN) {
                return true;
            }

            /** ha tema modulja, tema license kell mukodjon */
            if ($moduleLicenseDetails['is_theme_module']) {
                $mdN = $this->pearlTheme;
                return true;
            }

            /** tartalmazza a temat */
            if ($this->pearlTheme) {
                if (in_array($this->pearlTheme, $moduleLicenseDetails['theme_packages'])) {
                    $mdN = $this->pearlTheme;
                    return true;
                }
            } else {
                if (!($moduleLicenseDetails['is_license_needed']) && ($magentoVersion == "\x63\x6f\x6d\x6d\x75\x6e\x69\x74\x79")) {
                    return false;
                } else {
                    return true;
                }
            }
        } else {
            $mdN = $moduleLicenseDetails['module'];
            /** tema sajat modulja */
            if ($moduleLicenseDetails['is_theme_module']) {
                return false;
            }

            if ( !($moduleLicenseDetails['is_license_needed']) && ($magentoVersion == "\x63\x6f\x6d\x6d\x75\x6e\x69\x74\x79")) {
                return false;
            }

            /** tartalmazza a temat */
            if ($this->pearlTheme) {
                if (in_array($this->pearlTheme, $moduleLicenseDetails['theme_packages'])) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @return array
     */
    protected function getAvlbMds()
    {
        $weltpixelExtensions = $this->backendSession->getWeltPixelExtensions();
        $weltpixelExtensionsUserFriendlyNames = $this->backendSession->getWeltPixelExtensionsUserFriendlyNames();
        if (!empty($weltpixelExtensions)) {
            $this->modulesList = $weltpixelExtensions;
            $this->modulesUserFriendlyNames = $weltpixelExtensionsUserFriendlyNames;
            return $weltpixelExtensions;
        }

        if ($this->_attempt < 3 && empty($this->modulesList)) {
            $curl = curl_init(\WeltPixel\Backend\Block\Adminhtml\ModulesVersion::MODULE_VERSIONS);

            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            try {
                $response = curl_exec($curl);
                $modulesList = json_decode($response, true);
                $this->modulesList = array_keys($modulesList['modules']);

                foreach ($this->modulesList as $module) {
                    if (isset($modulesList['modules'][$module]['name'])) {
                        $this->modulesUserFriendlyNames[$module] = $modulesList['modules'][$module]['name'];
                    }
                }

                $this->backendSession->setWeltPixelExtensions($this->modulesList);
                $this->backendSession->setWeltPixelExtensionsUserFriendlyNames($this->modulesUserFriendlyNames);

            } catch (\Exception $ex) {
                $this->_attempt+=1;
                $this->modulesList = [];
                $this->modulesUserFriendlyNames = [];
            }

        }

        return $this->modulesList;
    }

    /**
     * @return array
     */
    public function getUserFriendlyModuleNames() {
        return $this->modulesUserFriendlyNames;
    }

    /**
     * @param $mdN
     * @return boolean
     */
    public function isLcNd($mdN)
    {
        $this->getMdsL();

        $path = $this->componentRegistrar->getPath(\Magento\Framework\Component\ComponentRegistrar::MODULE, str_replace("\x5f\x46\x72\x65\x65", '', $mdN));
        $isLRqd = $this->_isLRqd($path, $mdN, true);

        if ($isLRqd) {
            $licNs = $this->getLfM($mdN);
            return $this->isLcVd($licNs, $mdN);
        }

        return true;
    }

    /**
     * @param $mdN
     * @return bool|string
     */
    protected function getMdInfVl($mdN) {
        $connection = $this->getResource()->getConnection();
        $tableName = $this->getResource()->getTable('core_config_data');

        $row = $connection->fetchRow("SELECT `value` FROM " . $tableName . " WHERE path = '"
            . self::LICENSE_INFO_PREFIX . "' AND scope = '"
            . \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT
            . "' AND scope_id = 0");

        if (!isset($row['value']) || $row['value'] == 0) {
            return true;
        }

        $row = $connection->fetchRow("SELECT `value` FROM " . $tableName . " WHERE path = '"
            . self::MODULE_INFO_PREFIX . $mdN . "' AND scope = '"
            . \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT
            . "' AND scope_id = 0");


        if (!isset($row['value'])) {
            return true;
        }

        return $row['value'];
    }

    /**
     * @param $mdN
     * @return string
     */
    protected function getComposerVersion($mdN, $type) {
        $path = $this->componentRegistrar->getPath(
            $type,
            $mdN
        );

        if (!$path) {
            return __('N/A');
        }

        $dirReader = $this->readFactory->create($path);
        $composerJsonData = $dirReader->readFile('composer.json');
        $data = json_decode($composerJsonData, true);
        return $data['version'];
    }

    /**
     * @param string $mdN
     * @param string $path
     * @param string $licNs
     * @param boolean $isLNd
     * @return array
     */
    protected function getWpMdsInf($mdN, $path, $licNs, $isLNd, $moduleType) {
        $installationType = 'other';
        if (strpos($path, 'vendor') !== false) {
            $installationType = 'composer';
        }

        $moduleVersionName = $mdN;
        if ($moduleType == \Magento\Framework\Component\ComponentRegistrar::THEME) {
            $moduleVersionName = 'frontend/Pearl/weltpixel';
        }
        $vLid = false;
        if ($isLNd) {
            $vLid = $this->isLcVd($licNs, $mdN);
        }

        return [
            "\x6e\x61\x6d\x65" => $mdN,
            "\x76\x65\x72\x73\x69\x6f\x6e" => $this->getComposerVersion(str_replace("\x5f\x46\x72\x65\x65", '', $moduleVersionName), $moduleType),
            "\x6c\x69\x63\x65\x6e\x73\x65\x5f\x6b\x65\x79" => $licNs,
            "\x69\x6e\x73\x74\x61\x6c\x6c\x61\x74\x69\x6f\x6e\x5f\x74\x79\x70\x65" => $installationType,
            "\x69\x73\x5f\x6c\x69\x63\x65\x6e\x73\x65\x5f\x6e\x65\x65\x64\x65\x64" => ($isLNd) ? '1' : '0',
            "\x76\x61\x6c\x69\x64" => ($vLid) ? '1' : '0'
        ];
    }

    /**
     * @return array
     */
    public function getAllWpMds() {
        $this->getMdsL();
        return $this->_wpModulesList;
    }

    /**
     * @param string $mdN
     */
    public function updMdInf($mdN) {
        $this->getMdsL();
        $modules = [];
        $moduleInformation = $this->_wpModulesList[$mdN];
        $modules[$mdN] =  $moduleInformation;
        $this->updMdsInf(false, $modules);
    }

    /**
     * @param bool $all
     * @param array $modules
     */
    public function updMdsInf($all = true, $modules = []) {
        if ($all) {
            $modules = $this->getAllWpMds();
        }

        $baseUrl = $this->urlInterface->getBaseUrl();
        $domainInfo = parse_url($baseUrl);
        $domain = $domainInfo['host'];
        $magentoVersion = strtolower($this->productMetadata->getEdition());

        $data = array(
            "\x76\x65\x72\x73\x69\x6f\x6e" => $magentoVersion,
            "\x64\x6f\x6d\x61\x69\x6e" => $domain,
            "\x6d\x6f\x64\x75\x6c\x65\x73" => $modules
        );

        $data_string = json_encode($data);

        try {
            $ch = curl_init(self::LICENSE_ENDPOINT);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string))
            );

            $result = curl_exec($ch);
            $this->_prsLcInf($result);
        } catch (\Exception $ex) {
            $this->_uLcInRs(0);
        }
    }

    /**
     * @param $result
     */
    protected function _prsLcInf($result) {
        $info = json_decode($result, true);
        if (!is_array($info)) {
            $this->_uLcInRs(0);
            return ;
        }

        foreach ($info as $mName => $val) {
            $this->configWriter->save(self::MODULE_INFO_PREFIX . $mName, $val, \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
        }

        $this->_uLcInRs(1);
    }

    /**
     * @param $flag
     */
    protected function _uLcInRs($flag) {
        $this->configWriter->save(self::LICENSE_INFO_PREFIX, $flag, \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
    }

}
