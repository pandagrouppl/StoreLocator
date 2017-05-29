<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Block\Adminhtml\Image\Edit\Tab;

use Amasty\GiftCard\Model\Image;
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

        $model = $this->_coreRegistry->registry('current_amasty_giftcard_image');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('amasty_giftcard_');

        $fieldset = $form->addFieldset('general', ['legend' => __('General Information')]);

        if ($model->getId()) {
            $fieldset->addField('image_id', 'hidden', ['name' => 'image_id']);
        }

        $fieldset->addField('code_pos_x', 'hidden',
            array(
                'name'	=> 'code_pos_x'
            )
        );

        $fieldset->addField('code_pos_y', 'hidden',
            array(
                'name'	=> 'code_pos_y'
            )
        );

        $fieldset->addField(
            'title',
            'text',
            [
                'label' => __('Image Title'),
                'title' => __('Image Title'),
                'required' => true,
                'name' => 'title',
            ]
        );

        $fieldset->addField(
            'active',
            'select',
            [
                'label' => __('Status'),
                'required' => true,
                'options'	=> [
                    Image::STATUS_INACTIVE	=> __('Inactive'),
                    Image::STATUS_ACTIVE	=> __('Active'),
                ],
                'name' => 'active',
            ]
        );

        $fieldset->addField(
            'image',
            'file',
            [
                'label' => __('Upload Image'),
                'name' => 'image',
            ]
        );

        $fieldset->addType('block', '\Amasty\GiftCard\Block\Adminhtml\Form\Element\Block');

        $fieldset->addField(
            'image_position',
            'block',
            [
                'label' => __('Please, specify code position'),
                'block' => $this->getLayout()->createBlock('\Amasty\GiftCard\Block\Adminhtml\Image\Edit\Image'),
                'image'	=> $model,
            ]
        );
        $form->setValues($model->getData());

        $this->setForm($form);

        return parent::_prepareForm();
    }
}