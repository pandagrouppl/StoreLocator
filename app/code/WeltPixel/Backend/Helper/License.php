<?php

namespace WeltPixel\Backend\Helper;


/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class License extends \Magento\Framework\App\Helper\AbstractHelper
{

    /** @var  \WeltPixel\Backend\Model\License */
    protected $license;

    /**
     * @var \WeltPixel\Backend\Model\LicenseFactory
     */
    protected $licenseFactory;

    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $backendUrl;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \WeltPixel\Backend\Model\License $license
     * @param \WeltPixel\Backend\Model\LicenseFactory $licenseFactory,
     * @param \Magento\Backend\Model\UrlInterface $backendUrl
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \WeltPixel\Backend\Model\License $license,
        \WeltPixel\Backend\Model\LicenseFactory $licenseFactory,
        \Magento\Backend\Model\UrlInterface $backendUrl
    ) {
        parent::__construct($context);
        $this->license = $license;
        $this->licenseFactory = $licenseFactory;
        $this->backendUrl = $backendUrl;
    }

    /**
     * @return array
     */
    public function getMdsL() {
        return $this->license->getMdsL();
    }

    /**
     * @return array
     */
    public function getModulesListForDisplay() {
        $modulesList = $this->getMdsL();
        foreach ($modulesList as $key => $options) {
            if (version_compare($options['version'], \WeltPixel\Backend\Model\License::LICENSE_VERSION) < 0 ) {
                unset($modulesList[$key]);
            }
        }

        return $modulesList;
    }

    /**
     * @param $license
     * @param $module
     * @return bool
     */
    public function isLcVd($license, $module) {
        return $this->license->isLcVd($license, $module);
    }

    /**
     * @TODO might need to check and remove if some modules were meanwhile disabled
     * @return void
     */
    public function checkAndUpdate() {
        $modules = $this->getMdsL();
        foreach ($modules as $name => $options) {
            $license = $this->licenseFactory->create();
            try {
                $license->load($name, 'module_name');

                $license->setModuleName($name);
                $license->setLicenseKey($options['license']);
                $license->save();
            } catch (\Exception $ex) {}
        }
    }

    public function updMdsInf() {
        $this->license->updMdsInf();
    }

    /**
     * @return bool|string
     */
    public function getLicenseMessage() {
        if ($this->_request->isAjax()) {
            return false;
        }
        $messages = [];
        $modules = $this->getModulesListForDisplay();

        $userFriendlyNames = $this->license->getUserFriendlyModuleNames();
        foreach ($modules as $name => $options) {
            if (!$this->isLcVd($options['license'], $options['module_name'])) {
                $messages[] = isset($userFriendlyNames[$options['module_name']]) ? $userFriendlyNames[$options['module_name']] : $options['module_name'] ;
            }
        }

        if (count($messages)) {
            $moduleList = implode('<br/>', $messages);
            return __('License missing or invalid for the following WeltPixel module(s):') . '<br/>' . $moduleList . '<br/><br/>' .
            __('You can enter the license key(s)') . ' ' . '<a href="'. $this->backendUrl->getUrl('weltpixel_backend/licenses/index') .'">'
            . __('here.') . '</a><br/>' . __('License key can be found under My Downloadable Products section of your weltpixel.com account.') ;
        }

        return false;
    }
}
