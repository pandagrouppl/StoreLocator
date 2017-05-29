<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\Shopby\Model;


use Amasty\Shopby\Api\Data\FilterSettingInterface;
use Amasty\Shopby\Model\Source\DisplayMode;
use Magento\Framework\DataObject\IdentityInterface;

class FilterSetting extends \Magento\Framework\Model\AbstractModel implements FilterSettingInterface, IdentityInterface
{
    const CACHE_TAG = 'amshopby_filter_setting';

    protected $_eventPrefix = 'amshopby_filter_setting';

    /**
     * @var \Magento\Catalog\Helper\Data
     */
    protected $catalogHelper;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Helper\Data $catalogHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->catalogHelper = $catalogHelper;
        $this->scopeConfig = $scopeConfig;
        parent::__construct(
            $context, $registry, $resource, $resourceCollection, $data
        );
    }


    protected function _construct()
    {
        $this->_init('Amasty\Shopby\Model\ResourceModel\FilterSetting');
    }

    public function getId()
    {
        return $this->getData(self::FILTER_SETTING_ID);
    }

    public function getDisplayMode()
    {
        return $this->getData(self::DISPLAY_MODE);
    }

    public function getFilterCode()
    {
        return $this->getData(self::FILTER_CODE);
    }

    public function getFollowMode()
    {
        return $this->getData(self::FOLLOW_MODE);
    }

    public function getRelNofollow()
    {
        return $this->getData(self::REL_NOFOLLOW);
    }

    public function getHideOneOption()
    {
        return $this->getData(self::HIDE_ONE_OPTION);
    }

    /**
     * @return bool|null
     */
    public function getAddFromToWidget()
    {
        return $this->getData(self::ADD_FROM_TO_WIDGET);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getIndexMode()
    {
        return $this->getData(self::INDEX_MODE);
    }

    public function getUnitsLabel($currencySymbol = '')
    {
        if($this->getUnitsLabelUseCurrencySymbol()) {
            return $currencySymbol;
        }
        return parent::getUnitsLabel();
    }

    public function isMultiselect()
    {
        return $this->getData(self::IS_MULTISELECT) && $this->isDisplayTypeAllowsMultiselect();
    }

    public function isSeoSignificant()
    {
        return $this->getData(self::IS_SEO_SIGNIFICANT);
    }

    public function isExpanded()
    {
        return $this->getData(self::IS_EXPANDED);
    }

    /**
     * @return int
     */
    public function getSortOptionsBy()
    {
        return $this->getData(self::SORT_OPTIONS_BY);
    }

    /**
     * @return int
     */
    public function getShowProductQuantities()
    {
        return $this->getData(self::SHOW_PRODUCT_QUANTITIES);
    }

    public function isShowProductQuantities()
    {
        $showProductQuantities = $this->getShowProductQuantities();
        if($showProductQuantities == \Amasty\Shopby\Model\Source\ShowProductQuantities::SHOW_DEFAULT || is_null($showProductQuantities)) {
            $showProductQuantities = $this->catalogHelper->shouldDisplayProductCountOnLayer();
        } else {
            $showProductQuantities = $showProductQuantities == \Amasty\Shopby\Model\Source\ShowProductQuantities::SHOW_NO ? false : true;
        }
        return $showProductQuantities;
    }

    public function isShowTooltip()
    {
        $isFilterTooltipsEnabled = $this->scopeConfig->isSetFlag('amshopby/tooltips/enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $tooltip = $this->getTooltip();
        return $isFilterTooltipsEnabled && !empty($tooltip);
    }

    /**
     * @return bool
     */
    public function isShowSearchBox()
    {
        return $this->getData(self::IS_SHOW_SEARCH_BOX);
    }

    /**
     * @return mixed
     */
    public function getNumberUnfoldedOptions()
    {
        return $this->getData(self::NUMBER_UNFOLDED_OPTIONS);
    }

    /**
     * @return mixed
     */
    public function getTooltip()
    {
        return $this->getData(self::TOOLTIP);
    }

    /**
     * @return string
     */
    public function getVisibleInCategories()
    {
        return $this->getData(self::VISIBLE_IN_CATEGORIES);
    }

    /**
     * @return array
     */
    public function getCategoriesFilter()
    {
        $this->getResource()->lookupCategoriesFilter($this);
        return $this->getData(self::CATEGORIES_FILTER);
    }

    /**
     * @return array
     */
    public function getAttributesFilter()
    {
        $this->getResource()->lookupAttributesFilter($this);
        return $this->getData(self::ATTRIBUTES_FILTER);
    }

    /**
     * @return array
     */
    public function getAttributesOptionsFilter()
    {
        $this->getResource()->lookupAttributesOptionsFilter($this);
        return $this->getData(self::ATTRIBUTES_OPTIONS_FILTER);
    }

    /**
     * @return bool
     */
    public function isUseAndLogic()
    {
        return $this->getData(self::IS_USE_AND_LOGIC) && $this->isMultiselect();
    }

    public function getBlockPosition()
    {
        return $this->getData(self::BLOCK_POSITION);
    }

    public function setHideOneOption($hideOenOption)
    {
        return $this->setData(self::HIDE_ONE_OPTION, $hideOenOption);
    }

    public function setId($id)
    {
        return $this->setData(self::FILTER_SETTING_ID, $id);
    }

    public function setDisplayMode($displayMode)
    {
        return $this->setData(self::DISPLAY_MODE, $displayMode);
    }

    public function setFilterCode($filterCode)
    {
        return $this->setData(self::FILTER_CODE, $filterCode);
    }

    public function setIndexMode($indexMode)
    {
        return $this->setData(self::INDEX_MODE, $indexMode);
    }

    public function setFollowMode($followMode)
    {
        return $this->setData(self::FOLLOW_MODE, $followMode);
    }

    public function setRelNofollow($relNofollow)
    {
        return $this->setData(self::REL_NOFOLLOW, $relNofollow);
    }

    public function setIsMultiselect($isMultiselect)
    {
        return $this->setData(self::FILTER_SETTING_ID, $isMultiselect);
    }

    public function setIsSeoSignificant($isSeoSignificant)
    {
        return $this->setData(self::IS_SEO_SIGNIFICANT, $isSeoSignificant);
    }

    public function setIsExpanded($isExpanded)
    {
        return $this->setData(self::IS_EXPANDED, $isExpanded);
    }

    /**
     * @param bool $addFromToWidget
     *
     * @return FilterSettingInterface
     */
    public function setAddFromToWidget($addFromToWidget)
    {
        return $this->setData(self::ADD_FROM_TO_WIDGET, $addFromToWidget);
    }

    /**
     * @param int $sortOptionsBy
     *
     * @return FilterSettingInterface
     */
    public function setSortOptionsBy($sortOptionsBy)
    {
        return $this->setData(self::SORT_OPTIONS_BY, $sortOptionsBy);
    }

    /**
     * @param int $showProductQuantities
     *
     * @return FilterSettingInterface
     */
    public function setShowProductQuantities($showProductQuantities)
    {
        return $this->setData(self::SHOW_PRODUCT_QUANTITIES, $showProductQuantities);
    }

    /**
     * @param bool $isShowSearchBox
     *
     * @return FilterSettingInterface
     */
    public function setIsShowSearchBox($isShowSearchBox)
    {
        return $this->setData(self::IS_SHOW_SEARCH_BOX, $isShowSearchBox);
    }

    /**
     * @param int $numberOfUnfoldedOptions
     *
     * @return FilterSettingInterface
     */
    public function setNumberUnfoldedOptions($numberOfUnfoldedOptions)
    {
        return $this->setData(self::NUMBER_UNFOLDED_OPTIONS, $numberOfUnfoldedOptions);
    }

    /**
     * @param string $tooltip
     *
     * @return $this
     */
    public function setTooltip($tooltip)
    {
        return $this->setData(self::TOOLTIP, $tooltip);
    }

    /**
     * @param string $visibleInCategories
     * @return string
     */
    public function setVisibleInCategories($visibleInCategories)
    {
        return $this->setData(self::VISIBLE_IN_CATEGORIES, $visibleInCategories);
    }

    /**
     * @param array $categoriesFilter
     * @return array
     */
    public function setCategoriesFilter($categoriesFilter)
    {
        return $this->setData(self::CATEGORIES_FILTER, $categoriesFilter);
    }

    /**
     * @param array $attributesFilter
     * @return array
     */
    public function setAttributesFilter($attributesFilter)
    {
        return $this->setData(self::ATTRIBUTES_FILTER, $attributesFilter);
    }

    /**
     * @param array $attributesOptionsFilter
     * @return array
     */
    public function setAttributesOptionsFilter($attributesOptionsFilter)
    {
        return $this->setData(self::ATTRIBUTES_OPTIONS_FILTER, $attributesOptionsFilter);
    }

    /**
     * @param bool $isUseAndLogic
     *
     * @return $this
     */
    public function setIsUseAndLogic($isUseAndLogic)
    {
        return $this->setData(self::IS_USE_AND_LOGIC);
    }

    protected function isDisplayTypeAllowsMultiselect()
    {
        return in_array($this->getDisplayMode(), [
            DisplayMode::MODE_DEFAULT,
            DisplayMode::MODE_DROPDOWN,
            DisplayMode::MODE_IMAGES,
            DisplayMode::MODE_IMAGES_LABELS,
            DisplayMode::MODE_TEXT_SWATCH
        ]) ;
    }

    /**
     * @param int $blockPosition
     *
     * @return $this
     */
    public function setBlockPosition($blockPosition)
    {
        return $this->setData(self::BLOCK_POSITION, $blockPosition);
    }

    /**
     * @param \Magento\Eav\Model\Entity\Attribute $attribute
     * @return $this
     */
    public function setAttributeModel($attribute)
    {
        $this->setData('attribute_model', $attribute);
        return $this;
    }

    /**
     * @return null | \Magento\Eav\Model\Entity\Attribute
     */
    public function getAttributeModel()
    {
        return $this->getData('attribute_model');
    }

    public function getEnableOverflowScroll()
    {
        $enableOverflowScroll = $this->scopeConfig->getValue('amshopby/general/enable_overflow_scroll', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $enableOverflowScroll;
    }
}
