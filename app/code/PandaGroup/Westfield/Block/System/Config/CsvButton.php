<?php

namespace PandaGroup\Westfield\Block\System\Config;

class CsvButton extends \Magento\Config\Block\System\Config\Form\Field
{
    protected $_template = 'PandaGroup_Westfield::system/config/csvbtn.phtml';


    /**
     * CsvButton constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->_toHtml();
    }

    /**
     * Generate collect button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        $url = $this->getUrl('*/westfield_product_attribute/exportColors', []);

        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'type'      => 'button',
                'id'        => 'csv_export_button',
                'label'     => __('Export CSV'),
                'class'     => 'scalable',
                'onclick'   => 'setLocation(\''. $url . '\')'
            ]
        );

        return $button->toHtml();
    }
}
