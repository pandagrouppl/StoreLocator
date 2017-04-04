<?php

namespace MagicToolbox\Magic360\Block\Adminhtml\Product\Edit\Tab;

use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * Magic 360 Images tab
 *
 */
class Magic360Images extends \Magento\Backend\Block\Widget\Form\Generic implements TabInterface
{
    /**
     * Model factory
     * @var \MagicToolbox\Magic360\Model\GalleryFactory
     */
    protected $modelGalleryFactory = null;

    /**
     * Model factory
     * @var \MagicToolbox\Magic360\Model\ColumnsFactory
     */
    protected $modelColumnsFactory = null;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \MagicToolbox\Magic360\Model\GalleryFactory $modelGalleryFactory
     * @param \MagicToolbox\Magic360\Model\ColumnsFactory $modelColumnsFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \MagicToolbox\Magic360\Model\GalleryFactory $modelGalleryFactory,
        \MagicToolbox\Magic360\Model\ColumnsFactory $modelColumnsFactory,
        array $data = []
    ) {
        $this->modelGalleryFactory = $modelGalleryFactory;
        $this->modelColumnsFactory = $modelColumnsFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Return Tab label
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->getLabel();
    }

    /**
     * Return Tab title
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTitle();
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        if (!$this->_request->getParam('id') || !$this->_authorization->isAllowed('MagicToolbox_Magic360::magic360_settings_edit')) {
            $this->setCanShow(false);
        }
        return $this->hasCanShow() ? (bool)$this->getCanShow() : true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return $this->hasIsHidden() ? (bool)$this->getIsHidden() : false;
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     */
    protected function _prepareForm()
    {

        $multiRowsValue = false;
        $columnsValue = 0;
        $rowsValue = 1;

        $productId = $this->_coreRegistry->registry('product')->getId();

        $galleryModel = $this->modelGalleryFactory->create();
        $galleryCollection = $galleryModel->getCollection();
        $galleryCollection->addFieldToFilter('product_id', $productId);
        $galleryCollection->setOrder('position', \Magento\Framework\Data\Collection::SORT_ORDER_ASC);
        $imagesCount = $galleryCollection->count();

        $columnsModel = $this->modelColumnsFactory->create();
        $columnsModel->load($productId);
        $columnsValue = (int)$columnsModel->getData('columns');//null
        $columnsValue = $columnsValue ? $columnsValue : 0;

        if ($columnsValue && $imagesCount != $columnsValue) {
            $multiRowsValue = true;
            $rowsValue = floor($imagesCount/$columnsValue);
        }

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $fieldset = $form->addFieldset('magic360_spin_options_fieldset', ['legend' => __('Multi-row spin options')]);

        $fieldset->addField(
            'magic360_multi_rows',
            'checkbox',
            [
                'name' => 'magic360[multi_rows]',
                'label' => __('Multi-row spin'),
                'title' => __('Multi-row spin'),
                'value'     => $multiRowsValue,
                'checked'   => $multiRowsValue,
                'onclick'   => '(function() {'.
                                    'var disabled = jQuery(\'#magic360_multi_rows\').attr(\'checked\') ? false : true;'.
                                    'jQuery(\'#magic360_columns\').attr(\'disabled\', disabled);'.
                                    'jQuery(\'#magic360_rows\').attr(\'disabled\', disabled);'.
                                '})();',
            ]
        );

        $fieldset->addField(
            'magic360_columns',
            'text',
            [
                'name' => 'magic360[columns]',
                'label' => __('Number of images on X-axis'),
                'title' => __('Number of images on X-axis'),
                'value'     => $columnsValue,
                'disabled'  => !$multiRowsValue,
            ]
        );

        $fieldset->addField(
            'magic360_rows',
            'text',
            [
                'name' => 'magic360[rows]',
                'label' => __('Number of images on Y-axis'),
                'title' => __('Number of images on Y-axis'),
                'value'     => $rowsValue,
                'disabled'  => !$multiRowsValue,
            ]
        );

        $fieldset = $form->addFieldset('magic360_images_fieldset', ['legend' => __('Magic 360 Images')]);

        $fieldset->addType('magic360_gallery', 'MagicToolbox\Magic360\Block\Adminhtml\Product\Helper\Form\Gallery');

        $gallery = $fieldset->addField(
            'magic360_gallery',
            'magic360_gallery',
            [
                'name' => 'magic360[gallery]',
                'label' => __('Magic360 Gallery'),
                'title' => __('Magic360 Gallery'),
                'value'     => $galleryCollection->getData(),
            ]
        );

        $this->setForm($form);
        return parent::_prepareForm();
    }
}
