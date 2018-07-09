<?php

namespace PandaGroup\Salesforce\Block\System\Config;

use Magento\Framework\Data\Form\Element\AbstractElement;

class CreateDataExtensions extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var string
     */
    protected $_template = 'PandaGroup_Salesforce::system/config/manageDataExtensionsButton.phtml';

    /**
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
     * Remove scope label
     *
     * @param  AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * Return element html
     *
     * @param  AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }

    /**
     * Generate button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'id' => 'create_data_extensions_button',
                'label' => __('Create'),
                'onclick' => 'setLocation(\'' . $this->getUrl(
                        'pandagroup_salesforce/system_config/createdataextensions/',
                        ['store' => $this->getRequest()->getParam('store', 0)]
                    ) . '\')',
            ]
        );

        $button2 = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'id' => 'refresh_data_extensions_button',
                'label' => __('Refresh'),
                'onclick' => 'setLocation(\'' . $this->getUrl(
                        'pandagroup_salesforce/system_config/refreshdataextensions/',
                        ['store' => $this->getRequest()->getParam('store', 0)]
                    ) . '\')',
            ]
        );

        $button3 = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'id' => 'delete_data_extensions_button',
                'label' => __('Delete'),
                'onclick' => 'setLocation(\'' . $this->getUrl(
                        'pandagroup_salesforce/system_config/deletedataextensions/',
                        ['store' => $this->getRequest()->getParam('store', 0)]
                    ) . '\')',
            ]
        );

        return $button->toHtml() . $button2->toHtml() . $button3->toHtml();
    }
}
?>