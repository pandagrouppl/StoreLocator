<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\ShopbyBrand\Block\Widget;

use Amasty\Shopby\Helper\OptionSetting as OptionSettingHelper;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Model\Product\Attribute\Repository;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Registry;

/**
 * Class BrandSlider
 * @package Amasty\ShopbyBrand\Block\Widget
 * @author Evgeni Obukhovsky
 */
class BrandSlider extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    const HTML_ID = 'amslider_id';
    const DEFAULT_IMG_WIDTH = 130;

    /** @var  Repository */
    protected $_repository;

    /** @var  ScopeConfigInterface */
    protected $_scopeConfig;

    /** @var  OptionSettingHelper */
    protected $_optionSettingHelper;

    /** @var  Registry */
    protected $_registry;

    /** @var array $_items */
    protected $_items;

    /** @var int */
    protected $_id;

    public function __construct(
        Context $context,
        Repository $repository,
        OptionSettingHelper $optionSetting,
        Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_repository = $repository;
        $this->_optionSettingHelper = $optionSetting;
        $this->_registry = $registry;
        $this->getItems();
    }

    public function getItems()
    {
        if (isset($this->_items)) {
            return $this->_items;
        }

        $attribute_code = $this->_scopeConfig->getValue('amshopby_brand/general/attribute_code');
        if ($attribute_code == '') {
            return null;
        }

        $options = $this->_repository->get($attribute_code)->getOptions();
        array_shift($options);

        $items = [];
        foreach ($options as $option) {
            $filter_code = \Amasty\Shopby\Helper\FilterSetting::ATTR_PREFIX . $attribute_code;
            $setting = $this->_optionSettingHelper->getSettingByValue($option->getValue(), $filter_code, $this->_storeManager->getStore()->getId());
            if ($setting->getIsFeatured() && $setting->getSliderImageUrl()) {
                $items[] = [
                    'label' => $setting->getLabel(),
                    'url' => $setting->getUrlPath(),
                    'img' => $setting->getSliderImageUrl(),
                    'position'  => $setting->getSliderPosition()
                ];
            }
        }

        $this->_items = $items;
        return $items;
    }

    /**
     * Apply options from config
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $configValues = $this->_scopeConfig->getValue('amshopby_brand/slider',  \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        foreach ($configValues as $option => $value) {
            if (is_null($this->getData($option))) {
                $this->setData($option, $value);
            }
        }
        if ($this->getData('sort_by') == 'name') {
            usort($this->_items, array($this, '_sortByName'));
        } else {
            usort($this->_items, array($this, '_sortByPosition'));
        }
        return parent::_beforeToHtml();
    }

    /**
     * @return array
     */
    public function getSliderOptions()
    {
        $options = [];
        $itemsPerView               = max(1, $this->getData('items_number'));
        $this->setData('items_number', min($itemsPerView, count($this->_items)));
        $options['slidesPerView']   = $this->getData('items_number');
        $options['loop']            = $this->getData('infinity_loop')  ? 'true' : 'false';
        $options['simulateTouch']   = $this->getData('simulate_touch') ? 'true' : 'false';
        if ($this->getData('pagination_show')) {
            $options['pagination']  = '".swiper-pagination"';
            $options['paginationClickable'] = $this->getData('pagination_clickable') ? 'true' : 'false';
        }
        if ($this->getData('autoplay')) {
            $options['autoplay'] = intval($this->getData('autoplay_delay'));
        }
        return $options;
    }

    /**
     * Get html id attribute for slider in a case there are several sliders on the page.
     * @return string
     */
    public function getSliderId()
    {
        if ($this->_id) {
            return $this->_id;
        }
        $sliderId = intval($this->_registry->registry(self::HTML_ID));
        $sliderId++;
        $this->_registry->unregister(self::HTML_ID);
        $this->_registry->register(self::HTML_ID, $sliderId);
        $this->_id = self::HTML_ID . $sliderId;
        return $this->_id;
    }

    public function toHtml()
    {
        if (!count($this->getItems())) {
            return '';
        }
        return parent::toHtml();
    }
    
    /**
     * @param $a
     * @param $b
     * @return int
     */
    protected function _sortByPosition($a, $b)
    {
        return $a['position'] - $b['position'];
    }
    
    /**
     * @param $a
     * @param $b
     * @return int
     */
    protected function _sortByName($a, $b)
    {
        $a['label'] = trim($a['label']);
        $b['label'] = trim($b['label']);

        if ($a == '') return 1;
        if ($b == '') return -1;

        $x = substr($a['label'], 0, 1);
        $y = substr($b['label'], 0, 1);
        if (is_numeric($x) && !is_numeric($y)) return 1;
        if (!is_numeric($x) && is_numeric($y)) return -1;

        if (function_exists('mb_strtoupper')) {
            $res = strcmp(mb_strtoupper($a['label']), mb_strtoupper($b['label']));
        } else {
            $res = strcmp(strtoupper($a['label']), strtoupper($b['label']));
        }
        return $res;
    }

    public function getHeaderColor()
    {
        $res = $this->_scopeConfig
            ->getValue('amshopby_brand/slider/slider_header_color',  \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $res ? $res : '#F58C12';
    }

    public function getTitleColor()
    {
        $res = $this->_scopeConfig
            ->getValue('amshopby_brand/slider/slider_title_color',  \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $res ? $res : '#FFFFFF';
    }

    public function getTitle()
    {
        $title = $this->_scopeConfig
            ->getValue('amshopby_brand/slider/slider_title',  \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $this->escapeHtml($title);
    }
}