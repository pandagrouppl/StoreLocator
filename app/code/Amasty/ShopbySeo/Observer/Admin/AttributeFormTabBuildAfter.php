<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\ShopbySeo\Observer\Admin;

use Amasty\Shopby\Helper\Category;
use Amasty\ShopbySeo\Model\Source\IndexMode;
use Amasty\ShopbySeo\Model\Source\RelNofollow;
use Magento\Catalog\Model\Entity\Attribute;
use Magento\Config\Model\Config\Source\Yesno;
use Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory;
use Magento\Framework\Data\Form;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Registry;
use Magento\Backend\Block\Widget\Form\Element\Dependence;

class AttributeFormTabBuildAfter implements ObserverInterface
{
    /** @var  Yesno */
    protected $yesno;

    /** @var  IndexMode */
    protected $indexMode;

    /** @var  Attribute */
    protected $attribute;

    /** @var  FieldFactory */
    protected $dependencyFieldFactory;

    /** @var RelNofollow */
    protected $relNofollow;

    public function __construct(Yesno $yesno, IndexMode $indexMode, RelNofollow $relNofollow, Registry $registry, FieldFactory $fieldFactory)
    {
        $this->yesno = $yesno;
        $this->indexMode = $indexMode;
        $this->relNofollow = $relNofollow;
        $this->dependencyFieldFactory = $fieldFactory;
        $this->attribute = $registry->registry('entity_attribute');
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var Form $form */
        $form = $observer->getData('form');

        if ($this->attribute->getFrontendInput() == 'price') {
            return;
        }

        $fieldset = $form->addFieldset(
            'shopby_fieldset_seo',
            ['legend' => __('SEO')]
        );

        if ($this->attribute->getAttributeCode() != Category::ATTRIBUTE_CODE) {
            $fieldset->addField(
                'is_seo_significant',
                'select',
                [
                    'name'   => 'is_seo_significant',
                    'label'  => __('Generate SEO URL'),
                    'title'  => __('Generate SEO URL'),
                    'values' => $this->yesno->toOptionArray(),
                ]
            );
        }

        $fieldset->addField(
            'index_mode',
            'select',
            [
                'name'     => 'index_mode',
                'label'    => __('Allow Google to INDEX the Category Page with the Filter Applied'),
                'title'    => __('Allow Google to INDEX the Category Page with the Filter Applied'),
                'values'   => $this->indexMode->toOptionArray(),
            ]
        );

        $fieldset->addField(
            'follow_mode',
            'select',
            [
                'name'     => 'follow_mode',
                'label'    => __('Allow Google to FOLLOW Links on the Category Page with the Filter Applied'),
                'title'    => __('Allow Google to FOLLOW Links on the Category Page with the Filter Applied'),
                'values'   => $this->indexMode->toOptionArray(),
            ]
        );

        $fieldset->addField(
            'rel_nofollow',
            'select',
            [
                'name'     => 'rel_nofollow',
                'label'    => __('Add rel="nofollow" to filter links'),
                'title'    => __('Add rel="nofollow" to filter links'),
                'values'   => $this->relNofollow->toOptionArray(),
            ]
        );

        if ($this->attribute->getAttributeCode() == Category::ATTRIBUTE_CODE) {
            /** @var Dependence $dependence */
            $dependence = $observer->getData('dependence');
            $dependence->addFieldMap('index_mode', 'index_mode');
            $dependence->addFieldMap('follow_mode', 'follow_mode');
            $dependence->addFieldMap('seo_admin_category_notice', 'seo_admin_category_notice');
            $dependence->addFieldDependence('index_mode', 'is_multiselect',
                $this->dependencyFieldFactory->create(
                    ['fieldData' => ['value' => '1'], 'fieldPrefix' => '']
                )
            );
            $dependence->addFieldDependence('follow_mode', 'is_multiselect',
                $this->dependencyFieldFactory->create(
                    ['fieldData' => ['value' => '1'], 'fieldPrefix' => '']
                )
            );
        }
        $dependence = $observer->getData('dependence');
        $dependence->addFieldsets(
            $fieldset->getHtmlId(),
            \Amasty\Shopby\Block\Adminhtml\Product\Attribute\Edit\Tab\Shopby::FIELD_FRONTEND_INPUT,
            ['value' => 'price', 'negative' => false]
        );
    }
}
