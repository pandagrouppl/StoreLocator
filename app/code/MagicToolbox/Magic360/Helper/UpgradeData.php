<?php

namespace MagicToolbox\Magic360\Helper;

/**
 * Upgrade data helper
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UpgradeData extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\Module\Dir\Reader
     */
    protected $_modulesReader;

    /**
     * Model factory
     * @var \MagicToolbox\Magic360\Model\ConfigFactory
     */
    protected $_modelConfigFactory = null;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \MagicToolbox\Magic360\Model\ConfigFactory $modelConfigFactory
     * @param \Magento\Framework\Module\Dir\Reader $modulesReader
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \MagicToolbox\Magic360\Model\ConfigFactory $modelConfigFactory,
        \Magento\Framework\Module\Dir\Reader $modulesReader
    ) {
        $this->_modulesReader = $modulesReader;
        $this->_modelConfigFactory = $modelConfigFactory;
        parent::__construct($context);
    }

    /**
     * Public method to upgrade data
     *
     */
    public function upgrade()
    {
        $moduleEtcPath = $this->_modulesReader->getModuleDir(\Magento\Framework\Module\Dir::MODULE_ETC_DIR, 'MagicToolbox_Magic360');
        $fileName = $moduleEtcPath.'/defaults.xml';
        libxml_use_internal_errors(true);
        $xml = simplexml_load_file($fileName);
        libxml_use_internal_errors(false);

        if (!$xml) {
            return;
        }

        $model = $this->_modelConfigFactory->create();
        $collection = $model->getCollection();
        $collection->addFieldToFilter('platform', 0);
        $dbData = [];
        foreach ($collection->getData() as $param) {
            if (!isset($dbData[$param['platform']])) {
                $dbData[$param['platform']] = [];
            }
            if (!isset($dbData[$param['platform']][$param['profile']])) {
                $dbData[$param['platform']][$param['profile']] = [];
            }
            $dbData[$param['platform']][$param['profile']][$param['name']] = '';
        }

        $params = $xml->xpath('/defaults/param');
        foreach ($params as $param) {
            if (isset($dbData[(string)$param['platform']][(string)$param['profile']][(string)$param['name']])) {
                continue;
            }

            $collection->getResource()->insertConfigData([
                'platform' => (int)$param['platform'],
                'profile' => (string)$param['profile'],
                'name' => (string)$param['name'],
                'value' => (string)$param['value'],
                'status' => (int)$param['status']
            ]);
        }
    }
}
