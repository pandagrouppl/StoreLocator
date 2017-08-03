<?php
namespace Amasty\GiftCard\Block\Adminhtml\Code\Edit\Tab;

use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class General extends Generic implements TabInterface {

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
    }


    public function getTabLabel()
    {
        return __('General');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     * @codeCoverageIgnore
     */
    public function getTabTitle()
    {
        return __('General');
    }

    /**
     * Returns status flag about this tab can be showed or not
     *
     * @return bool
     * @codeCoverageIgnore
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return bool
     * @codeCoverageIgnore
     */
    public function isHidden()
    {
        return false;
    }

    protected function _prepareForm()
    {

        $model = $this->_coreRegistry->registry('current_amasty_giftcard_code');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('amasty_giftcard_');

        $fieldset = $form->addFieldset('general', ['legend' => __('General Information')]);

        if ($model->getId()) {
            $fieldset->addField('code_set_id', 'hidden', ['name' => 'code_set_id']);
        }

        $fieldset->addField(
            'title',
            'text',
            [
                'label' => __('Code Pool Name'),
                'title' => __('Code Pool Name'),
                'required'=>true,
                'name' => 'title',
            ]
        );

        $fieldset = $form->addFieldset('generate_codes', ['legend' => __('Generate Codes')]);

        $text = "<p class='note'>
		{L} - letter, {D} - digit<br>
		e.g. PROMO_{L}{L}{D}{D}{D} results in PROMO_DF627</p>
		";

        $fieldset->addField(
            'template',
            'text',
            [
                'label' => __('Generated codes template'),
                'title' => __('Generated codes template'),
                'name' => 'template',
                'after_element_html' => $text
            ]
        );

        $fieldset->addField(
            'qty',
            'text',
            [
                'label' => __('Generated codes qty'),
                'title' => __('Generated codes qty'),
                'name' => 'qty',
            ]
        );

        $fieldset3 = $form->addFieldset('import_codes', array(
            'htmlId'	=> 'import_codes',
            'legend'	=> __('Import Codes'),
        ));

        $fieldset3->addField('csv', 'file',
            array(
                'label' => __('CSV File'),
                'name' => 'csv',
                'note' => __('Each gift code on a new line'),
            )
        );

        $form->setValues($model->getData());

        $this->setForm($form);

        return parent::_prepareForm();
    }
}