<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\ShopbyPage\Block\Adminhtml\Page\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store as SystemStore;
use Amasty\ShopbyPage\Model\Config\Source\Category as SourceCategory;
use Amasty\ShopbyPage\Controller\RegistryConstants;
use Magento\Framework\Api\ExtensibleDataObjectConverter;

class Category extends Generic implements TabInterface
{
    /**
     * @var SystemStore
     */
    protected $_systemStore;

    /**
     * @var SourceCategory
     */
    protected $_sourceCategory;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param SystemStore $systemStore
     * @param SourceCategory $sourceCategory
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        SystemStore $systemStore,
        SourceCategory $sourceCategory,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_sourceCategory = $sourceCategory;
        $this->_extensibleDataObjectConverter = $extensibleDataObjectConverter;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Categories & Store Views');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
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

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('amasty_shopbypage_');

        /** @var \Amasty\ShopbyPage\Model\Page $model */
        $model = $this->_coreRegistry->registry(RegistryConstants::PAGE);

        $fieldset = $form->addFieldset(
            'category_fieldset',
            ['legend' => __('Categories'), 'class' => 'fieldset-wide']
        );

        /**
         * Check is single store mode
         */
        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'stores',
                'multiselect',
                [
                    'name' => 'stores[]',
                    'label' => __('Store Views'),
                    'title' => __('Store Views'),
                    'required' => true,
                    'values' => $this->_systemStore->getStoreValuesForForm(false, true),
                ]
            );

            /** @var \Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element $renderer */
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $field->setRenderer($renderer);
        } else {
            $storeId = $this->_storeManager->getStore(true)->getId();
            $fieldset->addField(
                'store_id',
                'hidden',
                ['name' => 'stores[]', 'value' => $storeId]
            );
            $model->setStores([$storeId]);
        }

        $fieldset->addField('categories', 'multiselect', array(
            'label' => __('Categories'),
            'title' => __('Categories'),
            'name' => 'categories',
            'style' => 'height: 500px; width: 300px;',
            'values' => $this->_sourceCategory->toOptionArray(),
        ));

        $form->setValues(
            $this->_extensibleDataObjectConverter->toNestedArray(
                $model,
                [],
                '\Amasty\ShopbyPage\Api\Data\PageInterface'
            )
        );

        $this->setForm($form);

        return parent::_prepareForm();
    }

}
