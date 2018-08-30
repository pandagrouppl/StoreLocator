<?php

namespace PandaGroup\LooknbuyExtender\Block\Adminhtml\Look\Edit\Tab;

/**
 * Adminhtml look edit form.
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry             $registry
     * @param \Magento\Framework\Data\FormFactory     $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config       $wysiwygConfig
     * @param \Magento\Store\Model\System\Store       $systemStore
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form.
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('look_form');
        $this->setTitle(__('Look Information'));
    }

    /**
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('look_look');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('look_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information')]
        );

        if ($model->getLookId()) {
            $fieldset->addField('look_id', 'hidden', ['name' => 'look_id']);
        }

        $fieldset->addField(
            'look_name',
            'text',
            ['name' => 'look_name', 'label' => __('Look Title'), 'title' => __('Look Title'), 'required' => true]
        );

        $fieldset->addField(
            'url_key',
            'text',
            [
                'name' => 'url_key',
                'label' => __('URL Key'),
                'title' => __('URL Key'),
                'class' => 'validate-identifier',
                'required' => false,
                'note' => __('Relative to Web Site Base URL'),

            ]
        );

        if ($model->getId()) {
            $image = $model->getBaseImage();
            $isImage = 0;
            if (isset($image) && $image != null) {
                $isImage = 1;
            }
        } else {
            $isImage = 0;
        }

        $baseImage = $fieldset->addField(
            'base_image',
            'image',
            [
                'name' => 'base_image',
                'label' => __('Base Image'),
                'title' => __('Base Image'),
                'required' => true,
                'class' => 'required-entry required-file',

            ]
        );

        if ($isImage == 0) {
            $baseImage->setAfterElementHtml('<script type="text/javascript">$("look_base_image").addClassName("required-entry");</script>');
        }

        // ---------------------- Carousel Image ---------------------- //
        if ($model->getId()) {
            $image = $model->getCarouselImage();

            $isImage = 0;
            if (isset($image) && $image != null) {
                $isImage = 1;
            }
        } else {
            $isImage = 0;
        }

        $carouselImage = $fieldset->addField(
            'carousel_image',
            'image',
            [
                'name' => 'carousel_image',
                'label' => __('Carousel Image'),
                'title' => __('Carousel Image'),
                'required' => true,
                'class' => 'required-entry required-file',
            ]
        );

        if ($isImage == 0) {
            $carouselImage->setAfterElementHtml('<script type="text/javascript">$("look_carousel_image").addClassName("required-entry");</script>');
        }
        // ---------------------- Carousel Image ---------------------- //

        $fieldset->addField(
            'discount_type',
            'select',
            [
                'label' => __('Discount Type'),
                'title' => __('Discount Type'),
                'name' => 'discount_type',
                'required' => true,
                'options' => ['1' => __('Fixed'), '0' => __('Percentage')],
            ]
        );

        $fieldset->addField(
            'discount_price',
            'text',
            [
                'name' => 'discount_price',
                'label' => __('Discount'),
                'title' => __('Discount'),
                'required' => true,

            ]
        );

        $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'status',
                'required' => true,
                'options' => ['1' => __('Enabled'), '0' => __('Disabled')],
            ]
        );
        if (!$model->getId()) {
            $model->setData('status', '1');
        }

        $fieldset->addField(
            'layout',
            'select',
            [
                'label' => __('Layout'),
                'title' => __('Layout'),
                'name' => 'layout',
                'required' => true,
//                'options' => ['1' => __('1 Column'), '2' => __('2 Columns')],
                'options' => ['2' => __('2 Columns')],
            ]
        );

//        $wysiwygConfig = $this->_wysiwygConfig->getConfig(['tab_id' => $this->getTabId()]);

//        $contentField = $fieldset->addField(
//            'description',
//            'editor',
//            [
//                'name' => 'description',
//                'style' => 'height:25em;',
//                'required' => false,
//                'config' => $wysiwygConfig,
//            ]
//        );

        // Setting custom renderer for content field to remove label column
//        $renderer = $this->getLayout()->createBlock(
//            'Magento\Backend\Block\Widget\Form\Renderer\Fieldset\Element'
//        )->setTemplate(
//            'Magento_Cms::page/edit/form/renderer/content.phtml'
//        );
//        $contentField->setRenderer($renderer);

        $this->_eventManager->dispatch('adminhtml_cms_page_edit_tab_content_prepare_form', ['form' => $form]);

        $form->setValues($model->getData());

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab.
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Look Information');
    }

    /**
     * Prepare title for tab.
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Look Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}
