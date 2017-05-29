<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\Shopby\Block\Widget\Form\Element;

class Dependence extends \Magento\Backend\Block\Widget\Form\Element\Dependence
{
    /**
     * @var \Amasty\Shopby\Model\Source\DisplayMode\Proxy
     */
    private $displayModeSource;

    protected $groupValues = [];

    protected $fieldsets = [];

    protected $groupFields = [];


    /**
     * Dependence constructor.
     * @param \Magento\Backend\Block\Context $context
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory $fieldFactory
     * @param \Amasty\Shopby\Model\Source\DisplayMode\Proxy $displayModeSource
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory $fieldFactory,
        \Amasty\Shopby\Model\Source\DisplayMode\Proxy $displayModeSource,
        array $data = []
    ) {
        parent::__construct($context, $jsonEncoder, $fieldFactory, $data);
        $this->displayModeSource = $displayModeSource;
    }

    protected function _toHtml()
    {
        if (!$this->_depends) {
            return '';
        }
        $this->addDisplayModeOptions();

        return '<script>
            require(["Amasty_Shopby/js/display-mode"], function() {
        var controller = new AmastyFormElementDependenceController(' .
            $this->_getDependsJson() . ', ' .
            ($this->groupValues ?  $this->_jsonEncoder->encode(
                $this->groupValues
                ) : 'null')  . ', ' .
            ($this->groupFields ?  $this->_jsonEncoder->encode(
                $this->groupFields
            ) : 'null')  . ', ' .
            ($this->fieldsets ?  $this->_jsonEncoder->encode(
                $this->fieldsets
            ) : 'null')  .
            ($this->_configOptions ? ', ' .
                $this->_jsonEncoder->encode(
                    $this->_configOptions
                ) : '') . ');
            });</script>';
    }


    private function addDisplayModeOptions()
    {
        $arrayOptions = [
            "levels_up" => 1,
            "notices" => $this->displayModeSource->getNotices(),
            "enabled_types" => $this->displayModeSource->getEnabledTypes(),
            "change_labels" => $this->displayModeSource->getChangeLabels()
        ];

        $this->addConfigOptions($arrayOptions);
    }

    public function addGroupValues($fieldName, $fieldNameFrom, $dependencies, $values)
    {
        $this->groupValues[$fieldName][$fieldNameFrom] = [
            'dependencies' => $dependencies,
            'values' => $values
        ];
    }

    public function addFieldsets($fieldSetName, $fieldNameFrom, $values)
    {
        $this->fieldsets[$fieldSetName][$fieldNameFrom] = $values;
    }

    public function addFieldToGroup($fieldName, $group)
    {
        $this->groupFields[$fieldName] = $group;
    }
}
