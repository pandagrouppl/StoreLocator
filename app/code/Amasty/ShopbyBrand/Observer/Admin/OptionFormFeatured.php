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
use Magento\Framework\ObjectManagerInterface;

/**
 * Class OptionFormFeatured
 * @package Amasty\ShopbyBrand\Observer\Admin
 * @author Evgeni Obukhovsky
 */
class OptionFormFeatured implements ObserverInterface
{
    /** @var Page */
    protected $page;

    /** @var ObjectManagerInterface */
    protected $_objectManager;

    public function __construct(Page $page, ObjectManagerInterface $objectManager)
    {
        $this->page = $page;
        $this->_objectManager = $objectManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var Form $form */
        $form = $observer->getData('form');
        /** @var OptionSetting $setting */
        $model = $observer->getData('setting');
        $storeId = $observer->getData('store');

        $featuredFieldset = $form->addFieldset('featured_fieldset', ['legend' => __('Slider Options'), 'class'=>'form-inline']);
        $listYesNo = $this->_objectManager->create('Magento\Config\Model\Config\Source\Yesno')->toOptionArray();

        $featuredFieldset->addField(
            'is_featured',
            'select',
            ['name' => 'is_featured', 'label' => __('Show in Brand Slider'), 'title' => __('Show in Brand Slider'), 'values'=>$listYesNo]
        );

        $featuredFieldset->addField(
            'slider_position',
            'text',
            ['name' => 'slider_position', 'label' => __('Position in Slider'), 'title' => __('Position in Slider')]
        );
        $img = $model->getSliderImageUrl();
        $strictImg = $model->getSliderImageUrl(true);
        $sliderImage = '';
        $imageUseDefault = $model->getData('slider_image_use_default') && $model->getCurrentStoreId();
        if($img) {
            $styles = $this->_getStyles($storeId);
            $sliderImage = '
            <div><br>
            <input type="checkbox" id="slider_image_delete" name="slider_image_delete" value="1" ' .
                (($imageUseDefault || !$strictImg) ? 'disabled="disabled"' : '' ).
                ' />
            <label for="slider_image_delete">' . __('Delete Image') . '</label>
            <br><br>            
            <img src="' . $img . '" style="' . $styles
                . ($imageUseDefault ? 'display:none;"' : '"') . '/></div>';
        }

        $note = '';
        if (!$img) {
            $note = __('Brand will not be included in slider without an image.');
        } elseif (!$strictImg) {
            $note = __('Page content image is used.');
        }
        $featuredFieldset->addField(
            'slider_image',
            'file',
            [
                'name' => 'slider_image',
                'label' => __('Slider Image'),
                'title' => __('Slider Image'),
                'note'  => $note,
                'after_element_html'=>$sliderImage
            ]
        );
    }

    /**
     * Get width and height of slider image
     *
     * @param $storeId
     * @return string
     */
    protected function _getStyles($storeId)
    {
        $config = $this->_objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface');
        $width = abs(intval($config->getValue('amshopby_brand/slider/image_width',  \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId)));
        $height = abs(intval($config->getValue('amshopby_brand/slider/image_height',  \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId)));
        $res = 'max-width:' . $width . 'px;';
        if ($height) {
            $res .= 'max-height:' . $height . 'px;';
        }
        return $res;
    }
}
