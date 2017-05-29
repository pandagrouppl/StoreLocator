<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\Shopby\Block\Navigation;


use Magento\Framework\View\Element\Template;

class SwatchesChoose  extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Catalog\Model\Layer
     */
    protected $catalogLayer;
    /**
     * @var \Magento\Catalog\Model\Layer\FilterList
     */
    protected $filterList;
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;
    /**
     * @var \Magento\Swatches\Helper\Data
     */
    protected $swatchHelper;
    /**
     * @var \Amasty\Shopby\Helper\FilterSetting
     */
    protected $filterSettingHelper;

    /**
     * SwatchesChoose constructor.
     *
     * @param Template\Context                        $context
     * @param \Magento\Catalog\Model\Layer\Resolver   $layerResolver
     * @param \Magento\Catalog\Model\Layer\FilterList $filterList
     * @param \Magento\Swatches\Helper\Data           $swatchHelper
     * @param \Amasty\Shopby\Helper\FilterSetting     $filterSettingHelper
     * @param array                                   $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Model\Layer\FilterList $filterList,
        \Magento\Swatches\Helper\Data $swatchHelper,
        \Amasty\Shopby\Helper\FilterSetting $filterSettingHelper,
        array $data = []
    ) {
        $this->catalogLayer = $layerResolver->get();
        $this->filterList = $filterList;
        $this->request = $context->getRequest();
        $this->swatchHelper = $swatchHelper;
        $this->filterSettingHelper = $filterSettingHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSwatchesByJson()
    {
        $listApplyingSwatches = [];
        foreach($this->filterList->getFilters($this->catalogLayer) as $filter) {
            if(!$filter->getItemsCount()) {
                continue;
            }
            if ($filter->hasAttributeModel()) {
                if ($this->swatchHelper->isSwatchAttribute($filter->getAttributeModel())) {
                    $isApplyFilter = $this->request->getParam($filter->getRequestVar(), false);
                    if(!$isApplyFilter) {
                        continue;
                    }
                    $isApplyFilter = explode(",", $isApplyFilter);
                    if(count($isApplyFilter) == 1) {
                        $filterSetting = $this->filterSettingHelper->getSettingByLayerFilter($filter);
                        if($filterSetting->isSeoSignificant()) {
                            $listApplyingSwatches[$filter->getAttributeModel()->getAttributeCode()] = $isApplyFilter[0];
                        }
                    }
                }
            }
        }

        return json_encode($listApplyingSwatches);
    }
}
