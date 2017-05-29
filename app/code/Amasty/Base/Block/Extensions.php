<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */

namespace Amasty\Base\Block;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Json\DecoderInterface;

class Extensions extends \Magento\Config\Block\System\Config\Form\Fieldset
{
    /**
     * @var \Magento\Framework\Module\ModuleListInterface
     */
    protected $_moduleList;
    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $_layoutFactory;
    /**
     * @var \Magento\Framework\Module\Dir\Reader
     */
    protected $_moduleReader;
    /**
     * @var DecoderInterface
     */
    protected $_jsonDecoder;
    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    protected $_filesystem;
    /**
     * @var \Amasty\Base\Helper\Module
     */
    protected $_moduleHelper;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\View\Helper\Js $jsHelper,
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Framework\Module\Dir\Reader $moduleReader,
        \Magento\Framework\Filesystem\Driver\File $filesystem,
        \Amasty\Base\Helper\Module $moduleHelper,
        DecoderInterface $jsonDecoder,
        array $data = []
    ) {
        parent::__construct($context, $authSession, $jsHelper, $data);

        $this->_moduleList    = $moduleList;
        $this->_layoutFactory = $layoutFactory;
        $this->_moduleReader  = $moduleReader;
        $this->_jsonDecoder   = $jsonDecoder;
        $this->_filesystem    = $filesystem;
        $this->_moduleHelper  = $moduleHelper;
        $this->_scopeConfig   = $context->getScopeConfig();
    }

    /**
     * Render fieldset html
     *
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $html = $this->_getHeaderHtml($element);

        $modules = $this->_moduleList->getNames();

        $dispatchResult = new \Magento\Framework\DataObject($modules);
        $modules = $dispatchResult->toArray();

        sort($modules);
        foreach ($modules as $moduleName) {
            if (strstr($moduleName, 'Amasty_') === false
                || $moduleName === 'Amasty_Base'
            ) {
                continue;
            }

            $html .= $this->_getFieldHtml($element, $moduleName);
        }

        $html .= $this->_getFooterHtml($element);

        return $html;
    }

    /**
     * @return \Magento\Framework\View\Element\BlockInterface
     */
    protected function _getFieldRenderer()
    {
        if (empty($this->_fieldRenderer)) {
            $layout = $this->_layoutFactory->create();

            $this->_fieldRenderer = $layout->createBlock(
                'Magento\Config\Block\System\Config\Form\Field'
            );
        }

        return $this->_fieldRenderer;
    }

    /**
     * Read info about extension from composer json file
     * @param $moduleCode
     * @return mixed
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function _getModuleInfo($moduleCode)
    {
        $dir = $this->_moduleReader->getModuleDir('', $moduleCode);
        $file = $dir . '/composer.json';

        $string = $this->_filesystem->fileGetContents($file);
        $json = $this->_jsonDecoder->decode($string);

        return $json;
    }

    /**
     * @param $fieldset
     * @param $moduleCode
     * @return string
     */
    protected function _getFieldHtml($fieldset, $moduleCode)
    {
        $module = $this->_getModuleInfo($moduleCode);
        if(!is_array($module)  ||
           !array_key_exists('version', $module) ||
           !array_key_exists('description', $module)
        ) {
            return '';
        }

        $currentVer = $module['version'];
        $moduleName = $module['description'];
        $moduleName = $this->_replaceAmastyText($moduleName);
        $status =
             '<a target="_blank">
                <img src="'. $this->getViewFileUrl('Amasty_Base::images/ok.gif') . '" title="' . __("Installed") . '"/>
             </a>';

        $allExtensions = $this->_moduleHelper->getAllExtensions();
        if ($allExtensions && isset($allExtensions[$moduleCode])){
            $ext = [];

            if (is_array($allExtensions[$moduleCode]) && !array_key_exists('name', $allExtensions[$moduleCode])){
                $ext = end($allExtensions[$moduleCode]);
            } else {
                $ext = $allExtensions[$moduleCode];
            }

            $url     = $ext['url'];
            $name    = $ext['name'];
            $name = $this->_replaceAmastyText($name);
            $lastVer = $ext['version'];

            $moduleName =
                '<a href="' . $url . '" target="_blank" title="' . $name . '">'
                    . $name .
                '</a>';

            if (version_compare($currentVer, $lastVer, '<')) {
                $status =
                    '<a href="' . $url . '" target="_blank">
                        <img src="' . $this->getViewFileUrl('Amasty_Base::images/update.gif') .
                            '" alt="' . __("Update available") . '" title="'. __("Update available")
                    .'"/></a>';
            }
        }

        // in case if module output disabled
        if ($this->_scopeConfig->getValue('advanced/modules_disable_output/' . $moduleCode)) {
            $status =
                '<a href="' . $url . '" target="_blank">
                        <img src="' . $this->getViewFileUrl('Amasty_Base::images/bad.gif') .
                            '" alt="' . __("Output disabled") . '" title="'. __("Output disabled")
                .'"/></a>';
        }

        $moduleName = $status . ' ' . $moduleName;

        $field = $fieldset->addField($moduleCode, 'label', array(
            'name'  => 'dummy',
            'label' => $moduleName,
            'value' => $currentVer,
        ))->setRenderer($this->_getFieldRenderer());

        return $field->toHtml();
    }

    /**
     * @param $moduleName
     * @return mixed
     */
    protected function _replaceAmastyText($moduleName)
    {
        $moduleName = str_replace('for Magento 2', '', $moduleName);
        $moduleName = str_replace('by Amasty', '', $moduleName);

        return $moduleName;
    }
}
