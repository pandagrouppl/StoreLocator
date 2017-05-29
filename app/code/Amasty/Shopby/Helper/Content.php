<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\Shopby\Helper;

use Amasty\Shopby\Api\CategoryDataSetterInterface;
use Amasty\Shopby\Api\Data\FilterSettingInterface;
use Magento\Catalog\Model\Layer\Filter\FilterInterface;
use Amasty\Shopby\Model\Source\AbstractFilterDataPosition;
use Amasty\Shopby\Model\Category\Manager as CategoryManager;
use Amasty\ShopbyPage\Model\Page as PageEntity;
use Magento\Store\Model\ScopeInterface;
use Magento\Catalog\Model\Category as CategoryModel;
use Magento\Framework\App\Helper\AbstractHelper;

class Content extends AbstractHelper implements CategoryDataSetterInterface
{
    const APPLY_TO_HEADING = 'am_apply_to_heading';
    const APPLY_TO_META = 'am_apply_to_meta';
    
    /** @var  Category */
    protected $_category;

    /** @var  \Amasty\Shopby\Api\Data\OptionSettingInterface[] */
    protected $_optionSettings;

    /** @var \Magento\Catalog\Model\Layer */
    protected $_layer;

    /** @var \Amasty\Shopby\Model\Layer\FilterList  */
    protected $_filterList;

    /** @var \Magento\Framework\App\Request\Http  */
    protected $_request;

    /** @var \Magento\Framework\Registry  */
    protected $_registry;

    /** @var  OptionSetting */
    protected $_optionHelper;

    /** @var Data  */
    protected $_helper;

    /** @var  array */
    protected $_settings = [];

    /** @var  string */
    protected $_storeId;

    /** @var bool  */
    protected $_headingApplyAll;

    /** @var bool  */
    protected $_metaApplyAll;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \Amasty\Shopby\Model\Layer\FilterList $filterList
     * @param \Magento\Store\Model\StoreManager $storeManager
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\Registry $registry
     * @param OptionSetting $optionHelper
     * @param Data $dataHelper
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Amasty\Shopby\Model\Layer\FilterList $filterList,
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Registry $registry,
        OptionSetting $optionHelper,
        Data $dataHelper
    ) {
        parent::__construct($context);
        $this->_layer =  $layerResolver->get();
        $this->_filterList =  $filterList;
        $this->_storeId = $storeManager->getStore()->getId();
        $this->_request = $request;
        $this->_registry= $registry;
        $this->_helper = $dataHelper;
        $this->_optionHelper = $optionHelper;
        $this->_initCategoryDataSettings();
    }

    protected function _initCategoryDataSettings()
    {
        $this->_settings['meta'] = $this->scopeConfig->getValue('amshopby/meta', ScopeInterface::SCOPE_STORE);
        $this->_settings['heading'] = $this->scopeConfig->getValue('amshopby/heading', ScopeInterface::SCOPE_STORE);
        if (!isset($this->_settings['meta']['apply_to'])) {
            $this->_settings['meta']['apply_to'] = '';
        }
        if (!isset($this->_settings['heading']['apply_to'])) {
            $this->_settings['heading']['apply_to'] = '';
        }
        $allAttributes = \Amasty\Shopby\Model\Source\Attribute\Extended::ALL;
        $this->_headingApplyAll = $this->_settings['heading'] && in_array($allAttributes, explode(',', $this->_settings['heading']['apply_to']));
        $this->_metaApplyAll = $this->_settings['meta'] && in_array($allAttributes, explode(',', $this->_settings['meta']['apply_to']));
    }

    /**
     * Apply filters first in order to load currently applied settings.
     * @return $this
     */
    protected function _applyFilters()
    {
        //at this point filters are not applied yet.
        foreach ($this->_filterList->getAllFilters($this->_layer) as $filter) {
            $filter->apply($this->_request);
        }
        return $this;
    }

    /**
     * Return applicable data types for an attribute.
     * @param string $attributeId
     * @return array
     */
    protected function _getFilterDataApplicable($attributeId)
    {
        $result = [];
        if ($this->_headingApplyAll || in_array($attributeId, explode(',', $this->_settings['heading']['apply_to']))
        ) {
            $result[] = Content::APPLY_TO_HEADING;
        }
        if ($this->_metaApplyAll || in_array($attributeId, explode(',', $this->_settings['meta']['apply_to']))
        ) {
            $result[] = Content::APPLY_TO_META;
        }
        return $result;
    }

    /**
     * Get currently applied option settings which are applicable to change category data.
     * @return \Amasty\Shopby\Api\Data\OptionSettingInterface[]
     */
    protected function _getAppliedOptionSettings()
    {
        if ($this->_optionSettings === null) {
            $this->_applyFilters();
            $this->_optionSettings = [];
            foreach ($this->_helper->getSelectedFiltersSettings() as $row) {
                /** @var FilterInterface $filter */
                $filter = $row['filter'];
                /** @var \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attributeModel */
                $attributeModel = $filter->getData('attribute_model');
                if ($attributeModel === null) {
                    continue;
                }
                $filterApplicableTo = $this->_getFilterDataApplicable($attributeModel->getAttributeId());
                if (!$filterApplicableTo) {
                    continue;
                }
                /** @var FilterSettingInterface $filterSetting */
                $filterSetting = $row['setting'];
                $values = explode(',', $this->_request->getParam($filter->getRequestVar()));
                foreach ($values as $v) { 
                    $option = $this->_optionHelper
                        ->getSettingByValue($v, $filterSetting->getFilterCode(), $this->_storeId);
                    foreach ($filterApplicableTo as $applyTo) {
                        $option->setData($applyTo, true);
                    }
                    $this->_optionSettings[] = $option;
                }
            }
        }
        return $this->_optionSettings;
    }

    /**
     * Set category data from currently applied filters.
     * @param CategoryModel $category
     * @return $this;
     */
    public function setCategoryData(CategoryModel $category)
    {
        if (is_object($this->_registry->registry(PageEntity::MATCHED_PAGE))) {
            return $this;
        }
        if (!$this->_getAppliedOptionSettings()) {
            return $this;
        }

        $this->_category = $category;

        $appliedBrandVal = (int) $category->getData(CategoryDataSetterInterface::APPLIED_BRAND_VALUE);
        $data = $this->_getOptionsData($appliedBrandVal);

        $this->_setTitle($data['title'])
            ->_setDescription($data['description'])
            ->_setImg($data['img_url'])
            ->_setCmsBlock($data['cms_block'])
            ->_setMetaTitle($data['meta_title'])
            ->_setMetaDescription($data['meta_description'])
            ->_setMetaKeywords($data['meta_keywords']);
        return $this;
    }

    /**
     * Get data from all applicable options.
     * @param int $appliedBrandVal
     * @return array
     */
    protected function _getOptionsData($appliedBrandVal)
    {
        $result = [
            'title' => [],
            'description' => [],
            'cms_block' => null,
            'img_url' => null,
            'meta_title' => [],
            'meta_description' => [],
            'meta_keywords' => [],

        ];

        foreach ($this->_getAppliedOptionSettings() as $opt) {
            if ($opt->getValue() == $appliedBrandVal) {
                continue;
            }
            if ($opt->getData(Content::APPLY_TO_HEADING)) {
                if ($opt->getTitle()) {
                    $result['title'][] = $opt->getTitle();
                }
                if ($opt->getDescription()) {
                    $result['description'][] = $opt->getDescription();
                }
                if ($opt->getTopCmsBlockId() && $result['cms_block'] === null) {
                    $result['cms_block'] = $opt->getTopCmsBlockId();
                }
                if ($opt->getImageUrl() && $result['img_url'] === null) {
                    $result['img_url'] = $opt->getImageUrl();
                }
            }
            if ($opt->getData(Content::APPLY_TO_META)) {
                if ($opt->getMetaTitle()) {
                    $result['meta_title'][] = $opt->getMetaTitle();
                }
                if ($opt->getMetaDescription()) {
                    $result['meta_description'][] = $opt->getMetaDescription();
                }
                if ($opt->getMetaKeywords()) {
                    $result['meta_keywords'][] = $opt->getMetaKeywords();
                }
            }
        }
        return $result;
    }

    /**
     * Set category title.
     * @param array $titles
     * @return $this
     */
    protected function _setTitle($titles)
    {
        $position = $this->_settings['heading']['add_title'];
        $title = $this->_insertContent(
            $this->_category->getName(),
            $titles,
            $position,
            $this->_settings['heading']['title_separator']
        );
        $this->_category->setName($title);
        return $this;
    }

    /**
     * Set category meta title.
     * @param array $metaTitles
     * @return $this
     */
    protected function _setMetaTitle($metaTitles)
    {
        $position = $this->_settings['meta']['add_title'];
        $metaTitle = $this->_insertContent(
            $this->_category->getData('meta_title'),
            $metaTitles,
            $position,
            $this->_settings['meta']['title_separator']
        );
        $this->_category->setData('meta_title', $metaTitle);
        return $this;
    }

    /**
     * Set category description.
     * @param array $descriptions
     * @return $this
     */
    protected function _setDescription($descriptions)
    {
        $position = $this->_settings['heading']['add_description'];
        if ($descriptions && $position != AbstractFilterDataPosition::DO_NOT_ADD) {
            $oldDescription = $this->_category->getData('description');
            $description = '<span class="amshopby-descr">' . join('<br>', $descriptions) . '</span>';
            switch ($position) {
                case AbstractFilterDataPosition::AFTER:
                    $description = $oldDescription ? $oldDescription . '<br>' . $description : $description;
                    break;
                case AbstractFilterDataPosition::BEFORE:
                    $description = $oldDescription ? $description . '<br>' . $oldDescription : $description;
                    break;
            }
            $this->_category->setData('description', $description);
        }
        return $this;
    }

    /**
     * Set category meta description.
     * @param array $metaDescriptions
     * @return $this
     */
    protected function _setMetaDescription(array $metaDescriptions)
    {
        $position = $this->_settings['meta']['add_description'];
        $metaDescription = $this->_insertContent(
            $this->_category->getData('meta_description'),
            $metaDescriptions,
            $position,
            $this->_settings['meta']['description_separator']
        );
        $this->_category->setData('meta_description', $metaDescription);
        return $this;
    }

    /**
     * Set category meta keywords.
     * @param array $metaKeywords
     * @return $this
     */
    protected function _setMetaKeywords($metaKeywords)
    {
        $position = $this->_settings['meta']['add_keywords'];
        $metaKeyword = $this->_insertContent(
            $this->_category->getData('meta_keywords'),
            $metaKeywords,
            $position,
            ', '
        );
        $this->_category->setData('meta_keywords', $metaKeyword);
        return $this;
    }

    /**
     * Set category image.
     * @param string|null $imgUrl
     * @return $this
     */
    protected function _setImg($imgUrl)
    {
        if ($imgUrl !== null && $this->_settings['heading']['replace_image']) {
            $this->_category->setData(CategoryManager::CATEGORY_SHOPBY_IMAGE_URL, $imgUrl);
        }
        return $this;
    }

    /**
     * Set category CMS block.
     * @param string|null $blockId
     * @return $this
     */
    protected function _setCmsBlock($blockId)
    {
        if ($blockId !== null && $this->_settings['heading']['replace_cms_block']) {
            $this->_category->setData('landing_page', $blockId);
            $this->_category->setData(CategoryManager::CATEGORY_FORCE_MIXED_MODE, 1);
        }
        return $this;
    }

    /**
     * replace an original data considering a position and a separator.
     * @param string $original
     * @param array $newParts
     * @param string $position
     * @param string $separator
     * @return string
     */
    protected function _insertContent($original, $newParts, $position, $separator)
    {
        if ($newParts && $position != AbstractFilterDataPosition::DO_NOT_ADD) {
            if ($original) {
                switch ($position) {
                    case AbstractFilterDataPosition::AFTER:
                        array_unshift($newParts, $original);
                        break;
                    case AbstractFilterDataPosition::BEFORE:
                        array_push($newParts, $original);
                        break;
                }
            }
            $result = join($separator, $newParts);
        } else {
            $result = $original;
        }
        $result = $this->_trim($result, $separator);

        return $result;
    }

    /**
     * Trim a string considering a certain separator.
     * @param string $str
     * @param string $separator
     * @return string
     */
    protected function _trim($str, $separator = ',')
    {
        $str = strip_tags($str);
        $str = str_replace('"', '', $str);
        return trim($str, " " . $separator);
    }
}
