<?php

namespace WeltPixel\Backend\Block\Adminhtml;

/**
 * Class ModulesVersion
 * @package WeltPixel\Backend\Block\Adminhtml
 */
class ModulesVersion extends  \Magento\Backend\Block\Template
{
    CONST MODULE_VERSIONS = 'https://www.weltpixel.com/weltpixel_extensions.json';

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
     * @var array
     */
    protected $latestVersions;

    /**
     * @param \Magento\Framework\App\DeploymentConfig $deploymentConfig
     * @param \Magento\Framework\Component\ComponentRegistrarInterface $componentRegistrar
     * @param \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\DeploymentConfig $deploymentConfig,
        \Magento\Framework\Component\ComponentRegistrarInterface $componentRegistrar,
        \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory,
        \Magento\Backend\Block\Template\Context $context,
        array $data = [])
    {
        $this->deploymentConfig = $deploymentConfig;
        $this->readFactory = $readFactory;
        $this->componentRegistrar = $componentRegistrar;
        $this->latestVersions = $this->getModulesLatestVersions();
        parent::__construct($context, $data);
    }

    /**
     * @return array
     */
    protected function getModulesLatestVersions() {
        $curl = curl_init(self::MODULE_VERSIONS);

        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);

        $latestVersions = json_decode($response, true);

        return $latestVersions;
    }


    /**
     * @return array
     */
    public function getModuleVersions() {
        $this->getModulesLatestVersions();
        $modules = $this->deploymentConfig->get('modules');

        $moduleDetails = [];

        foreach ($modules as $moduleName => $isEnabled) {
            if (strpos($moduleName, 'WeltPixel_') !== false ) {
                $moduleDetails[$moduleName]['enabled'] = $isEnabled;
                $moduleDetails[$moduleName]['version'] = $this->getComposerVersion($moduleName, \Magento\Framework\Component\ComponentRegistrar::MODULE);
                if (isset($this->latestVersions['modules'][$moduleName]['version']))  {
                    $moduleDetails[$moduleName]['latest_version'] = $this->latestVersions['modules'][$moduleName]['version'];
                    $moduleDetails[$moduleName]['theme_module'] = $this->latestVersions['modules'][$moduleName]['theme_module'];
                } else {
                    $moduleDetails[$moduleName]['latest_version'] = __('N/A');
                    $moduleDetails[$moduleName]['theme_module'] = false;
                }
                /** Adding latest version comparison messages */
                if ($moduleDetails[$moduleName]['version'] == $moduleDetails[$moduleName]['latest_version']) {
                    $moduleDetails[$moduleName]['status_message'] = __('Up to Date');
                    $moduleDetails[$moduleName]['status'] = true;
                } else {
                    $moduleDetails[$moduleName]['status_message'] = __('Update needed');
                    $moduleDetails[$moduleName]['status'] = false;
                }
            }
        }

        return $moduleDetails;
    }

    /**
     * @return array
     */
    public function getThemeVersions() {
        $themeDetails = [];

        $themes = [
            'Pearl' => 'frontend/Pearl/weltpixel',
            'Pearl custom' => 'frontend/Pearl/weltpixel_custom'
        ];
        foreach ($themes as $name => $theme) {
            $themeVersion =  $this->getComposerVersion($theme, \Magento\Framework\Component\ComponentRegistrar::THEME);
            if ($themeVersion != 'N/A') {
                $themeDetails[$name]['version'] = $themeVersion;
                if (isset($this->latestVersions['themes'][$name]))  {
                    $themeDetails[$name]['latest_version'] = $this->latestVersions['themes'][$name];
                } else {
                    $themeDetails[$name]['latest_version'] = __('N/A');
                }
                /** Adding latest version comparison messages */
                if ($themeDetails[$name]['version'] == $themeDetails[$name]['latest_version']) {
                    $themeDetails[$name]['status_message'] = __('Up to Date');
                    $themeDetails[$name]['status'] = true;
                } else {
                    $themeDetails[$name]['status_message'] = __('Update needed');
                    $themeDetails[$name]['status'] = false;
                }
            }
        }

        return $themeDetails;
    }

    /**
     * @param $moduleName
     * @return string
     */
    protected function getComposerVersion($moduleName, $type) {
        $path = $this->componentRegistrar->getPath(
            $type,
            $moduleName
        );

        if (!$path) {
            return __('N/A');
        }

        $dirReader = $this->readFactory->create($path);
        $composerJsonData = $dirReader->readFile('composer.json');
        $data = json_decode($composerJsonData, true);
        return $data['version'];

    }
}
