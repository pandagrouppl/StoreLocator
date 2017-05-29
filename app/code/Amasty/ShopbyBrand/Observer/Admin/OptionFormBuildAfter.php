<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\ShopbyBrand\Observer\Admin;

use Amasty\Shopby\Model\OptionSetting;
use Magento\Catalog\Model\Category\Attribute\Source\Page;
use Magento\Framework\Data\Form;
use Magento\Framework\Event\ObserverInterface;

class OptionFormBuildAfter implements ObserverInterface
{
    /** @var Page */
    protected $page;

    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var Form $form */
        $form = $observer->getData('form');

        /** @var OptionSetting $setting */
        $setting = $observer->getData('setting');

        $this->addMetaDataFieldset($form);
        $this->addProductListFieldset($form, $setting);
    }

    protected function addMetaDataFieldset(\Magento\Framework\Data\Form $form)
    {
        $metaDataFieldset = $form->addFieldset('meta_data_fieldset', ['legend' => __('Meta Data'), 'class'=>'form-inline']);

        $metaDataFieldset->addField(
            'meta_title',
            'text',
            ['name' => 'meta_title', 'label' => __('Meta Title'), 'title' => __('Meta Title')]
        );

        $metaDataFieldset->addField(
            'meta_description',
            'textarea',
            ['name' => 'meta_description', 'label' => __('Meta Description'), 'title' => __('Meta Description')]
        );

        $metaDataFieldset->addField(
            'meta_keywords',
            'textarea',
            ['name' => 'meta_keywords', 'label' => __('Meta Keywords'), 'title' => __('Meta Keywords')]
        );
    }

    protected function addProductListFieldset(\Magento\Framework\Data\Form $form, OptionSetting $model)
    {
        $productListFieldset = $form->addFieldset('product_list_fieldset', ['legend' => __('Page Content'), 'class'=>'form-inline']);

        $productListFieldset->addField(
            'title',
            'text',
            ['name' => 'title', 'label' => __('Page Title'), 'title' => __('Title')]
        );

        $productListFieldset->addField(
            'description',
            'textarea',
            ['name' => 'description', 'label' => __('Description'), 'title' => __('Description')]
        );
        $categoryImage = '';
        $categoryImageUseDefault = $model->getData('image_use_default') && $model->getCurrentStoreId();
        if($model->getImageUrl()) {
            $categoryImage = '
            <div>
            <br>
            <input type="checkbox" id="image_delete" name="image_delete" value="1" ' .
                ($categoryImageUseDefault ? 'disabled="disabled"' : '' ).
            ' />
            <label for="image_delete">' . __('Delete Image') . '</label>
            <br>
            <br><img src="'.$model->getImageUrl().'" ' .($categoryImageUseDefault ? 'style="display:none"' : '').'/></div>';
        }


        $productListFieldset->addField(
            'image',
            'file',
            ['name' => 'image', 'label' => __('Image'), 'title' => __('Image'), 'after_element_html'=>$categoryImage]
        );

        $listCmsBlocks = $this->page->toOptionArray();

        $productListFieldset->addField(
            'top_cms_block_id',
            'select',
            ['name' => 'top_cms_block_id', 'label' => __('Top CMS Block'), 'title' => __('Top CMS Block'), 'values'=>$listCmsBlocks]
        );

//        $productListFieldset->addField(
//            'bottom_cms_block_id',
//            'select',
//            ['name' => 'bottom_cms_block_id', 'label' => __('Bottom CMS Block'), 'title' => __('Bottom CMS Block'), 'values'=>$listCmsBlocks]
//        );
    }
}
