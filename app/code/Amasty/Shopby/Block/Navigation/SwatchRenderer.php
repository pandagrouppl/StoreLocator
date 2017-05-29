<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */
namespace Amasty\Shopby\Block\Navigation;



use Magento\Catalog\Model\Layer\Filter\Item as FilterItem;
use Magento\Catalog\Model\ResourceModel\Layer\Filter\AttributeFactory;
use Magento\Eav\Model\Entity\Attribute;
use Amasty\Shopby\Helper\FilterSetting;
use Magento\Eav\Model\Entity\Attribute\Option;

class SwatchRenderer extends \Magento\Swatches\Block\LayeredNavigation\RenderLayered
    implements RendererInterface
{
    const VAR_COUNT = 'amasty_shopby_count';
    const VAR_SELECTED = 'amasty_shopby_selected';

    protected $urlBuilderHelper;

    protected $settingHelper;

    protected $filterSetting;

    protected $helper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param Attribute $eavAttribute
     * @param AttributeFactory $layerAttribute
     * @param \Magento\Swatches\Helper\Data $swatchHelper
     * @param \Magento\Swatches\Helper\Media $mediaHelper
     * @param \Amasty\Shopby\Helper\UrlBuilder $urlBuilderHelper
     * @param \Amasty\Shopby\Helper\Data $helper
     * @param FilterSetting $settingHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context, Attribute $eavAttribute,
        AttributeFactory $layerAttribute,
        \Magento\Swatches\Helper\Data $swatchHelper,
        \Magento\Swatches\Helper\Media $mediaHelper,
        \Amasty\Shopby\Helper\UrlBuilder $urlBuilderHelper,
        \Amasty\Shopby\Helper\Data $helper,
        FilterSetting $settingHelper,
        array $data = []
    ) {
        parent::__construct(
            $context, $eavAttribute, $layerAttribute, $swatchHelper,
            $mediaHelper, $data
        );

        $this->settingHelper = $settingHelper;
        $this->helper = $helper;
        $this->urlBuilderHelper = $urlBuilderHelper;
    }

    /**
     * @param string $attributeCode
     * @param int $optionId
     * @return string
     */
    public function buildUrl($attributeCode, $optionId)
    {
        return $this->urlBuilderHelper->buildUrl($this->filter, $optionId);
    }

    /**
     * Get relevant path to template
     *
     * @return string
     */
    public function getTemplate()
    {
        $template = null;

        $setting = $this->getFilterSetting();

        switch($setting->getDisplayMode()){
            case \Amasty\Shopby\Model\Source\DisplayMode::MODE_IMAGES_LABELS:
                $template = 'Amasty_Shopby::layer/filter/swatch/imageslabels.phtml';
                break;
            default:
                $template = 'Amasty_Shopby::layer/filter/swatch/default.phtml';
                break;
        }

        return $template;
    }

    /**
     * @return \Amasty\Shopby\Api\Data\FilterSettingInterface
     */
    public function getFilterSetting()
    {
        if(is_null($this->filterSetting)) {
            $this->filterSetting = $this->settingHelper->getSettingByLayerFilter($this->filter);
        }
        return $this->filterSetting;
    }

    /**
     * @return array
     */
    public function getSwatchData()
    {
        $swatchData = parent::getSwatchData();

        if ($this->getFilterSetting()->getSortOptionsBy() == \Amasty\Shopby\Model\Source\SortOptionsBy::NAME){
            uasort($swatchData['options'], [$this, 'sortSwatchData']);
        }

        return $swatchData;
    }

    /**
     * @param FilterItem $filterItem
     * @param Option $swatchOption
     * @return array
     */
    protected function getOptionViewData(FilterItem $filterItem, Option $swatchOption)
    {
        $data = parent::getOptionViewData($filterItem, $swatchOption);
        $data[self::VAR_COUNT] = $filterItem->getCount();
        $data[self::VAR_SELECTED] = $this->isFilterItemSelected($filterItem);

        return $data;
    }

    /**
     * @param $a
     * @param $b
     * @return int
     */
    public function sortSwatchData($a, $b)
    {
        return strcmp($a['label'], $b['label']);
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getTooltipHtml()
    {
        return $this->getLayout()->createBlock(
            'Amasty\Shopby\Block\Navigation\Widget\Tooltip'
        )
            ->setFilterSetting($this->getFilterSetting())
            ->toHtml();
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getHideMoreOptionsHtml()
    {
        return $this->getLayout()->createBlock(
            'Amasty\Shopby\Block\Navigation\Widget\HideMoreOptions'
        )
            ->setFilterSetting($this->getFilterSetting())
            ->toHtml();
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        $html = parent::toHtml();

        if ($this->getFilterSetting()->isShowTooltip()) {
            $html .= $this->getTooltipHtml();
        }

        if ($this->getFilterSetting()->getNumberUnfoldedOptions())
        {
            $html .= $this->getHideMoreOptionsHtml();
        }

        return $html;
    }

    /**
     * @param \Amasty\Shopby\Model\Layer\Filter\Item $filterItem
     * @return int
     */
    public function isFilterItemSelected(\Amasty\Shopby\Model\Layer\Filter\Item $filterItem)
    {
        return $this->helper->isFilterItemSelected($filterItem);
    }

    /**
     * @return bool
     */
    public function collectFilters()
    {
        return $this->_scopeConfig->getValue(self::XML_CONFIG_SUBMIT_FILTER, \Magento\Store\Model\ScopeInterface::SCOPE_STORE) === \Amasty\Shopby\Model\Source\SubmitMode::BY_BUTTON_CLICK ? '1' : '0';
    }

    public function getFilter()
    {
        return $this->filter;
    }
}
