<?php

namespace MagicToolbox\Magic360\Block\Adminhtml\Import\Edit;

use Magento\Backend\Block\Widget\Form\Generic;

/**
 * Adminhtml import edit form
 *
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post', 'class' => 'magictoolbox-config']]
        );
        $form->setUseContainer(true);
        $this->setForm($form);

        $fieldset = $form->addFieldset('import_settings_fieldset', ['legend' => __('Batch import multiple spins at the same time.')]);

        $field = $fieldset->addField('import-folder', 'text', [
            'label'     => 'Images folder',
            'title'     => 'Images folder',
            'name'      => 'magictoolbox[import][folder]',
            'note'      => 'The name of the folder containing your 360 images. It must be located in the Magento 2 base directory.',
            'value'     => 'magic360images',
            'class'     => 'magictoolbox-option',
            'before_element_html' => preg_replace('#^https?://#', '', $this->getBaseUrl().' '),
        ]);

        $fieldset->addType('advanced-radios', '\MagicToolbox\Magic360\Block\Adminhtml\Import\Edit\Form\Element\AdvancedRadios');

        $field = $fieldset->addField('import-method', 'advanced-radios', [
            'label'     => 'Import method',
            'title'     => 'Import method',
            'name'      => 'magictoolbox[import][method]',
            'value'     => 'id',
            'class'     => 'magictoolbox-option',
            'values'    => [
                [
                    'value' => 'sku',
                    'label' => 'by SKU',
                    'note'  => 'The folder and file structure must follow this pattern:<br/><br/>
                                {images folder}/{product SKU}/any-filename1.jpg<br/>
                                {images folder}/{product SKU}/any-filename2.jpg<br/>
                                {images folder}/{product SKU}/any-filename3.jpg<br/>
                                etc.<br/><br/>
                                The folder names must exactly match your product SKU. The file names can be anything - images will be added to the spin in alphanumeric order.',
                ],
                [
                    'value' => 'id',
                    'label' => 'by ID',
                    'note'  => 'The folder and file structure must follow this pattern:<br/><br/>
                                {images folder}/{product ID}/any-filename1.jpg<br/>
                                {images folder}/{product ID}/any-filename2.jpg<br/>
                                {images folder}/{product ID}/any-filename3.jpg<br/>
                                etc.<br/><br/>
                                The folder names must exactly match your product ID. The file names can be anything - images will be added to the spin in alphanumeric order.',
                ],
            ],
        ]);

        $field = $fieldset->addField('import-clear', 'advanced-radios', [
            'label'     => 'Delete after import',
            'title'     => 'Delete after import',
            'name'      => 'magictoolbox[import][clear]',
            'note'      => 'Delete the images after they have been imported.',
            'value'     => 'no',
            'class'     => 'magictoolbox-option',
            'values'    => [['value' => 'yes', 'label' => 'Yes'], ['value' => 'no', 'label' => 'No']],
        ]);

        return parent::_prepareForm();
    }
}
