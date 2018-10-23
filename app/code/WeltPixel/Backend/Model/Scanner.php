<?php

namespace WeltPixel\Backend\Model;

class Scanner extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var \Magento\Framework\App\Config\FileResolver
     */
    protected $fileResolver;

    /**
     * @var \Magento\Framework\App\AreaList
     */
    protected $areaList;

    /**
     * Direct usage of \Magento\Setup\Module\Di\Code\Scanner\ConfigurationScanner
     * caused error on compilation
     * Argument 1 passed to Magento\\Setup\\Module\\Di\\Code\\Scanner\\ConfigurationScanner::__construct()
     * must be an instance of Magento\\Framework\\App\\Config\\FileResolver,
     * instance of Magento\\Framework\\ObjectManager\\ObjectManager given
     *
     * so FileResolver and AreaList injected directly insetad
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\FileResolver $fileResolver
     * @param \Magento\Framework\App\AreaList $areaList
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\FileResolver $fileResolver,
        \Magento\Framework\App\AreaList $areaList
    )
    {
        parent::__construct($context, $registry);
        $this->fileResolver = $fileResolver;
        $this->areaList = $areaList;
    }

    /**
     * \Magento\Setup\Module\Di\Code\Scanner\ConfigurationScanner scan usage,
     * because the original class injection is causing error on compilation
     *
     * @param $fileName
     * @return array
     */
    public function getScannedFiles($fileName) {
        $files = [];
        $areaCodes = array_merge(
            ['primary', \Magento\Framework\App\Area::AREA_GLOBAL],
            $this->areaList->getCodes()
        );
        foreach ($areaCodes as $area) {
            $files = array_merge_recursive(
                $files,
                $this->fileResolver->get($fileName, $area)->toArray()
            );
        }
        return array_keys($files);
    }

    /**
     * @param bool $all
     * @return array
     */
    public function getRewrites($all = false)
    {
        $diFiles = $this->getScannedFiles('di.xml');
        $moduleFiles = $this->getScannedFiles('module.xml');

        array_walk($moduleFiles, function (&$item) {
            $item = str_replace('/module.xml', '', $item);
        });

        if (!$all) {
            foreach ($diFiles as $key => $file) {
                preg_match('/(.*\/etc)/', $file, $matches);
                $resultKey = $matches[1];
                if (!in_array($resultKey, $moduleFiles) ||
                    (strpos($file, '/vendor/magento') !== false)
                ) {
                    unset($diFiles[$key]);
                }
            }
        }

        $rewrites = $this->_collectRewriteEntities($diFiles);
        return $rewrites;

    }

    /**
     * @param array $files
     * @return array
     */
    protected function _collectRewriteEntities(array $files)
    {
        $rewrites = [];
        foreach ($files as $fileName) {
            $areaCode = 'global';
            preg_match('/\/etc\/(.*)\/di.xml/', $fileName, $matches);
            if (isset($matches[1])) {
                $areaCode = $matches[1];
            }
            $dom = new \DOMDocument();
            $dom->loadXML(file_get_contents($fileName));
            $xpath = new \DOMXPath($dom);
            /** @var $node \DOMNode */
            foreach ($xpath->query('//preference') as $node) {
                $originalClass = $node->getAttribute('for');
                $rewriteClass = $node->getAttribute('type');

                $rewrites[$originalClass][$areaCode][] = $rewriteClass;
            }
        }

        return $rewrites;
    }
}
