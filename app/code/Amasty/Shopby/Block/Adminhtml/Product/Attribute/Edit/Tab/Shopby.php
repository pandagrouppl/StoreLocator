<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\Shopby\Block\Adminhtml\Product\Attribute\Edit\Tab;

use Amasty\Shopby\Block\Widget\Form\Element\Dependence;
use Amasty\Shopby\Model\FilterSetting;
use Amasty\Shopby\Model\FilterSettingFactory;
use Amasty\Shopby\Model\Source\VisibleInCategory;
use Amasty\Shopby\Model\Source\Category as CategorySource;
use Amasty\Shopby\Model\Source\Attribute as AttributeSource;
use Amasty\Shopby\Model\Source\Attribute\Option as AttributeOptionSource;
use Amasty\Shopby\Model\Source\DisplayMode;
use Amasty\Shopby\Model\Source\MeasureUnit;
use Amasty\Shopby\Model\Source\MultipleValuesLogic;
use Amasty\Shopby\Model\Source\ShowProductQuantities;
use Amasty\Shopby\Model\Source\SortOptionsBy;
use Amasty\ShopbySeo\Model\Source\RelNofollow;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Model\Config\Source\Yesno;
use Magento\Catalog\Model\Entity\Attribute;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Framework\Data\Form\Element\Fieldset;

class Shopby extends \Magento\Backend\Block\Widget\Form\Generic
{
    const MAX_ATTRIBUTE_OPTIONS_COUNT = 500;

    const FIELD_FRONTEND_INPUT = 'frontend_input';

    /**
     * @var Yesno
     */
    protected $yesNo;

    /** @var  DisplayMode */
    protected $displayMode;

    /** @var  MeasureUnit */
    protected $measureUnitSource;

    /** @var  MultipleValuesLogic */
    protected $multipleValuesLogic;

    /** @var  FilterSetting */
    protected $setting;

    /** @var Attribute $attributeObject */
    protected $attributeObject;

    /**
     * @var SortOptionsBy
     */
    protected $sortOptionsBy;

    /**
     * @var ShowProductQuantities
     */
    protected $showProductQuantities;

    /**
     * @var \Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory
     */
    protected $dependencyFieldFactory;

    /** @var VisibleInCategory\Proxy */
    protected $visibleInCategory;

    /** @var CategorySource */
    protected $categorySource;

    /** @var AttributeSource */
    protected $attributeSource;

    /** @var AttributeOptionSource */
    protected $attributeOptionSource;

    /** @var \Amasty\Shopby\Model\Source\FilterPlacedBlock */
    protected $filterPlacedBlockSource;

    /** @var \Amasty\Shopby\Model\Source\SubcategoriesView */
    protected $subcategoriesViewSource;

    /** @var \Amasty\Shopby\Model\Source\SubcategoriesExpand */
    protected $subcategoriesExpandSource;

    /** @var \Amasty\Shopby\Model\Source\RenderCategoriesLevel */
    protected $renderCategoriesLevelSource;

    /**
     * @var \Amasty\Shopby\Helper\FilterSetting
     */
    protected $filterSettingHelper;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Yesno $yesNo
     * @param DisplayMode $displayMode
     * @param VisibleInCategory\Proxy $visibleInCategory
     * @param CategorySource $categorySource
     * @param MeasureUnit $measureUnitSource
     * @param AttributeSource $attributeSource
     * @param AttributeOptionSource $attributeOptionSource
     * @param FilterSettingFactory $settingFactory
     * @param SortOptionsBy $sortOptionsBy
     * @param ShowProductQuantities $showProductQuantities
     * @param \Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory $dependencyFieldFactory
     * @param MultipleValuesLogic $multipleValuesLogic
     * @param \Amasty\Shopby\Model\Source\FilterPlacedBlock $filterPlacedBlockSource
     * @param \Amasty\Shopby\Model\Source\SubcategoriesView $subcategoriesViewSource
     * @param \Amasty\Shopby\Model\Source\SubcategoriesExpand $subcategoriesExpandSource
     * @param \Amasty\Shopby\Model\Source\RenderCategoriesLevel $renderCategoriesLevelSource
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Yesno $yesNo,
        DisplayMode $displayMode,
        VisibleInCategory\Proxy $visibleInCategory,
        CategorySource $categorySource,
        MeasureUnit $measureUnitSource,
        AttributeSource $attributeSource,
        AttributeOptionSource $attributeOptionSource,
        FilterSettingFactory $settingFactory,
        SortOptionsBy $sortOptionsBy,
        ShowProductQuantities $showProductQuantities,
        \Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory $dependencyFieldFactory,
        MultipleValuesLogic $multipleValuesLogic,
        \Amasty\Shopby\Model\Source\FilterPlacedBlock $filterPlacedBlockSource,
        \Amasty\Shopby\Model\Source\SubcategoriesView $subcategoriesViewSource,
        \Amasty\Shopby\Model\Source\SubcategoriesExpand $subcategoriesExpandSource,
        \Amasty\Shopby\Model\Source\RenderCategoriesLevel $renderCategoriesLevelSource,
        \Amasty\Shopby\Helper\FilterSetting $filterSettingHelper,
        array $data = []
    ) {
        $this->yesNo = $yesNo;
        $this->displayMode = $displayMode;
        $this->measureUnitSource = $measureUnitSource;
        $this->setting = $settingFactory->create();
        $this->attributeObject = $registry->registry('entity_attribute');
        $this->sortOptionsBy = $sortOptionsBy;
        $this->showProductQuantities = $showProductQuantities;
        $this->dependencyFieldFactory = $dependencyFieldFactory;
        $this->multipleValuesLogic = $multipleValuesLogic;
        $this->visibleInCategory = $visibleInCategory;
        $this->categorySource = $categorySource->setEmptyOption(false);
        $this->attributeSource = $attributeSource->skipAttributeId($this->attributeObject->getId());
        $this->attributeOptionSource = $attributeOptionSource->skipAttributeId($this->attributeObject->getId());
        $this->filterPlacedBlockSource = $filterPlacedBlockSource;
        $this->subcategoriesViewSource = $subcategoriesViewSource;
        $this->subcategoriesExpandSource = $subcategoriesExpandSource;
        $this->renderCategoriesLevelSource = $renderCategoriesLevelSource;
        $this->filterSettingHelper = $filterSettingHelper;
        $this->displayMode->setAttribute($this->attributeObject);
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $this->prepareFilterSetting();
        $form->setDataObject($this->setting);

        $form->addField(
            'filter_code',
            'hidden',
            [
                'name' => 'filter_code',
                'value' => $this->setting->getFilterCode(),
            ]
        );

        $yesnoSource = $this->yesNo->toOptionArray();
        /** @var  $dependence Dependence */
        $dependence = $this->getLayout()->createBlock(
            'Amasty\Shopby\Block\Widget\Form\Element\Dependence'
        );

        $fieldsetDisplayProperties = $form->addFieldset(
            'shopby_fieldset_display_properties',
            ['legend' => __('Display Properties'), 'collapsable' => $this->getRequest()->has('popup')]
        );

        $displayModeField = $fieldsetDisplayProperties->addField(
            'display_mode',
            'select',
            [
                'name' => 'display_mode',
                'label' => __('Display Mode'),
                'title' => __('Display Mode'),
                'values' => $this->displayMode->toOptionArray(),
                'note' => '&nbsp;'
            ]
        );

        $dependence->addGroupValues(
            $displayModeField->getName(),
            self::FIELD_FRONTEND_INPUT,
            $this->displayMode->getInputTypeMap(),
            $this->displayMode->getAllOptionsDependencies()
        );

        if ($this->attributeObject->getAttributeCode() == \Amasty\Shopby\Helper\Category::ATTRIBUTE_CODE) {
            $this->addCategorySettingFields($fieldsetDisplayProperties, $dependence, $displayModeField);
        }


        $addFromToWidget = $fieldsetDisplayProperties->addField(
            'add_from_to_widget',
            'select',
            [
                'name' => 'add_from_to_widget',
                'label' => __('Add From-To Widget'),
                'title' => __('Add From-To Widget'),
                'values' => $this->yesNo->toOptionArray()
            ]
        );
        $valuesMode = [
            DisplayMode::MODE_DEFAULT,
            DisplayMode::MODE_DROPDOWN,
            DisplayMode::MODE_SLIDER
        ];
        /**
         * dependency means that all Display Modes support widget except "From-To Only" mode
         */
        $dependence->addFieldMap(
            $addFromToWidget->getHtmlId(),
            $addFromToWidget->getName()
        )->addFieldDependence(
            $addFromToWidget->getName(),
            $displayModeField->getName(),
            $this->dependencyFieldFactory->create(
                [
                    'fieldData' => [
                        'separator' => ',',
                        'value' => implode(',', $valuesMode),
                        'negative' => false,
                        'group' => 'price'
                    ],
                    'fieldPrefix' => ''
                ]
            )
        );

        $dependence->addFieldToGroup($addFromToWidget->getName(), DisplayMode::ATTRUBUTE_PRICE);

        $dependence->addFieldMap(
            $displayModeField->getHtmlId(),
            $displayModeField->getName()
        );

        $sliderMinField = $fieldsetDisplayProperties->addField(
            'slider_min',
            'text',
            [
                'name' => 'slider_min',
                'label' => __('Minimum Slider Value'),
                'title' => __('Minimum Slider Value'),
                'class' => 'validate-zero-or-greater validate-number',
                'note' => __('Please specify the min value to limit the slider, e.g. <$10')
            ]
        );

        $dependence->addFieldMap(
            $sliderMinField->getHtmlId(),
            $sliderMinField->getName()
        )->addFieldDependence(
            $sliderMinField->getName(),
            $displayModeField->getName(),
            DisplayMode::MODE_SLIDER
        );

        $sliderMaxField = $fieldsetDisplayProperties->addField(
            'slider_max',
            'text',
            [
                'name' => 'slider_max',
                'label' => __('Maximum Slider Value'),
                'title' => __('Maximum Slider Value'),
                'class' => 'validate-greater-than-zero validate-number',
                'note' => __('Please specify the max value to limit the slider, e.g. >$999')
            ]
        );

        $dependence->addFieldMap(
            $sliderMaxField->getHtmlId(),
            $sliderMaxField->getName()
        )->addFieldDependence(
            $sliderMaxField->getName(),
            $displayModeField->getName(),
            DisplayMode::MODE_SLIDER
        );

        $sliderStepField = $fieldsetDisplayProperties->addField(
            'slider_step',
            'text',
            [
                'name' => 'slider_step',
                'label' => __('Slider Step'),
                'title' => __('Slider Step'),
                'class' => 'validate-zero-or-greater'
            ]
        );

        $dependence->addFieldMap(
            $sliderStepField->getHtmlId(),
            $sliderStepField->getName()
        )->addFieldDependence(
            $sliderStepField->getName(),
            $displayModeField->getName(),
            DisplayMode::MODE_SLIDER
        );

        ////for decimal
        $valuesMode = [
            DisplayMode::MODE_DEFAULT,
            DisplayMode::MODE_DROPDOWN,
            DisplayMode::MODE_SLIDER,
            DisplayMode::MODE_FROM_TO_ONLY
        ];

        $useCurrencySymbolField = $fieldsetDisplayProperties->addField(
            'units_label_use_currency_symbol',
            'select',
            [
                'name' => 'units_label_use_currency_symbol',
                'label' => __('Measure Units'),
                'title' => __('Measure Units'),
                'values' => $this->measureUnitSource->toOptionArray(),
            ]
        );
        $dependence->addFieldMap(
            $useCurrencySymbolField->getHtmlId(),
            $useCurrencySymbolField->getName()
        )->addFieldDependence(
            $useCurrencySymbolField->getName(),
            $displayModeField->getName(),
            $this->dependencyFieldFactory->create(
                [
                    'fieldData' => [
                        'separator' => ';',
                        'value' => implode(";", $valuesMode),
                        'negative' => false
                    ],
                    'fieldPrefix' => ''
                ]
            )
        );
        $dependence->addFieldToGroup($useCurrencySymbolField->getName(), DisplayMode::ATTRUBUTE_PRICE);

        $unitsLabelField = $fieldsetDisplayProperties->addField(
            'units_label',
            'text',
            [
                'name' => 'units_label',
                'label' => __('Unit Label'),
                'title' => __('Unit Label'),
            ]
        );

        $dependence->addFieldMap(
            $unitsLabelField->getHtmlId(),
            $unitsLabelField->getName()
        );

        $dependence->addFieldDependence(
            $unitsLabelField->getName(),
            $displayModeField->getName(),
            $this->dependencyFieldFactory->create(
                [
                    'fieldData' => [
                        'separator' => ',',
                        'value' => implode(",", $valuesMode),
                        'negative' => false
                    ],
                    'fieldPrefix' => ''
                ]
            )
        );
        $dependence->addFieldDependence(
            $unitsLabelField->getName(),
            $useCurrencySymbolField->getName(),
            MeasureUnit::CUSTOM
        );
        $dependence->addFieldToGroup($unitsLabelField->getName(), DisplayMode::ATTRUBUTE_PRICE);

        $fieldsetDisplayProperties->addField(
            'block_position',
            'select',
            [
                'name' => 'block_position',
                'label' => __('Show in the Block'),
                'title' => __('Show in the Block'),
                'values' => $this->filterPlacedBlockSource->toOptionArray(),
            ]
        );

        $fieldDisplayModeSliderDependencyNegative = $this->dependencyFieldFactory->create(
            ['fieldData' => ['value' => (string)DisplayMode::MODE_SLIDER, 'negative' => true], 'fieldPrefix' => '']
        );

        $sortOptionsByField = $fieldsetDisplayProperties->addField(
            'sort_options_by',
            'select',
            [
                'name' => 'sort_options_by',
                'label' => __('Sort Options By'),
                'title' => __('Sort Options By'),
                'values' => $this->sortOptionsBy->toOptionArray(),
            ]
        );

        $dependence->addFieldMap(
            $sortOptionsByField->getHtmlId(),
            $sortOptionsByField->getName()
        );

        $dependence->addFieldDependence(
            $sortOptionsByField->getName(),
            $displayModeField->getName(),
            $fieldDisplayModeSliderDependencyNegative
        );

        $showProductQuantitiesField = $fieldsetDisplayProperties->addField(
            'show_product_quantities',
            'select',
            [
                'name' => 'show_product_quantities',
                'label' => __('Show Product Quantities'),
                'title' => __('Show Product Quantities'),
                'values' => $this->showProductQuantities->toOptionArray(),
            ]
        );

        $dependence->addFieldMap(
            $showProductQuantitiesField->getHtmlId(),
            $showProductQuantitiesField->getName()
        );

        $dependence->addFieldDependence(
            $showProductQuantitiesField->getName(),
            $displayModeField->getName(),
            $this->dependencyFieldFactory->create(
                [
                    'fieldData' => [
                        'separator' => ';',
                        'value' => implode(";", $this->displayMode->getShowProductQuantitiesConfig()),
                        'negative' => false
                    ],
                    'fieldPrefix' => ''
                ]
            )
        );


        $showSearchBoxField = $fieldsetDisplayProperties->addField(
            'is_show_search_box',
            'select',
            [
                'name' => 'is_show_search_box',
                'label' => __('Show Search Box'),
                'title' => __('Show Search Box'),
                'values' => $this->yesNo->toOptionArray(),
            ]
        );

        $dependence->addFieldMap(
            $showSearchBoxField->getHtmlId(),
            $showSearchBoxField->getName()
        );

        $dependence->addFieldDependence(
            $showSearchBoxField->getName(),
            $displayModeField->getName(),
            DisplayMode::MODE_DEFAULT
        );

        $numberUnfoldedOptionsField = $fieldsetDisplayProperties->addField(
            'number_unfolded_options',
            'text',
            [
                'name' => 'number_unfolded_options',
                'label' => __('Number of unfolded options'),
                'title' => __('Number of unfolded options'),
                'note' => __('Other options will be shown after a customer clicks the "More" button.')
            ]
        );
        $dependence->addFieldMap(
            $numberUnfoldedOptionsField->getHtmlId(),
            $numberUnfoldedOptionsField->getName()
        );

        $dependence->addFieldDependence(
            $numberUnfoldedOptionsField->getName(),
            $displayModeField->getName(),
            $this->dependencyFieldFactory->create(
                [
                    'fieldData' => [
                        'separator' => ';',
                        'value' => implode(";", $this->displayMode->getNumberUnfoldedOptionsConfig()),
                        'negative' => false
                    ],
                    'fieldPrefix' => ''
                ]
            )
        );

        $fieldsetDisplayProperties->addField(
            'is_expanded',
            'select',
            [
                'name' => 'is_expanded',
                'label' => __('Expand'),
                'title' => __('Expand'),
                'values' => $this->yesNo->toOptionArray(),
            ]
        );

        $fieldsetDisplayProperties->addField(
            'tooltip',
            'textarea',
            [
                'name' => 'tooltip',
                'label' => __('Tooltip'),
                'title' => __('Tooltip'),
            ]
        );

        $this->addCategoriesVisibleFilter($fieldsetDisplayProperties, $dependence);
        $this->addDependentFiltersFilter($fieldsetDisplayProperties);

        $fieldsetFiltering = $form->addFieldset(
            'shopby_fieldset_filtering',
            ['legend' => __('Filtering'), 'collapsable' => $this->getRequest()->has('popup')]
        );

        $dependence->addFieldsets(
            $fieldsetFiltering->getHtmlId(),
            self::FIELD_FRONTEND_INPUT,
            ['value' => 'price', 'negative' => false]
        );

        $multiselectNote = $this->attributeObject->getAttributeCode() == \Amasty\Shopby\Helper\Category::ATTRIBUTE_CODE
            ? __('When multiselect option is disabled it follows the category page')
            : null;

        $multiselectField = $fieldsetFiltering->addField(
            'is_multiselect',
            'select',
            [
                'name' => 'is_multiselect',
                'label' => __('Allow Multiselect'),
                'title' => __('Allow Multiselect'),
                'values' => $yesnoSource,
                'note' => $multiselectNote,
            ]
        );
        $dependence->addFieldMap(
            $multiselectField->getHtmlId(),
            $multiselectField->getName()
        );
        $dependence->addFieldDependence(
            $multiselectField->getName(),
            $displayModeField->getName(),
            $this->dependencyFieldFactory->create(
                [
                    'fieldData' => [
                        'separator' => ';',
                        'value' => implode(";", $this->displayMode->getIsMultiselectConfig()),
                        'negative' => false
                    ],
                    'fieldPrefix' => ''
                ]
            )
        );

        if ($this->attributeObject->getAttributeCode() != \Amasty\Shopby\Helper\Category::ATTRIBUTE_CODE) {
            $useAndLogicField = $fieldsetFiltering->addField(
                'is_use_and_logic',
                'select',
                [
                    'name' => 'is_use_and_logic',
                    'label' => __('Multiple Values Logic'),
                    'title' => __('Multiple Values Logic'),
                    'values' => $this->multipleValuesLogic->toOptionArray(),
                ]
            );

            $dependence->addFieldMap(
                $useAndLogicField->getHtmlId(),
                $useAndLogicField->getName()
            )->addFieldDependence(
                $useAndLogicField->getName(),
                $multiselectField->getName(),
                $this->dependencyFieldFactory->create(
                    [
                        'fieldData' => [
                            'separator' => ';',
                            'value' => implode(";", $this->displayMode->getIsMultiselectConfig()),
                            'negative' => false
                        ],
                        'fieldPrefix' => ''
                    ]
                )
            );
        }

        $fieldsetDisplayProperties->addField(
            'hide_one_option',
            'select',
            [
                'name' => 'hide_one_option',
                'label' => __('Hide filter when only one option available'),
                'title' => __('Hide filter when only one option available'),
                'values' => $yesnoSource,
            ]
        );

        $this->setChild(
            'form_after',
            $dependence
        );


        $this->_eventManager->dispatch('amshopby_attribute_form_tab_build_after',
            ['form' => $form, 'setting' => $this->setting, 'dependence' => $dependence]);

        $this->setForm($form);
        $data = $this->setting->getData();

        if (isset($data['slider_step'])) {
            $data['slider_step'] = round($data['slider_step'], 4);
        }

        $form->setValues($data);
        return parent::_prepareForm();
    }

    protected function addCategoriesVisibleFilter(
        Fieldset $fieldsetDisplayProperties,
        Dependence $dependence
    ) {
        $visibleInCategories = $fieldsetDisplayProperties->addField(
            'visible_in_categories',
            'select',
            [
                'name' => 'visible_in_categories',
                'label' => __('Visible in Categories'),
                'title' => __('Visible in Categories'),
                'values' => $this->visibleInCategory->toOptionArray(),
            ]
        );

        $categoryFilter = $fieldsetDisplayProperties->addField(
            'categories_filter',
            'multiselect',
            [
                'name' => 'categories_filter',
                'label' => __('Categories'),
                'title' => __('Categories'),
                'style' => 'height: 500px; width: 300px;',
                'values' => $this->categorySource->toOptionArray(),
            ]
        );

        $dependence->addFieldMap(
            $visibleInCategories->getHtmlId(),
            $visibleInCategories->getName()
        )->addFieldMap(
            $categoryFilter->getHtmlId(),
            $categoryFilter->getName()
        )->addFieldDependence(
            $categoryFilter->getName(),
            $visibleInCategories->getName(),
            $this->dependencyFieldFactory->create(
                [
                    'fieldData' => ['value' => (string)VisibleInCategory::VISIBLE_EVERYWHERE, 'negative' => true],
                    'fieldPrefix' => ''
                ]
            )
        );

        return $fieldsetDisplayProperties;
    }

    protected function addDependentFiltersFilter(Fieldset $fieldsetDisplayProperties)
    {
        $attributesFilter = $fieldsetDisplayProperties->addField(
            'attributes_filter',
            'multiselect',
            [
                'name' => 'attributes_filter',
                'label' => __('Show only when any option of attributes below is selected'),
                'title' => __('Show only when any option of attributes below is selected'),
                'values' => $this->attributeSource->toOptionArray(),
            ]
        );

        $attributesFilter->setRenderer(
            $this->getLayout()
                ->createBlock('Amasty\Shopby\Block\Adminhtml\Product\Attribute\Edit\Tab\Shopby\Multiselect')
        );

        $attributeOptions = $this->attributeOptionSource->toOptionArray();
        if (count($attributeOptions) < self::MAX_ATTRIBUTE_OPTIONS_COUNT) {

            $attributesOptionsFilter = $fieldsetDisplayProperties->addField(
                'attributes_options_filter',
                'multiselect',
                [
                    'name' => 'attributes_options_filter',
                    'label' => __('Show only if the following option is selected'),
                    'title' => __('Show only if the following option is selected'),
                    'values' => $attributeOptions
                ]
            );

            $attributesOptionsFilter->setRenderer(
                $this->getLayout()
                    ->createBlock('Amasty\Shopby\Block\Adminhtml\Product\Attribute\Edit\Tab\Shopby\Multiselect')
            );
        } else {
            $attributesOptionsFilter = $fieldsetDisplayProperties->addField(
                'attributes_options_filter',
                'text',
                [
                    'name' => 'attributes_options_filter',
                    'label' => __('Show only if the following option is selected'),
                    'title' => __('Show only if the following option is selected'),
                    'note' => __('Comma separated options ids')
                ]
            );

            $this->setting->setAttributesOptionsFilter(implode(',', $this->setting->getAttributesOptionsFilter()));
        }

        return $fieldsetDisplayProperties;
    }

    protected function addCategorySettingFields(
        Fieldset $fieldsetDisplayProperties,
        Dependence $dependence,
        $displayModeField
    ) {
        $categoryTreeDepthField = $fieldsetDisplayProperties->addField(
            'category_tree_depth',
            'text',
            [
                'name' => 'category_tree_depth',
                'label' => __('Category Tree Depth'),
                'title' => __('Category Tree Depth'),
                'class' => 'validate-greater-than-zero',
                'note' => __('Specify the max level number for category tree. Keep 1 to hide the subcategories'),
            ]
        );

        $categoryTreeDepthFieldValues = ',0,1';

        $dependence->addFieldMap(
            $categoryTreeDepthField->getHtmlId(),
            $categoryTreeDepthField->getName()
        );

        $subcategoriesViewField = $fieldsetDisplayProperties->addField(
            'subcategories_view',
            'select',
            [
                'name' => 'subcategories_view',
                'label' => __('Subcategories View'),
                'title' => __('Subcategories View'),
                'values' => $this->subcategoriesViewSource->toOptionArray()
            ]
        );

        $dependence->addFieldMap(
            $subcategoriesViewField->getHtmlId(),
            $subcategoriesViewField->getName()
        )->addFieldDependence(
            $subcategoriesViewField->getName(),
            $categoryTreeDepthField->getName(),
            $this->dependencyFieldFactory->create(
                [
                    'fieldData' => ['value' => $categoryTreeDepthFieldValues, 'separator' => ',', 'negative' => true],
                    'fieldPrefix' => ''
                ]
            )
        )->addFieldDependence(
            $subcategoriesViewField->getName(),
            $displayModeField->getName(),
            (string)DisplayMode::MODE_DEFAULT
        );

        $subcategoriesExpandField = $fieldsetDisplayProperties->addField(
            'subcategories_expand',
            'select',
            [
                'name' => 'subcategories_expand',
                'label' => __('Expand Subcategories'),
                'title' => __('Expand Subcategories'),
                'values' => $this->subcategoriesExpandSource->toOptionArray()
            ]
        );

        $dependence->addFieldMap(
            $subcategoriesExpandField->getHtmlId(),
            $subcategoriesExpandField->getName()
        );

        $dependence->addFieldDependence(
            $subcategoriesExpandField->getName(),
            $subcategoriesViewField->getName(),
            (string)\Amasty\Shopby\Model\Source\SubcategoriesView::FOLDING
        )->addFieldDependence(
            $subcategoriesExpandField->getName(),
            $displayModeField->getName(),
            (string)DisplayMode::MODE_DEFAULT
        );

        $dependence->addFieldDependence(
            $subcategoriesExpandField->getName(),
            $categoryTreeDepthField->getName(),
            $this->dependencyFieldFactory->create(
                [
                    'fieldData' => ['value' => $categoryTreeDepthFieldValues, 'separator' => ',', 'negative' => true],
                    'fieldPrefix' => ''
                ]
            )
        );


        $renderAllCategoriesTreeField = $fieldsetDisplayProperties->addField(
            'render_all_categories_tree',
            'select',
            [
                'name' => 'render_all_categories_tree',
                'label' => __('Render All Categories Tree'),
                'title' => __('Render All Categories Tree'),
                'values' => $this->yesNo->toOptionArray(),
                'note' => __('Yes (Render Full Categories Tree) or No (Only For Current Category Path)')
            ]
        );

        $renderCategoriesLevelField = $fieldsetDisplayProperties->addField(
            'render_categories_level',
            'select',
            [
                'name' => 'render_categories_level',
                'label' => __('Render Categories Level'),
                'title' => __('Render Categories Level'),
                'values' => $this->renderCategoriesLevelSource->toOptionArray(),
            ]
        );

        $dependence->addFieldMap(
            $renderAllCategoriesTreeField->getHtmlId(),
            $renderAllCategoriesTreeField->getName()
        )->addFieldMap(
            $renderCategoriesLevelField->getHtmlId(),
            $renderCategoriesLevelField->getName()
        )->addFieldDependence(
            $renderCategoriesLevelField->getName(),
            $categoryTreeDepthField->getName(),
            $this->dependencyFieldFactory->create(
                [
                    'fieldData' => ['value' => $categoryTreeDepthFieldValues, 'separator' => ',', 'negative' => true],
                    'fieldPrefix' => ''
                ]
            )
        )->addFieldDependence(
            $renderAllCategoriesTreeField->getName(),
            $categoryTreeDepthField->getName(),
            $this->dependencyFieldFactory->create(
                [
                    'fieldData' => ['value' => $categoryTreeDepthFieldValues, 'separator' => ',', 'negative' => true],
                    'fieldPrefix' => ''
                ]
            )
        )->addFieldDependence(
            $renderAllCategoriesTreeField->getName(),
            $renderCategoriesLevelField->getName(),
            $this->dependencyFieldFactory->create(
                [
                    'fieldData' => [
                        'value' => (string)\Amasty\Shopby\Model\Source\RenderCategoriesLevel::CURRENT_CATEGORY_CHILDREN,
                        'negative' => true
                    ],
                    'fieldPrefix' => ''
                ]
            )
        );
    }

    protected function prepareFilterSetting()
    {
        if ($this->attributeObject->getId()) {
            $filterCode = \Amasty\Shopby\Helper\FilterSetting::ATTR_PREFIX . $this->attributeObject->getAttributeCode();
            $this->setting->load($filterCode, 'filter_code');
            if (!$this->setting->getId()) {
                $this->setting->setRelNofollow(RelNofollow::MODE_AUTO);
            }
            $this->setting->setFilterCode($filterCode);
            if ($filterCode == \Amasty\Shopby\Helper\FilterSetting::ATTR_PREFIX . \Amasty\Shopby\Helper\Category::ATTRIBUTE_CODE) {
                $this->setting->addData($this->filterSettingHelper->getCustomDataForCategoryFilter());
            }
        }
    }
}
